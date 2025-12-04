<?php

namespace Otomaties\Core\Modules;

use Otomaties\Core\View;
use OtomatiesCoreVendor\Illuminate\Support\Str;

class Security
{
    public function __construct(private View $view)
    {
        //
    }

    /**
     * Add actions and filters
     *
     * @return void
     */
    public function init()
    {
        add_action('admin_notices', [$this, 'debugNotice']);
        add_filter('login_errors', [$this, 'genericLoginErrors']);
        add_filter('wp_get_attachment_url', [$this, 'forceAttachmentHttps']);
        add_filter('pre_update_option', [$this, 'disableUpdateCriticalOptions'], 10, 3);
        add_action('admin_notices', [$this, 'showSecurityNotices']);
    }

    /**
     * Add notices for different security issues
     */
    public function debugNotice(): void
    {
        $securityIssues = [];
        if (! defined('WP_DEBUG') || constant('WP_DEBUG') === true) {
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
        if (! defined('DISALLOW_FILE_EDIT') || constant('DISALLOW_FILE_EDIT') === false) {
            array_push(
                $securityIssues,
                __('Disallow file editing for better security. Add <code>define( \'DISALLOW_FILE_EDIT\', true );</code> to wp-config.php', 'otomaties-core'), // phpcs:ignore Generic.Files.LineLength
            );
        }
        if (! is_plugin_active('sucuri-scanner/sucuri.php')
            && ! is_plugin_active('wordfence/wordfence.php')
        && ! is_plugin_active('wp-defender/wp-defender.php')
        && ! is_plugin_active('defender-security/wp-defender.php')
        ) {
            array_push(
                $securityIssues,
                __('Install & activate Wordfence, Sucuri Security or WP Defender for optimal security.', 'otomaties-core'), // phpcs:ignore Generic.Files.LineLength
            );
        }
        if (! empty($securityIssues)) {
            $type = 'warning';
            if (otomatiesCore()->environment() === 'production') {
                $type = 'error';
            }

            $message = '<h4>' . __('You have some security concerns', 'otomaties-core') . '</h4><ol>';
            foreach ($securityIssues as $issue) {
                $message .= '<li>' . $issue . '</li>';
            }
            $message .= '</ol>';

            $this->view
                ->render(
                    'admin/notice.php',
                    [
                        'type' => $type,
                        'message' => $message,
                    ]
                );
        }
    }

    /**
     * Replace login error with generic error
     *
     * @param  string  $errors
     */
    public function genericLoginErrors($errors): string
    {
        if (! apply_filters('otomaties_generic_login_error', true)) {
            return $errors;
        }

        if (mb_strpos($_SERVER['QUERY_STRING'] ?? '', 'action=lostpassword') !== false) {
            return __('Could not reset your password.', 'otomaties-core');
        }

        // translators: %s is the lost password url.
        return sprintf(
            __('Could not log you in. If this problem persists, <a href="%s">try resetting your password</a>', 'otomaties-core'), // phpcs:ignore Generic.Files.LineLength
            wp_lostpassword_url()
        );
    }

    /**
     * Force https on attachments if available
     *
     * @param  string  $url
     */
    public function forceAttachmentHttps($url): string
    {
        if (is_ssl()) {
            $url = Str::replaceStart('http://', 'https://', $url);
        }

        return $url;
    }

    /**
     * Disable update of critical options
     */
    public function disableUpdateCriticalOptions(mixed $value, string $option, mixed $oldValue = null): mixed
    {
        if (! apply_filters('otomaties_disable_update_critical_options', true)) {
            return $value;
        }

        return match ($option) {
            'users_can_register' => 0,
            'default_role' => 'subscriber',
            default => $value,
        };
    }

    /**
     * Show security notices
     */
    public function showSecurityNotices(): void
    {
        $currentScreen = get_current_screen();
        if (! $currentScreen
            || ! property_exists($currentScreen, 'base')
            || $currentScreen->base !== 'options-general') {
            return;
        }

        $this->view
            ->render(
                'admin/notice.php',
                [
                    'type' => 'notice',
                    'message' => __('Otomaties core has disabled updating of <code>users_can_register</code> & <code>default_role</code>.', 'otomaties-core'), // phpcs:ignore Generic.Files.LineLength
                ]
            );
    }
}
