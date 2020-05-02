<?php
namespace Otomaties\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Frontend {
	public function __construct() {
		$this->clean_up_head();

		add_filter( 'comments_open', array( $this, 'open_comments' ), 50 , 2 );
		add_action( 'template_redirect', array( $this, 'single_search_result' ) );
		add_filter( 'widget_text', 'do_shortcode' );

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
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7);
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
	}

	/**
	 * Close comments
	 */
	public function open_comments() {
		return apply_filters( 'otomaties_open_comments', false );
	}

	/**
	 * Redirect to result's single page when there is only 1 search result
	 */
	public function single_search_result() {
		if ( is_search( )) {
			global $wp_query;
			if ( $wp_query->post_count == 1 ) {
				wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
			}
		}
	}
}
new Frontend;