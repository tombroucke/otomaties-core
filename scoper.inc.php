<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;

// You can do your own things here, e.g. collecting symbols to expose dynamically
// or files to exclude.
// However beware that this file is executed by PHP-Scoper, hence if you are using
// the PHAR it will be loaded by the PHAR. So it is highly recommended to avoid
// to auto-load any code here: it can result in a conflict or even corrupt
// the PHP-Scoper analysis.

// Example of collecting files to include in the scoped build but to not scope
// leveraging the isolated finder.
$excludedFiles = array_map(
    static fn (SplFileInfo $fileInfo) => $fileInfo->getPathName(),
    iterator_to_array(
        Finder::create()->files()->in(__DIR__),
        false,
    ),
);

function getWpExcludedSymbols(string $fileName): array
{
    $filePath = __DIR__ . '/vendor/sniccowp/php-scoper-wordpress-excludes/generated/' . $fileName;

    return json_decode(
        file_get_contents($filePath),
        true,
    );
}

$wpConstants = getWpExcludedSymbols('exclude-wordpress-constants.json');
$wpClasses = getWpExcludedSymbols('exclude-wordpress-classes.json');
$wpFunctions = getWpExcludedSymbols('exclude-wordpress-functions.json');

return [
    // The prefix configuration. If a non-null value is used, a random prefix
    // will be generated instead.
    //
    // For more see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#prefix
    'prefix' => 'Otomaties\Core',

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
        /*
        Finder::create()->files()->in('src'),

        */
    ],

    // List of excluded files, i.e. files for which the content will be left untouched.
    // Paths are relative to the configuration file unless if they are already absolute
    //
    // For more see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#patchers
    'exclude-files' => [
        // 'src/an-excluded-file.php',
        // ...$excludedFiles,
    ],

    // When scoping PHP files, there will be scenarios where some of the code being scoped indirectly references the
    // original namespace. These will include, for example, strings or string manipulations. PHP-Scoper has limited
    // support for prefixing such strings. To circumvent that, you can define patchers to manipulate the file to your
    // heart contents.
    //
    // For more see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#patchers
    'patchers' => [
        static function (string $filePath, string $prefix, string $contents): string {
            // Fix Illuminate Container calling array_last() - prefix it to the scoped root namespace
            if (str_contains($filePath, 'illuminate/container/Container.php')) {
                $contents = preg_replace(
                    '/([^\\\\a-zA-Z_])array_last\(/',
                    '$1\\\\' . $prefix . '\\\\array_last(',
                    $contents
                );
            }

            return $contents;
        },
    ],

    // List of symbols to consider internal i.e. to leave untouched.
    //
    // For more information see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#excluded-symbols
    'exclude-namespaces' => [
        'Otomaties\Core\*',
        'PHPUnit\Framework\*',
        'PHPStan/*',
        'PHPStan/*',
        'PhpCsFixer/*',
        // '~^$~',
        // 'Acme\Foo'                     // The Acme\Foo namespace (and sub-namespaces)
        // '~^PHPUnit\\\\Framework$~',    // The whole namespace PHPUnit\Framework (but not sub-namespaces)
        // '~^$~',                        // The root namespace only
        // '',                            // Any namespace
    ],
    'exclude-constants' => [
        'WP_ENV',
        ...$wpConstants,
    ],
    'exclude-classes' => $wpClasses,
    'exclude-functions' => $wpFunctions,
];
