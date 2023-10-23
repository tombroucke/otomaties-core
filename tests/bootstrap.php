<?php

define('WP_CONTENT_DIR', '.');

function __(string $string, string $textdomain)
{
    return $string;
}
function _e(string $string, string $textdomain)
{
    echo $string;
}

function is_plugin_active(string $plugin)
{
    return false;
}

function esc_html(string $string)
{
    return $string;
}

function esc_html_e(string $string)
{
    echo $string;
}

function wp_kses(string $string, array $allow)
{
    return $string;
}

function apply_filters($filter, ...$vars)
{
    return $vars[0];
}

function wp_lostpassword_url()
{
    return 'https://example.com/login';
}

function is_ssl()
{
    return true;
}

$currentScreen = (object) [
    'id' => 'options-general',
    'base' => 'options-general',
];
function get_current_screen()
{
    global $currentScreen;
    return $currentScreen;
}

function custom_change_current_screen(object $screen)
{
    global $currentScreen;
    $currentScreen = $screen;
}

function content_url()
{
    return 'https://example.com/wp-content';
}

function get_bloginfo(string $info)
{
    $bloginfo = [
        'version' => '5.8',
    ];
    return $bloginfo[$info];
}

function get_option(string $option)
{
    $options = [
        'active_plugins' => [
            'advanced-custom-fields-pro/acf.php',
            'woocommerce/woocommerce.php',
            'wordfence/wordfence.php'
        ],
        'blog_public' => true,
        'admin_email' => 'tom@tombroucke.be',
    ];
    return $options[$option];
}

global $wpdb;

class Wpdb {
    public function get_results() {
        return [];
    }
}

$wpdb = new Wpdb();
