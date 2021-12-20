<?php //phpcs:ignore
namespace Otomaties\Core;

/**
 * Our main plugin class
 */
class Plugin {

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
	private static $_instance = null;

	/**
	 * Get plugin instance
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( ! isset( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Initialize plugin
	 */
	public function __construct() {

		$this->loader = new Loader();
		$this->set_locale();
		$this->define_hooks();

	}

	/**
	 * Set local
	 */
	private function set_locale() {

		$plugin_i18n = new i18n();
		$plugin_i18n->load_plugin_textdomain();

	}

	/**
	 * Define hooks
	 *
	 * @return void
	 */
	private function define_hooks() {

		$admin = new Admin();
		$admin->init();
		$this->loader->add_action( 'admin_menu', $admin, 'remove_menus' );
		$this->loader->add_action( 'admin_bar_menu', $admin, 'remove_from_admin_bar', 999 );
		$this->loader->add_action( 'admin_bar_menu', $admin, 'admin_bar_logo', 1 );
		$this->loader->add_action( 'admin_notices', $admin, 'discussion_notice' );
		$this->loader->add_action( 'login_head', $admin, 'login_logo', 100 );
		$this->loader->add_filter( 'admin_footer_text', $admin, 'admin_footer_branding', 1 );

		$security = new Security();
		$this->loader->add_action( 'admin_notices', $security, 'debug_notice' );
		$this->loader->add_filter( 'login_errors', $security, 'generic_login_errors' );
		$this->loader->add_filter( 'wp_get_attachment_url', $security, 'force_attachment_https' );

		$emojis = new Emojis();
		$emojis->init();
		$this->loader->add_filter( 'tiny_mce_plugins', $emojis, 'disable_emojis_tinymce' );
		$this->loader->add_filter( 'wp_resource_hints', $emojis, 'disable_emojis_remove_dns_prefetch', 10, 2 );

		$frontend = new Frontend();
		$frontend->init();
		$frontend->clean_up_head();
		$this->loader->add_action( 'template_redirect', $frontend, 'single_search_result' );
		$this->loader->add_action( 'after_setup_theme', $frontend, 'set_defaults', 999 );

		$discussion = new Discussion();
		$this->loader->add_filter( 'comments_open', $discussion, 'close_comments', 50, 2 );
		$this->loader->add_action( 'after_setup_theme', $discussion, 'set_defaults', 999 );

		$shortcodes = new Shortcodes();
		add_shortcode('email', [$shortcodes, 'obfuscateEmail']);
	}

	/**
	 * Run actions and filters
	 *
	 * @return void
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * Get loader
	 *
	 * @return Loader
	 */
	public function get_loader() {
		return $this->loader;
	}

}
