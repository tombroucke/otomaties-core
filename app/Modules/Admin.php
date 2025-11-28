<?php

namespace Otomaties\Core\Modules;

use Otomaties\Core\View;

/**
 * Clean up admin section
 */
class Admin
{
    public function __construct(private View $view)
    {
        //
    }

    /**
     * Add actions and filters
     */
    public function init(): void
    {
        if (otomatiesCore()->environment() != 'development') {
            add_filter('acf/settings/show_admin', '__return_false');
        }

        if (! apply_filters('otomaties_whitelabel', false)) {
            otomatiesCore()->make(Admin\Branding::class)->init();
        }

        remove_action('welcome_panel', 'wp_welcome_panel');

        add_action('admin_menu', [$this, 'removeMenus']);
        add_action('admin_bar_menu', [$this, 'removeFromAdminBar'], 999);
        add_action('admin_notices', [$this, 'discussionNotice']);
        add_action('updated_option', [$this, 'setDefaults'], 999);
        add_filter('wpseo_metabox_prio', [$this, 'yoastSeoToBottom']);
        add_action('admin_head', [$this, 'removeUpdateNag'], 1);
        add_action('admin_notices', [$this, 'cacheTtlNotice']);
    }

    /**
     * Set default image link type
     */
    public function setDefaults(): void
    {
        $options = [
            'image_default_link_type' => 'file',
        ];

        foreach ($options as $key => $value) {
            if (apply_filters('otomaties_set_default_' . $key, true)) {
                update_option($key, $value);
            }
        }
    }

    /**
     * Remove comments menu
     */
    public function removeMenus(): void
    {
        $menus = [
            'edit-comments.php',
        ];

        if (apply_filters('otomaties_open_comments', false)) {
            $key = array_search('edit-comments.php', $menus);
            if ($key !== false) {
                unset($menus[$key]);
            }
        }
        foreach (apply_filters('otomaties_admin_bar_unnecessary_menus', $menus) as $menu) {
            remove_menu_page($menu);
        }
    }

    /**
     * Remove wp-logo & comments from admin bar
     *
     * @param  \WP_Admin_Bar  $wp_admin_bar  admin bar object.
     */
    public function removeFromAdminBar(\WP_Admin_Bar $wp_admin_bar): void
    {
        $nodes = [
            'wp-logo',
            'comments',
        ];

        if (apply_filters('otomaties_open_comments', false) && ($key = array_search('comment', $nodes)) !== false) {
            unset($nodes[$key]);
        }

        foreach (apply_filters('otomaties_admin_bar_unnecessary_nodes', $nodes) as $node) {
            $wp_admin_bar->remove_node($node);
        }
    }

    /**
     * Show discussion notice
     */
    public function discussionNotice(): void
    {
        global $pagenow;

        if ($pagenow !== 'options-discussion.php') {
            return;
        }

        $this->view
            ->render('admin/notice', [
                'type' => 'notice',
                'message' => __('Some of these settings are controlled by Otomaties core. To update these settings, please contact the website administrator.', 'otomaties-core'), // phpcs:ignore Generic.Files.LineLength
            ]);
    }

    /**
     * Move Yoast to bottom
     */
    public function yoastSeoToBottom(string $priority): string
    {
        if (! apply_filters('otomaties_yoast_seo_to_bottom', true)) {
            return $priority;
        }

        return 'low';
    }

    /**
     * Remove update nag
     */
    public function removeUpdateNag(): void
    {
        if (! current_user_can('administrator')) {
            remove_action('admin_notices', 'update_nag', 3);
        }
    }

    public function cacheTtlNotice(): void
    {
        global $sw_cloudflare_pagecache;
        if (! $sw_cloudflare_pagecache || ! method_exists($sw_cloudflare_pagecache, 'get_single_config')) {
            return;
        }

        $ttl = $sw_cloudflare_pagecache->get_single_config('cf_fallback_cache_ttl', 0);

        if ($ttl !== 0) {
            return;
        }

        $this->view
            ->render('admin/notice', [
                'type' => 'warning',
                'message' => sprintf(__('Super page cache TTL is not set. Please configure it in the plugin settings to ensure optimal caching performance. Recommendation: %s seconds.', 'otomaties-core'), 28800), // phpcs:ignore Generic.Files.LineLength
            ]);
    }
}
