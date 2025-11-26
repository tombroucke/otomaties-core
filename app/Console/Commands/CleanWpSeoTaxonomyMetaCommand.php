<?php

namespace Otomaties\Core\Console\Commands;

/**
 * Clean up admin section
 */
class CleanWpSeoTaxonomyMetaCommand implements Contracts\CommandContract
{
    public const COMMAND_NAME = 'otomaties-core cleanup-wp-seo-taxonomy-meta';

    public const COMMAND_DESCRIPTION = 'Cleans up the wp_option wpseo_taxonomy_meta entries that are no longer needed.';

    public const COMMAND_ARGUMENTS = [];

    public function __construct()
    {
        //
    }

    /**
     * Run below command to activate:
     *
     * wp vrd sync handle
     */
    public function handle(array $args, array $assocArgs): void
    {
        $taxonomies = get_option('wpseo_taxonomy_meta', []);

        foreach ($taxonomies as $taxonomy => $terms) {
            foreach ($terms as $termId => $meta) {
                if (! term_exists($termId, $taxonomy)) {
                    unset($taxonomies[$taxonomy][$termId]);
                    \WP_CLI::log("Removed wpseo_taxonomy_meta entry for term ID {$termId} in taxonomy {$taxonomy}.");
                }
            }
        }

        update_option('wpseo_taxonomy_meta', $taxonomies);

        \WP_CLI::success('Cleanup of wpseo_taxonomy_meta completed successfully.');
    }
}
