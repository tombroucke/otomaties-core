<?php //phpcs:ignore
namespace Otomaties\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Frontend {

	public function init() {
		add_filter( 'widget_text', 'do_shortcode' );
	}

	public function set_defaults() {
    	update_option( 'image_default_link_type', 'file' );
	}

	public function clean_up_head() {

		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'rsd_link' );

		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_generator' );

		remove_action( 'wp_head', 'start_post_rel_link' );
		remove_action( 'wp_head', 'index_rel_link' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link' );
        remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
        remove_action( 'wp_head', 'rest_output_link_wp_head', 10);
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links');
        remove_action( 'wp_head', 'wp_oembed_add_host_js');

		add_filter('the_generator', '__return_false');

	}

	/**
	 * Redirect to result's single page when there is only 1 search result
	 */
	public function single_search_result() {
		if ( is_search() && apply_filters( 'otomaties_redirect_single_search_result', true ) ) {
			global $wp_query;
			if ( 1 == $wp_query->found_posts ) {
				wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
			}
		}
	}

}