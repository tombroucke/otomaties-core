<?php //phpcs:ignore
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
     * @return Plugin
     */
    public static function instance()
    {
        if (! isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Initialize plugin
     */
    public function __construct()
    {
        $this->loader = new Loader();
        $this->setLocale();
        $this->defineHooks();
    }

    /**
     * Set local
     */
    private function setLocale()
    {
        $plugin_i18n = new I18n();
        $plugin_i18n->loadTextdomain();
    }

    /**
     * Define hooks
     *
     * @return void
     */
    private function defineHooks()
    {
        $admin = new Admin();
        $admin->init();
        $this->loader->addAction('admin_menu', $admin, 'removeMenus');
        $this->loader->addAction('admin_bar_menu', $admin, 'removeFromAdminBar', 999);
        $this->loader->addAction('admin_bar_menu', $admin, 'adminBarLogo', 1);
        $this->loader->addAction('admin_notices', $admin, 'discussionNotice');
        $this->loader->addAction('login_head', $admin, 'loginLogo', 100);
        $this->loader->addFilter('admin_footer_text', $admin, 'adminFooterBranding', 1);
        $this->loader->addFilter('update_footer', $admin, 'showRevision', 999);

        $security = new Security();
        $this->loader->addAction('admin_notices', $security, 'debugNotice');
        $this->loader->addFilter('login_errors', $security, 'genericLoginErrors');
        $this->loader->addFilter('wp_get_attachment_url', $security, 'forceAttachmentHttps');
        $this->loader->addFilter('pre_update_option', $security, 'disableUpdateCriticalOptions', 10, 3);
        $this->loader->addAction('admin_notices', $security, 'showSecurityNotices', 10, 3);

        $emojis = new Emojis();
        $emojis->init();
        $this->loader->addFilter('tiny_mce_plugins', $emojis, 'disableEmojisTinymce');
        $this->loader->addFilter('wp_resource_hints', $emojis, 'disableEmojisRemoveDnsPrefetch', 10, 2);

        $frontend = new Frontend();
        $frontend->init();
        $frontend->cleanUpHead();

        $this->loader->addAction('template_redirect', $frontend, 'redirectSingleSearchResult');
        $this->loader->addAction('after_setup_theme', $frontend, 'setDefaults', 999);

        $discussion = new Discussion();
        $this->loader->addFilter('comments_open', $discussion, 'closeComments', 50, 2);
        $this->loader->addAction('after_setup_theme', $discussion, 'setDefaults', 999);

        $shortcodes = new Shortcodes();
        add_shortcode('email', [$shortcodes, 'obfuscateEmail']);
    }

    /**
     * Run actions and filters
     *
     * @return void
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * Get loader
     *
     * @return Loader
     */
    public function getLoader()
    {
        return $this->loader;
    }
}
