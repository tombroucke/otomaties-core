<?php
/**
 * Plugin Name:     Otomaties Core
 * Description:     Optimize WordPress install
 * Author:          Tom Broucke
 * Author URI:      https://tombroucke.be
 * Text Domain:     otomaties-core
 * Domain Path:     /lang
 * Version:           1.2.0
 *
 * @package         Core
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require 'vendor/autoload.php';
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
add_action(
    'plugins_loaded',
    function () {
        $plugin = Otomaties\Core\Plugin::instance();
        $plugin->run();
    }
);
