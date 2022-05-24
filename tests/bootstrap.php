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

function is_plugin_active(string $plugin) {
    return false;
}

function esc_html(string $string) {
    return $string;
}

function esc_html_e(string $string) {
    echo $string;
}

function wp_kses(string $string, array $allow) {
    return $string;
}

function apply_filters($filter, ...$vars) {
    return $vars[0];
}

function wp_lostpassword_url() {
    return 'https://example.com/login';
}

function is_ssl() {
    return true;
}
