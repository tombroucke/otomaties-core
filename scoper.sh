#!/bin/bash
set -e

get_scoper_require() {
    jq -r '.extra.scoper.require // {} | to_entries | map("\(.key):\(.value)") | .[]' composer.json
}

get_scoper_autoload() {
    jq -r '.extra.scoper.autoload // {} | to_entries | map("\(.key):\(.value | tojson)") | .[]' composer.json
}

set_autoloader() {
    TMP=$(mktemp)
    jq '.autoload = '"$1" composer.json > "$TMP" && mv "$TMP" composer.json
}

remove_autoloader() {
    TMP=$(mktemp)
    jq 'del(.autoload)' composer.json > "$TMP" && mv "$TMP" composer.json
}

set_autoloader_suffix() {
    TMP=$(mktemp)
    jq 'if .config then .config["autoloader-suffix"]="OtomatiesVendorCore" else . + {config: {"autoloader-suffix":"OtomatiesVendorCore"}} end' build/composer.json > "$TMP" && mv "$TMP" build/composer.json
}

echo "# Setting up..."

SCOPED_DEPS=$(get_scoper_require)

rm -rf vendor_prefixed

echo "# Installing scoped dependencies..."

while IFS=: read -r package version; do
    echo "  Requiring: $package $version"
    composer require "$package" "$version" --quiet
done <<< "$SCOPED_DEPS"

SCOPED_AUTOLOAD=$(get_scoper_autoload)

if [ -n "$SCOPED_AUTOLOAD" ]; then
    AUTOLOAD_JSON="{"
    FIRST=true
    while IFS=: read -r key value; do
        if [ "$FIRST" = true ]; then
            FIRST=false
        else
            AUTOLOAD_JSON+=","
        fi
        AUTOLOAD_JSON+="\"$key\": $value"
    done <<< "$SCOPED_AUTOLOAD"
    AUTOLOAD_JSON+="}"
    echo "# Setting up autoloader..."
    set_autoloader "$AUTOLOAD_JSON"
fi

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

if [ -n "$SCOPED_AUTOLOAD" ]; then
    echo "# Removing autoloader..."
    remove_autoloader
fi

echo "# Cleaning up..."

rm -rf build
rm vendor_prefixed/scoper-autoload.php

echo "# Build vendor directory..."
composer update --quiet

echo "# Build complete!"
