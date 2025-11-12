<?php

namespace Otomaties\Core;

use Otomaties\Core\Helpers\View;

/**
 * Clean up admin section
 */
class Admin
{
    private $wpEnv = 'production';

    public function __construct(string $wpEnv)
    {
        $this->wpEnv = $wpEnv;
    }

    /**
     * Hide acf in production environments, hide welcome panel
     */
    public function init(): void
    {
        if ($this->wpEnv != 'development') {
            add_filter('acf/settings/show_admin', '__return_false');
        }
        remove_action('welcome_panel', 'wp_welcome_panel');
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
     * Add tb logo to admin bar
     *
     * @param  \WP_Admin_Bar  $wp_admin_bar  admin bar object.
     */
    public function adminBarLogo(\WP_Admin_Bar $wp_admin_bar): void
    {
        if (! apply_filters('otomaties_whitelabel', false)) {
            ob_start();
            include dirname(plugin_dir_path(__FILE__)) . '/assets/img/minilogo.svg';
            $minilogo = ob_get_clean();
            $args = [
                'id' => 'otomaties-core',
                'title' => (string) $minilogo,
                'href' => 'mailto:tom@tombroucke.be',
                'meta' => [
                    'class' => 'tb-logo',
                    'html' => '<style type="text/css">
								.tb-logo .ab-item {
									padding: 0 !important;
								}

								.tb-logo svg {
									padding: 6px !important;
									width: 20px !important;
									height: 19px !important;
									vertical-align: baseline !important;
								}

								.tb-logo path {
									fill: rgba(240, 245, 250, 0.6);
								}

								.tb-logo .ab-item:hover {
									background-color: #0073aa !important;
								}

								.tb-logo .ab-item:hover path {
									fill: #fff;
								}
							</style>',
                ],
            ];
            $wp_admin_bar->add_node($args);
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

        echo View::render(
            'admin/notice.php',
            [
                'type' => 'notice',
                'message' => __('Some of these settings are controlled by the theme. To change these, please contact the theme author.', 'otomaties-core'),
            ]
        );
    }

    /**
     * TB logo on login page
     */
    public function loginLogo(): void
    {
        $logo = dirname(plugin_dir_url(__FILE__)) . '/assets/img/logo.svg';
        if (! apply_filters('otomaties_whitelabel', false)) {
            ?>
            <style type="text/css">
                .login h1 a {
                    background-image: url(<?php echo esc_url($logo); ?>);
                    background-size: contain;
                    height: 67px;
                    width: 320px;
                }
            </style>
            <?php
        }
    }

    /**
     * Custom footer branding
     *
     * @param  string  $text  default text.
     */
    public function adminFooterBranding(string $text): string
    {
        if (apply_filters('otomaties_whitelabel', false)) {
            return $text;
        }

        $text = sprintf('<a target="_blank" href="%s">%s</a>', 'https://tombroucke.be', __('Website by', 'otomaties-core') . ' Tom Broucke'); // phpcs:ignore Generic.Files.LineLength

        return $text;
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

        echo View::render(
            'admin/notice.php',
            [
                'type' => 'warning',
                'message' => sprintf(__('Super page cache TTL is not set. Please configure it in the plugin settings to ensure optimal caching performance. Recommendation: %s hours.', 'otomaties-core'), 8),
            ]
        );
    }
}
