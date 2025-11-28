#!/bin/bash
set -e

echo "# Fetching scoped dependencies from composer.json..."

# Extract scoped dependencies from composer.json extra.require-scoped
SCOPED_DEPS=$(jq -r '.extra["require-scoped"] // {} | to_entries | map("\(.key):\(.value)") | .[]' composer.json)

if [ -z "$SCOPED_DEPS" ]; then
    echo "  Warning: No require-scoped found in composer.json extra section"
    exit 1
fi

echo "# Installing scoped dependencies..."

# Require each scoped dependency
while IFS=: read -r package version; do
    echo "  Requiring: $package $version"
    composer require "$package" "$version" --quiet
done <<< "$SCOPED_DEPS"

echo "# Running Composer update..."

composer update --no-dev --quiet

echo "# Running Scoper..."

php-scoper add-prefix --force

composer update --no-dev --working-dir=build --quiet

echo "# Importing Build..."

rm -rf vendor_prefixed
mv build/vendor vendor_prefixed
rm -rf build

echo "# Prefixing Composer autoloader class names..."

# Extract the current hash from vendor_prefixed/autoload.php
if [ -f "vendor_prefixed/autoload.php" ]; then
    OLD_HASH=$(grep -o 'ComposerAutoloaderInit[a-f0-9]\{32\}' vendor_prefixed/autoload.php | head -n 1 | sed 's/ComposerAutoloaderInit//')
    
    if [ -n "$OLD_HASH" ]; then
        # Generate a new hash by prefixing and rehashing
        NEW_HASH=$(echo "Prefixed${OLD_HASH}" | md5)
        
        echo "  Old hash: $OLD_HASH"
        echo "  New hash: $NEW_HASH"
        
        # Replace the old hash with the new hash in all autoloader files
        for file in vendor_prefixed/composer/autoload_real.php vendor_prefixed/autoload.php vendor_prefixed/composer/autoload_static.php; do
            if [ -f "$file" ]; then
                sed -i '' "s/${OLD_HASH}/${NEW_HASH}/g" "$file"
                echo "  Patched: $file"
            fi
        done
    else
        echo "  Warning: Could not extract hash from vendor_prefixed/autoload.php"
    fi
else
    echo "  Warning: vendor_prefixed/autoload.php not found"
fi

echo "# Prefixing file identifier hashes to prevent collisions..."

# Change file identifier hashes in autoload_static.php and autoload_real.php
if [ -f "vendor_prefixed/composer/autoload_static.php" ]; then
    # Use PHP to replace all 32-char hashes in the $files array
    php -r "
        \$file = 'vendor_prefixed/composer/autoload_static.php';
        \$contents = file_get_contents(\$file);
        \$prefix = 'OtomatiesCoreVendor';
        
        // Replace hashes in the \$files array
        \$contents = preg_replace_callback(
            \"/'([a-f0-9]{32})'\s*=>/\",
            function(\$matches) use (\$prefix) {
                \$hash = \$matches[1];
                \$newHash = md5(\$prefix . \$hash);
                return \"'\" . \$newHash . \"' =>\";
            },
            \$contents
        );
        
        file_put_contents(\$file, \$contents);
        echo \"  Patched: vendor_prefixed/composer/autoload_static.php\n\";
    "
fi

if [ -f "vendor_prefixed/composer/autoload_real.php" ]; then
    # Use PHP to replace all 32-char hashes in $GLOBALS['__composer_autoload_files']
    php -r "
        \$file = 'vendor_prefixed/composer/autoload_real.php';
        \$contents = file_get_contents(\$file);
        \$prefix = 'OtomatiesCoreVendor';
        
        // Replace hashes in \$GLOBALS['__composer_autoload_files']
        \$contents = preg_replace_callback(
            \"/\\\$GLOBALS\['__composer_autoload_files'\]\['([a-f0-9]{32})'\]/\",
            function(\$matches) use (\$prefix) {
                \$hash = \$matches[1];
                \$newHash = md5(\$prefix . \$hash);
                return \"\\\$GLOBALS['__composer_autoload_files']['\" . \$newHash . \"']\";
            },
            \$contents
        );
        
        file_put_contents(\$file, \$contents);
        echo \"  Patched: vendor_prefixed/composer/autoload_real.php\n\";
    "
fi

echo "# Removing scoped dependencies from composer.json..."

while IFS=: read -r package version; do
    echo "  Removing: $package"
    composer remove "$package" --quiet --no-update
done <<< "$SCOPED_DEPS"

composer update --quiet

echo "# Build complete!"
