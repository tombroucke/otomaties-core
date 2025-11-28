<?php

/**
 * Plugin Name:     Otomaties Core
 * Description:     Optimize WordPress install
 * Author:          Tom Broucke
 * Author URI:      https://tombroucke.be
 * Text Domain:     otomaties-core
 * Domain Path:     /lang
 * Version:           1.10.1
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    exit;
}

require_once __DIR__ . '/vendor_prefixed/autoload.php';

/**
 * Get main plugin class instance
 *
 * @return \Otomaties\Core\Plugin
 */
function otomatiesCore()
{
    static $plugin;

    if (! $plugin) {
        $version = \get_plugin_data(__FILE__, false, false)['Version'];
        $environment = defined('WP_ENV') && is_string(constant('WP_ENV')) ? constant('WP_ENV') : null;

        $plugin = new \Otomaties\Core\Plugin(
            $version,
            $environment
        );
        do_action('plugin_boilerplate_functionality', $plugin);
    }

    return $plugin;
}

add_action('plugin_boilerplate_functionality', function ($plugin) {
    $plugin->initialize();
}, PHP_INT_MAX);

add_action('plugins_loaded', function () {
    otomatiesCore();
});
