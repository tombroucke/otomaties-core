<?php

namespace Otomaties\Core;

/**
 * Our main plugin class
 */
class Plugin
{
    /**
     * Action and filter loader
     *
     * @var Loader
     */
    protected $loader;

    /**
     * The plugin instance
     *
     * @var null|Plugin
     */
    private static $instance = null;

    /**
     * Get plugin instance
     *
     * @param  array<string, mixed>  $pluginData
     */
    public static function instance(array $pluginData): Plugin
    {
        if (! isset(self::$instance)) {
            self::$instance = new self($pluginData);
        }

        return self::$instance;
    }

    /**
     * Initialize plugin
     *
     * @param  array<string, mixed>  $pluginData
     */
    public function __construct(private array $pluginData)
    {
        $this->loader = new Loader;
        $this->setLocale();
        $this->defineHooks();
    }

    /**
     * Set local
     */
    private function setLocale(): void
    {
        $plugin_i18n = new I18n;
        $plugin_i18n->loadTextdomain();
    }

    /**
     * Define hooks
     */
    private function defineHooks(): void
    {
        $wpEnv = $this->getWpEnv();
        $admin = new Admin($wpEnv);
        $admin->init();
        $this->loader->addAction('admin_menu', $admin, 'removeMenus');
        $this->loader->addAction('admin_bar_menu', $admin, 'removeFromAdminBar', 999);
        $this->loader->addAction('admin_bar_menu', $admin, 'adminBarLogo', 1);
        $this->loader->addAction('admin_notices', $admin, 'discussionNotice');
        $this->loader->addAction('login_head', $admin, 'loginLogo', 100);
        $this->loader->addFilter('admin_footer_text', $admin, 'adminFooterBranding', 1);
        $this->loader->addAction('updated_option', $admin, 'setDefaults', 999);
        $this->loader->addAction('wpseo_metabox_prio', $admin, 'yoastSeoToBottom');
        $this->loader->addAction('admin_head', $admin, 'removeUpdateNag', 1);

        $security = new Security($wpEnv);
        $this->loader->addAction('admin_notices', $security, 'debugNotice');
        $this->loader->addFilter('login_errors', $security, 'genericLoginErrors');
        $this->loader->addFilter('wp_get_attachment_url', $security, 'forceAttachmentHttps');
        $this->loader->addFilter('pre_update_option', $security, 'disableUpdateCriticalOptions', 10, 3);
        $this->loader->addAction('admin_notices', $security, 'showSecurityNotices', 10, 3);

        $emojis = new Emojis;
        $emojis->init();
        $this->loader->addFilter('tiny_mce_plugins', $emojis, 'disableEmojisTinymce');
        $this->loader->addFilter('wp_resource_hints', $emojis, 'disableEmojisRemoveDnsPrefetch', 10, 2);

        $frontend = new Frontend;
        $frontend->init();
        $frontend->cleanUpHead();

        $this->loader->addAction('template_redirect', $frontend, 'redirectSingleSearchResult');

        $connect = new Connect($this->pluginData['Version'], $wpEnv);
        $this->loader->addAction('rest_api_init', $connect, 'registerRestRoutes');

        $discussion = new Discussion;
        $this->loader->addFilter('comments_open', $discussion, 'closeComments', 50, 2);
        $this->loader->addAction('updated_option', $discussion, 'setDefaults', 999);

        $revision = new Revision($wpEnv);
        $this->loader->addFilter('update_footer', $revision, 'showRevisionInAdminFooter', 999);
        $this->loader->addAction('wp_footer', $revision, 'showRevisionInConsole', 999);

        $gdpr = new Gdpr;
        $this->loader->addFilter('embed_oembed_html', $gdpr, 'replaceYoutubeWithYoutubeNoCookie', 10, 2);

        $woocommerce = new WooCommerce;
        $this->loader->addFilter('woocommerce_generate_order_key', $woocommerce, 'rejectPatternsInOrderKey', 10, 2);

        $mollie = new Mollie;
        $this->loader->addFilter('mollie-payments-for-woocommerce_webhook_url', $mollie, 'webhookBasicAuth', 10, 2);

        $shortcodes = new Shortcodes;
        add_shortcode('email', [$shortcodes, 'obfuscateEmail']);
        add_shortcode('tel', [$shortcodes, 'obfuscateTel']);
    }

    /**
     * Get WP_ENV, assume production
     */
    public function getWpEnv(): string
    {
        if (defined('WP_ENV') && is_string(constant('WP_ENV'))) {
            return constant('WP_ENV');
        }

        return 'production';
    }

    /**
     * Run actions and filters
     */
    public function run(): void
    {
        $this->loader->run();
    }

    /**
     * Get loader
     */
    public function getLoader(): Loader
    {
        return $this->loader;
    }
}
