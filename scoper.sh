#!/bin/bash
echo "# Removing the vendor folder..."

rm -r -f vendor
rm composer.lock

echo "# Running Composer..."

composer update

echo "# Running Scoper..."

vendor/bin/php-scoper add-prefix --force

echo "# Importing Build..."

rm -r -f vendor
mv build/vendor vendor
rm -r -f build
composer update --no-dev
