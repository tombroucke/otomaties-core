<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;

// You can do your own things here, e.g. collecting symbols to expose dynamically
// or files to exclude.
// However beware that this file is executed by PHP-Scoper, hence if you are using
// the PHAR it will be loaded by the PHAR. So it is highly recommended to avoid
// to auto-load any code here: it can result in a conflict or even corrupt
// the PHP-Scoper analysis.

// Dynamically extract function names from Symfony polyfill bootstrap files
$polyFillFunctions = [];
$polyfillPaths = glob(__DIR__ . '/vendor/symfony/polyfill*/bootstrap*.php');

foreach ($polyfillPaths as $polyfillPath) {
    $content = file_get_contents($polyfillPath);
    // Match function definitions: function functionName(
    if (preg_match_all('/function\s+(\w+)\s*\(/', $content, $matches)) {
        $polyFillFunctions = array_merge($polyFillFunctions, $matches[1]);
    }
}
$polyFillFunctions = array_unique($polyFillFunctions);

if (count($polyFillFunctions) === 0) {
    throw new RuntimeException('Failed to extract polyfill function names.');
}

return [
    // The prefix configuration. If a non-null value is used, a random prefix
    // will be generated instead.
    //
    // For more see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#prefix
    'prefix' => 'OtomatiesCoreVendor',

    // By default when running php-scoper add-prefix, it will prefix all relevant code found in the current working
    // directory. You can however define which files should be scoped by defining a collection of Finders in the
    // following configuration key.
    //
    // This configuration entry is completely ignored when using Box.
    //
    // For more see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#finders-and-paths
    'finders' => [
        Finder::create()
            ->files()
            ->ignoreVCS(true)
            ->notName('/LICENSE|.*\\.md|.*\\.dist|Makefile|composer\\.json|composer\\.lock/')
            ->exclude([
                'doc',
                'test',
                'test_old',
                'tests',
                'Tests',
                'vendor-bin',
            ])
            ->in('vendor'),
        Finder::create()->append([
            'composer.json',
        ]),
    ],

    // When scoping PHP files, there will be scenarios where some of the code being scoped indirectly references the
    // original namespace. These will include, for example, strings or string manipulations. PHP-Scoper has limited
    // support for prefixing such strings. To circumvent that, you can define patchers to manipulate the file to your
    // heart contents.
    //
    // For more see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#patchers
    'patchers' => [
        static function (string $filePath, string $prefix, string $contents) use ($polyFillFunctions): string {

            // Check if any part of the file path matches an entry in $filePaths
            if (! str_contains($filePath, 'vendor/symfony/polyfill')) {
                // Patch all functions in the list
                foreach ($polyFillFunctions as $function) {
                    $contents = preg_replace(
                        '/([^\\\\a-zA-Z_])' . preg_quote($function, '/') . '\(/',
                        '$1\\\\' . $prefix . '\\\\' . $function . '(',
                        $contents
                    );
                }
            }

            return $contents;
        },
    ],

    'exclude-namespaces' => ['Otomaties\\Core'],
];
