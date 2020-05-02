<?php
namespace Otomaties\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Admin {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'remove_menus' ) );
		add_action( 'admin_bar_menu', array( $this, 'remove_from_admin_bar' ), 999 );
		add_action( 'admin_bar_menu', array( $this, 'admin_bar_logo' ), 1 );
		add_action( 'admin_notices', array( $this, 'discussion_notice' ) );
		add_action( 'login_head', array( $this, 'login_logo' ), 100 );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_branding' ), 1 );

		if ( !defined('WP_ENV') || WP_ENV == 'production' || WP_ENV == 'staging' ) {
			add_filter('acf/settings/show_admin', '__return_false');
		}

		$this->customize_dashboard();
	}

	/**
	 * Remove comments menu
	 */
	public function remove_menus() {
		$menus = array(
			'edit-comments.php'
		);
		if( apply_filters( 'otomaties_open_comments', false ) ) {
			unset( $menus['comments'] );
		}
		foreach ( apply_filters( 'otomaties_admin_bar_unnecessary_menus', $menus ) as $menu) {
			remove_menu_page( $menu );
		}

	}

	/**
	 * Remove wp-logo & comments from admin bar
	 * @param  object $wp_admin_bar
	 */
	public function remove_from_admin_bar( $wp_admin_bar ) {
		$nodes = array(
			'wp-logo',
			'comments'
		);
		if( apply_filters( 'otomaties_open_comments', false ) ) {
			unset( $nodes['comments'] );
		}
		foreach ( apply_filters( 'otomaties_admin_bar_unnecessary_nodes', $nodes ) as $node) {
			$wp_admin_bar->remove_node( $node );
		}
	}

	/**
	 * Add tb logo to admin bar
	 * @param  object $wp_admin_bar
	 */
	public function admin_bar_logo( $wp_admin_bar ) {
		if( !apply_filters( 'otomaties_whitelabel', false ) ) {
			ob_start();
			include( plugin_dir_path( __FILE__ ) . '../assets/img/minilogo.svg' );
			$minilogo = ob_get_clean();
			$args = array(
				'id'    => 'otomaties',
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
							</style>'
				)
			);
			$wp_admin_bar->add_node( $args );
		}
	}

	/**
	 * Show discussion notice
	 */
	public function discussion_notice() {
		global $pagenow;
		if ( $pagenow == 'options-discussion.php' ) {
			echo '<div class="notice notice-warning">
			<p>' . __( 'Some of these settings are controlled by the theme. To change these, please contact the theme author.', 'otomaties' ) . '</p>
			</div>';
		}
	}

	/**
	 * TB logo on login page
	 */
	public function login_logo() {
		$logo = plugin_dir_url( __FILE__ ) . '../assets/img/logo.svg';
		if( !apply_filters( 'otomaties_whitelabel', false ) ) {
			?>
			<style type="text/css">
				.login h1 a {
					background-image: url(<?php echo esc_url( $logo ); ?>);
					background-size: contain;
					height: 67px;
					width: 320px;
				}
			</style>
			<?php
		}
	}

	/**
	 * Remove welcome panel from dashboard
	 */
	public function customize_dashboard() {
		remove_action( 'welcome_panel', 'wp_welcome_panel' );
	}

	/**
	 * Custom footer branding
	 * @param  string $text
	 * @return string
	 */
	public function admin_footer_branding( $text ) {
		if( !apply_filters( 'otomaties_whitelabel', false ) ) {
			$text = sprintf( '<a target="_blank" href="%s">%s</a>', 'https://tombroucke.be', __( 'Website by', 'otomaties') . ' Tom Broucke' );
		}
		return $text;
	}
}
new Admin;