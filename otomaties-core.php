<?php

/**
 * Plugin Name:     Otomaties Core
 * Description:     Optimize WordPress install
 * Author:          Tom Broucke
 * Author URI:      https://tombroucke.be
 * Text Domain:     otomaties-core
 * Domain Path:     /lang
 * Version:           1.10.0
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    exit;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once realpath(__DIR__ . '/vendor/autoload.php');
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
        if (! function_exists('get_plugin_data')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $pluginData = \get_plugin_data(__FILE__, false, false);
        $pluginData['pluginName'] = basename(__FILE__, '.php');

        $plugin = Otomaties\Core\Plugin::instance($pluginData);
        $plugin->run();
    }
);
