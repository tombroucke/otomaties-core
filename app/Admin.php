<?php //phpcs:ignore
namespace Otomaties\Core;

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
    public function init()
    {
        if ('development' != $this->wpEnv) {
            add_filter('acf/settings/show_admin', '__return_false');
        }
        remove_action('welcome_panel', 'wp_welcome_panel');
    }

    /**
     * Remove comments menu
     */
    public function removeMenus()
    {
        $menus = array(
            'edit-comments.php',
        );

        if (apply_filters('otomaties_open_comments', false)) {
            $key = array_search('edit-comments.php', $menus);
            if (false !== $key) {
                unset($menus[ $key ]);
            }
        }
        foreach (apply_filters('otomaties_admin_bar_unnecessary_menus', $menus) as $menu) {
            remove_menu_page($menu);
        }
    }

    /**
     * Remove wp-logo & comments from admin bar
     *
     * @param  object $wp_admin_bar admin bar object.
     */
    public function removeFromAdminBar($wp_admin_bar)
    {
        $nodes = array(
            'wp-logo',
            'comments',
        );
        if (apply_filters('otomaties_open_comments', false)) {
            unset($nodes['comments']);
        }
        foreach (apply_filters('otomaties_admin_bar_unnecessary_nodes', $nodes) as $node) {
            $wp_admin_bar->remove_node($node);
        }
    }

    /**
     * Add tb logo to admin bar
     *
     * @param  object $wp_admin_bar admin bar object.
     */
    public function adminBarLogo($wp_admin_bar)
    {
        if (! apply_filters('otomaties_whitelabel', false)) {
            ob_start();
            include(dirname(plugin_dir_path(__FILE__)) . '/assets/img/minilogo.svg');
            $minilogo = ob_get_clean();
            $args = array(
                'id'    => 'otomaties-core',
                'title' => $minilogo,
                'href'  => 'mailto:tom@tombroucke.be',
                'meta'  => array(
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
                ),
            );
            $wp_admin_bar->add_node($args);
        }
    }

    /**
     * Show discussion notice
     */
    public function discussionNotice()
    {
        global $pagenow;
        if ('options-discussion.php' == $pagenow) {
            ?>
            <div class="notice notice-warning">
                <p><?php esc_html_e('Some of these settings are controlled by the theme. To change these, please contact the theme author.', 'otomaties-core'); ?></p>
            </div>
            <?php
        }
    }

    /**
     * TB logo on login page
     */
    public function loginLogo()
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
     * @param  string $text default text.
     * @return string
     */
    public function adminFooterBranding($text)
    {
        if (apply_filters('otomaties_whitelabel', false)) {
            return $text;
        }

        $text = sprintf('<a target="_blank" href="%s">%s</a>', 'https://tombroucke.be', __('Website by', 'otomaties-core') . ' Tom Broucke');

        return $text;
    }
}
