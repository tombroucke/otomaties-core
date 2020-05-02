<?php
namespace Otomaties\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Theme {
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'set_defaults' ), 999 );
	}

	/**
	 * Set defaults
	 */
	public function set_defaults() {
    	update_option( 'image_default_link_type', 'file' );

	    update_option( 'default_comment_status', '0' );
	    update_option( 'default_ping_status', '0' );
	    update_option( 'moderation_notify', '0' );
	    update_option( 'comments_notify', '0' );
	}
}
new Theme;