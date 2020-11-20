<?php //phpcs:ignore
namespace Otomaties\Core;

class Security {

	/**
	 * Add notices for different security issues
	 */
	public function debug_notice() {

		$security_issues = array();
		if ( ! defined( 'WP_DEBUG' ) || WP_DEBUG === true ) {
			array_push( $security_issues, __( 'Disable debugging for better security. Add <code>define( \'WP_DEBUG\', false );</code> to wp-config.php', 'otomaties-core' ) );
		}
		if ( file_exists( WP_CONTENT_DIR . '/debug.log' ) ) {
			array_push( $security_issues, __( 'Your debug.log file is publicly accessible. Remove <code>wp-content/debug.log</code>', 'otomaties-core' ) );
		}
		if ( ! defined( 'DISALLOW_FILE_EDIT' ) || DISALLOW_FILE_EDIT === false ) {
			array_push( $security_issues, __( 'Disallow file editing for better security. Add <code>define( \'DISALLOW_FILE_EDIT\', true );</code> to wp-config.php', 'otomaties-core' ) );
		}
		if ( ! is_plugin_active( 'sucuri-scanner/sucuri.php' ) && ! is_plugin_active( 'wordfence/wordfence.php' ) ) {
			array_push( $security_issues, __( 'Install & activate Wordfence or Sucuri Security for optimal security.', 'otomaties-core' ) );
		}
		if ( ! empty( $security_issues ) ) :
			$class = 'notice-warning';
			if ( ! defined( 'WP_ENV' ) || WP_ENV == 'production' ) {
				$class = 'notice-error';
			}
			?>
			<div class="notice <?php echo esc_html( $class ); ?>">
				<h4><?php esc_html_e( 'You have some security concerns', 'otomaties-core' ); ?></h4>
				<ol>
					<?php foreach ( $security_issues as $issue ) : ?>
						<li>
						<?php
						echo wp_kses(
							$issue,
							array(
								'a' => array(),
								'code' => array(),
							)
						);
						?>
							</li>
					<?php endforeach; ?>
				</ol>
			</div>
			<?php
		endif;

	}

	/**
	 * Replace login error with generic error
	 *
	 * @param  string $errors
	 * @return string
	 */
	public function generic_login_errors( $errors ) {

		if ( apply_filters( 'otomaties_generic_login_error', true ) ) {
			// translators: %s is the lost password url.
			return sprintf( __( 'Could not log you in. If this problem persists, <a href="%s">try resetting your password</a>', 'otomaties-core' ), wp_lostpassword_url() );
		}
		return $errors;

	}

	/**
	 * Force https on attachments if available
	 *
	 * @param  string $url
	 * @return string
	 */
	public function force_attachment_https( $url ) {
		
		if ( is_ssl() ) {
			$url = str_replace( 'http://', 'https://', $url );
		}
		return $url;

	}
}
