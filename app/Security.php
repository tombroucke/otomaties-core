<?php //phpcs:ignore
namespace Otomaties\Core;

class Security
{
    private $wpEnv = 'production';

    public function __construct(string $wpEnv)
    {
        $this->wpEnv = $wpEnv;
    }

    /**
     * Add notices for different security issues
     */
    public function debugNotice()
    {
        $securityIssues = array();
        if (!defined('WP_DEBUG') || constant('WP_DEBUG') === true) {
            array_push(
                $securityIssues,
                __('Disable debugging for better security. Add <code>define( \'WP_DEBUG\', false );</code> to wp-config.php', 'otomaties-core'), // phpcs:ignore Generic.Files.LineLength
            );
        }
        if (file_exists(constant('WP_CONTENT_DIR') . '/debug.log')) {
            array_push(
                $securityIssues,
                sprintf(__('Your debug.log file is publicly accessible. Remove <code>%s</code>', 'otomaties-core'), constant('WP_CONTENT_DIR') . '/debug.log'), // phpcs:ignore Generic.Files.LineLength
            );
        }
        if (!defined('DISALLOW_FILE_EDIT') || constant('DISALLOW_FILE_EDIT') === false) {
            array_push(
                $securityIssues,
                __('Disallow file editing for better security. Add <code>define( \'DISALLOW_FILE_EDIT\', true );</code> to wp-config.php', 'otomaties-core'), // phpcs:ignore Generic.Files.LineLength
            );
        }
        if (!is_plugin_active('sucuri-scanner/sucuri.php')
            && !is_plugin_active('wordfence/wordfence.php')
            && !is_plugin_active('wp-defender/wp-defender.php')
            && !is_plugin_active('defender-security/wp-defender.php')
        ) {
            array_push(
                $securityIssues,
                __('Install & activate Wordfence, Sucuri Security or WP Defender for optimal security.', 'otomaties-core'),
            );
        }
        if (!empty($securityIssues)) :
            $class = 'notice-warning';
            if ('production' == $this->wpEnv) {
                $class = 'notice-error';
            }
            ?>
            <div class="notice <?php echo esc_html($class); ?>">
                <h4><?php esc_html_e('You have some security concerns', 'otomaties-core'); ?></h4>
                <ol>
                    <?php foreach ($securityIssues as $issue) : ?>
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
    public function genericLoginErrors($errors)
    {
        if (apply_filters('otomaties_generic_login_error', true)) {
            // translators: %s is the lost password url.
            return sprintf(
                __('Could not log you in. If this problem persists, <a href="%s">try resetting your password</a>', 'otomaties-core'), // phpcs:ignore Generic.Files.LineLength
                wp_lostpassword_url()
            );
        }
        return $errors;
    }

    /**
     * Force https on attachments if available
     *
     * @param  string $url
     * @return string
     */
    public function forceAttachmentHttps($url)
    {
        if (is_ssl()) {
            $url = str_replace('http://', 'https://', $url);
        }
        return $url;
    }

    public function disableUpdateCriticalOptions($value, $option, $oldValue = null)
    {
        if (!apply_filters('otomaties_disable_update_critical_options', true)) {
            return $value;
        }
        if ('users_can_register' == $option) {
            return 0;
        }
        if ('default_role' == $option) {
            return 'subscriber';
        }
        return $value;
    }

    public function showSecurityNotices()
    {
        global $pagenow;
        if ('options-general.php' == $pagenow) {
            ?>
            <div class="notice">
                <p><?php _e('Otomaties core has disabled updating of <code>users_can_register</code> & <code>default_role</code>.', 'otomaties-core'); // phpcs:ignore Generic.Files.LineLength ?></p>
            </div>
            <?php
        }
    }
}
