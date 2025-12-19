<?php

/**
 * Plugin Name:     Otomaties Core
 * Plugin URI:      https://github.com/tombroucke/otomaties-core
 * Description:     Optimize WordPress install
 * Author:          Tom Broucke
 * Author URI:      https://tombroucke.be
 * Text Domain:     otomaties-core
 * Domain Path:     resources/languages
 * Requires PHP:    8.0
 * Version:           2.1.0
 */

// If this file is called directly, abort.
if (! defined('ABSPATH')) {
    exit;
}

// Load the Composer autoloader
$prefixedAutoloadPath = __DIR__ . '/vendor_prefixed/autoload.php';
if (file_exists($prefixedAutoloadPath)) {
    require_once $prefixedAutoloadPath;
}

/**
 * Get main plugin class instance
 *
 * @return \Otomaties\Core\Plugin
 */
function otomatiesCore()
{
    static $plugin;

    if (! $plugin) {
        $version = get_plugin_data(__FILE__, false, false)['Version'];
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
