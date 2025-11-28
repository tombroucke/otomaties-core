#!/bin/bash
echo "# Removing the vendor folder..."

rm -rf vendor
rm -f composer.lock

echo "# Running Composer..."

composer require illuminate/container ^12.40
composer require illuminate/support ^12.40
composer require illuminate/config ^12.40

composer update

echo "# Running Scoper..."
php-scoper add-prefix --force

composer update --no-dev --working-dir=build

echo "# Importing Build..."
rm -rf vendor_prefixed
mv build/vendor vendor_prefixed
rm -rf build

echo "# Build complete!"

jq 'del(.require["illuminate/support", "illuminate/container", "illuminate/config"])' composer.json > composer.tmp \
  && mv composer.tmp composer.json
