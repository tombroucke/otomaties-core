#!/bin/bash
echo "# Removing the vendor folder..."

rm -r -f vendor
rm composer.lock

echo "# Running Composer..."

composer update

composer require illuminate/container ^12.40  --no-scripts --no-plugins
composer require illuminate/support ^12.40  --no-scripts --no-plugins
composer require illuminate/config ^12.40  --no-scripts --no-plugins

echo "# Running Scoper..."

vendor/bin/php-scoper add-prefix --force

echo "# Importing Build..."

rm -r -f vendor
mv build/vendor vendor
rm -r -f build
composer update --no-dev

jq 'del(.require["illuminate/support", "illuminate/container", "illuminate/config"])' composer.json > composer.tmp \
  && mv composer.tmp composer.json
