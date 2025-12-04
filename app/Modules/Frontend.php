<?php

namespace Otomaties\Core\Modules;

class Frontend
{
    /**
     * Add actions and filters
     */
    public function init(): void
    {
        add_filter('widget_text', 'do_shortcode');
        add_action('template_redirect', [$this, 'redirectSingleSearchResult']);

        $this->cleanUpHead();
    }

    /**
     * Redirect to result's single page when there is only 1 search result
     */
    public function redirectSingleSearchResult(): void
    {
        if (! is_search() || ! apply_filters('otomaties_redirect_single_search_result', true)) {
            return;
        }

        global $wp_query;
        if ($wp_query->found_posts === 1) {
            $redirect = get_permalink($wp_query->posts[0]->ID);
            if ($redirect) {
                wp_safe_redirect($redirect);
                exit;
            }
        }
    }

    /**
     * Clean up wp_head
     */
    private function cleanUpHead(): void
    {
        remove_action('wp_head', 'feed_links_extra', 3);
        remove_action('wp_head', 'feed_links', 2);
        remove_action('wp_head', 'rsd_link');

        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_generator');

        remove_action('wp_head', 'start_post_rel_link');
        remove_action('wp_head', 'index_rel_link');
        remove_action('wp_head', 'adjacent_posts_rel_link');
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
        remove_action('wp_head', 'rest_output_link_wp_head', 10);
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');

        add_filter('the_generator', '__return_false');
    }
}
