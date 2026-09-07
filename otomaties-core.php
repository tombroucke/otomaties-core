<?php

use Otomaties\Core\Helpers\WpEnvironment;
use Otomaties\Core\Plugin;

/**
 * Plugin Name:     Otomaties Core
 * Plugin URI:      https://github.com/tombroucke/otomaties-core
 * Description:     Optimize WordPress install
 * Author:          Tom Broucke
 * Author URI:      https://tombroucke.be
 * Text Domain:     otomaties-core
 * Domain Path:     resources/languages
 * Requires PHP:    8.0
 * Version:           2.3.0
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
 * @return Plugin
 */
function otomatiesCore()
{
    static $plugin;

    if (! $plugin) {
        $version = get_plugin_data(__FILE__, false, false)['Version'];
        $environment = WpEnvironment::get();

        $plugin = new Plugin(
            $version,
            $environment
        );

        do_action('otomaties_core', $plugin);
    }

    return $plugin;
}

add_action('otomaties_core', function ($plugin) {
    $plugin->initialize();
}, PHP_INT_MAX);

add_action('plugins_loaded', function () {
    otomatiesCore();
});
