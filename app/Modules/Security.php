<?php

namespace Otomaties\Core\Modules;

use Otomaties\Core\View;
use OtomatiesCoreVendor\Illuminate\Support\Str;

class Security
{
    public function __construct(private string $env, private View $view)
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
        add_filter('rest_pre_insert_user', [$this, 'disableInsertAdminUser'], 10, 2);
        add_filter('rest_pre_dispatch', [$this, 'requireAuthForRestBatch'], 10, 3);
        add_filter('rest_endpoints', [$this, 'disableUserEndpoints']);

        add_filter('wp_pre_insert_user_data', [$this, 'disableAdministratorPromotion'], 10, 4);
        add_filter('add_user_metadata', [$this, 'preventCapabilityEscalation'], 10, 4);
        add_filter('update_user_metadata', [$this, 'preventCapabilityEscalation'], 10, 4);
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
            if ($this->env === 'production') {
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

    public function disableInsertAdminUser(mixed $preparedUser, \WP_REST_Request $request): mixed
    {
        if (! apply_filters('otomaties_disable_insert_admin_user', true)) {
            return $preparedUser;
        }

        $roles = $request->get_param('roles') ?? [];
        if (in_array('administrator', (array) $roles, true)) {
            return new \WP_Error(
                'rest_cannot_assign_administrator',
                'Assigning the administrator role via the REST API is not allowed.',
                ['status' => 403]
            );
        }

        return $preparedUser;
    }

    public function requireAuthForRestBatch(mixed $result, \WP_REST_Server $server, \WP_REST_Request $request): mixed
    {
        if (! apply_filters('otomaties_require_auth_for_batch', true)) {
            return $result;
        }

        if ($request->get_route() === '/batch/v1' && ! current_user_can('edit_posts')) {
            return new \WP_Error(
                'rest_forbidden',
                'Batch endpoint requires authentication.',
                ['status' => 401]
            );
        }

        return $result;
    }

    /**
     * @param  array<string, mixed>  $endpoints
     * @return array<string, mixed>
     */
    public function disableUserEndpoints(array $endpoints): array
    {
        if (! apply_filters('otomaties_disable_user_endpoints', true)) {
            return $endpoints;
        }

        if (! current_user_can('edit_posts')) {
            unset($endpoints['/wp/v2/users']);
            unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
        }

        return $endpoints;
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $userdata
     * @return array<string, mixed>|bool|\WP_Error
     */
    public function disableAdministratorPromotion(array $data, bool $update, ?int $userId, array $userdata): array|bool|\WP_Error // phpcs:ignore Generic.Files.LineLength
    {
        if (! apply_filters('otomaties_disable_administrator_promotion', true)) {
            return $data;
        }

        if (isset($userdata['role']) && $userdata['role'] === 'administrator') {
            $this->reportIncident('Attempt to promote user to administrator role.', [
                'data' => $data,
                'user_id' => $userId,
            ]);

            // This error will not be displayed, "Not enough data to create this user." is displayed instead.
            return new \WP_Error(
                'admin_promotion_not_allowed',
                __('Promoting a user to administrator is not allowed.', 'otomaties-core'),
            );
        }

        return $data;
    }

    public function preventCapabilityEscalation(null|int|false $check, int $userId, string $metaKey, mixed $metaValue): null|int|false // phpcs:ignore Generic.Files.LineLength
    {
        if (! apply_filters('otomaties_disable_administrator_promotion', true)) {
            return $check;
        }

        global $wpdb;
        if ($metaKey !== $wpdb->get_blog_prefix() . 'capabilities') {
            return $check;
        }

        if (is_array($metaValue) && ! empty($metaValue['administrator'])) {
            $this->reportIncident('Attempt to escalate user capabilities to administrator.', [
                'user_id' => $userId,
                'meta_key' => $metaKey,
                'meta_value' => $metaValue,
            ]);

            return false;
        }

        return $check;
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function reportIncident(string $message, array $context = []): void
    {
        otomatiesCore()->make(Connect::class)->reportIncident($message, $context);
    }
}
