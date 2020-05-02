<?php
/**
 * Plugin Name:     Otomaties Core
 * Description:     Optimize WordPress install
 * Author:          Tom Broucke
 * Author URI:      https://tombroucke.be
 * Text Domain:     otomaties
 * Domain Path:     /languages
 * Version:         1.1.0
 *
 * @package         Core
 */

namespace Otomaties\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Core {

	private static $instance = null;

	/**
	 * Creates or returns an instance of this class.
	 * @since  1.0.0
	 * @return Core A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct() {
		$this->includes();
		$this->init();
	}

	/**
	 * Include classes
	 */
	private function includes() {
		require 'vendor/autoload.php';
		include 'includes/class-admin.php';
		include 'includes/class-emojis.php';
		include 'includes/class-theme.php';
		include 'includes/class-frontend.php';
		include 'includes/class-performance.php';
		include 'includes/class-security.php';
	}

	/**
	 * Load textdomain, check for updates
	 */
	private function init() {
		$myUpdateChecker = \Puc_v4_Factory::buildUpdateChecker(
			'https://bitbucket.org/tombro/otomaties-core',
			__FILE__,
			'otomaties-core'
		);
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
	}

	/**
	 * Load textdomain otomaties
	 */
	public function load_plugin_textdomain() {
		load_muplugin_textdomain( 'otomaties', plugin_basename( dirname( __FILE__ ) ) . '/languages' ); 
	}
}

Core::get_instance();
