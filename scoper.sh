#!/bin/bash
set -e

get_scoped_deps() {
    jq -r '.extra["require-scoped"] // {} | to_entries | map("\(.key):\(.value)") | .[]' composer.json
}

set_autoloader_suffix() {
    TMP=$(mktemp)
    jq 'if .config then .config["autoloader-suffix"]="OtomatiesVendorCore" else . + {config: {"autoloader-suffix":"OtomatiesVendorCore"}} end' build/composer.json > "$TMP" && mv "$TMP" build/composer.json
}

echo "# Setting up..."

SCOPED_DEPS=$(get_scoped_deps)

rm -rf vendor_prefixed

echo "# Installing scoped dependencies..."

while IFS=: read -r package version; do
    echo "  Requiring: $package $version"
    composer require "$package" "$version" --quiet
done <<< "$SCOPED_DEPS"

echo "# Running Composer install to remove dev dependencies..."

composer install --no-dev --quiet

echo "# Running Scoper..."

php-scoper add-prefix --force

set_autoloader_suffix
composer dump-autoload --working-dir=build --optimize --no-dev --quiet

echo "# Importing Build..."

mv build/vendor vendor_prefixed

echo "# Removing scoped dependencies from composer.json..."

while IFS=: read -r package version; do
    echo "  Removing: $package"
    composer remove "$package" --quiet --no-update
done <<< "$SCOPED_DEPS"

echo "# Cleaning up..."

rm -rf build
rm vendor_prefixed/scoper-autoload.php

echo "# Build vendor directory..."
composer update --quiet

echo "# Build complete!"
