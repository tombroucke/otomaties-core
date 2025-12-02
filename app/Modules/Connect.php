<?php

namespace Otomaties\Core\Modules;

use Otomaties\Core\Modules\Connect\ResponseBuilder;

/**
 * Clean up admin section
 */
class Connect
{
    /**
     * Add actions and filters
     */
    public function init(): void
    {
        add_action('rest_api_init', [$this, 'registerRestRoutes']);
        add_action('wp_mail_failed', [$this, 'notifyMailFailure']);
    }

    /**
     * Register the REST API routes
     */
    public function registerRestRoutes(): void
    {
        register_rest_route('otomaties-connect/v1', '/info', [
            'methods' => 'GET',
            'callback' => [$this, 'generalInfo'],
            'permission_callback' => [$this, 'checkPermission'],
        ]);
    }

    /**
     * Get general info about the site
     *
     * @return array<string, mixed>
     */
    public function generalInfo(): array
    {
        $previousErrorReporting = error_reporting(0);

        $builder = new ResponseBuilder;

        $debugLogFileLocation = constant('WP_CONTENT_DIR') . '/debug.log';
        $debugLogFileUrl = content_url() . '/debug.log';

        $builder
            ->env(otomatiesCore()->environment())
            ->bedrock(class_exists('\\Roots\\WPConfig\\Config'))
            ->administratorEmail(get_option('admin_email'))
            ->version()
            ->php(phpversion())
            ->wordpress(get_bloginfo('version'))
            ->otomatiesCore(otomatiesCore()->version())
            ->endVersion()
            ->plugins()
            ->active(get_option('active_plugins'))
            ->endPlugins()
            ->reading()
            ->disableIndexing(! get_option('blog_public'))
            ->endReading()
            ->security()
            ->debugLog(! defined('WP_DEBUG') || constant('WP_DEBUG') === true)
            ->debugLogFile(file_exists($debugLogFileLocation) ? $debugLogFileUrl : false)
            ->disallowFileEdit(defined('DISALLOW_FILE_EDIT') && constant('DISALLOW_FILE_EDIT') === true)
            ->sqlTriggers($this->sqlTriggers())
            ->endSecurity();

        if (is_plugin_active('wordfence/wordfence.php')) {
            $firewall = new \wfFirewall;

            $scanResults = \wfIssues::shared()->getIssues();
            $scanResults['last-scan-time'] = \wfConfig::get('scanTime');

            $builder->security()
                ->wordfence()
                ->firewallActive(
                    $firewall->protectionMode() == \wfFirewall::PROTECTION_MODE_EXTENDED && ! $firewall->isSubDirectoryInstallation() // phpcs:ignore Generic.Files.LineLength
                )
                ->endWordfence()
                ->endSecurity();
        }

        if (class_exists('woocommerce')) {
            $builder->version()
                ->woocommerce(WC()->version)
                ->endVersion();

            if (is_plugin_active('mollie-payments-for-woocommerce/mollie-payments-for-woocommerce.php')) {
                $builder->woocommerce()
                    ->mollie()
                    ->testmode(get_option('mollie-payments-for-woocommerce_test_mode_enabled') === 'yes')
                    ->endMollie()
                    ->endWoocommerce();
            }
        }

        error_reporting($previousErrorReporting);

        return $builder->build();
    }

    /**
     * Get all SQL triggers
     *
     * @return array<int, mixed>
     */
    private function sqlTriggers(): array
    {
        global $wpdb;

        return $wpdb->get_results('SHOW TRIGGERS');
    }

    /**
     * Check the public key signature to see if the request is valid
     */
    public function checkPermission(\WP_REST_Request $request): bool
    {
        $connectionKey = $this->findConnectionKey();

        if (! $connectionKey) {
            return false;
        }

        $signature = base64_decode($request->get_header('X-Otomaties-Signature') ?? '');

        $publicKeyLocation = otomatiesCore()->config('paths.base') . '/keys/otomaties_connect.pem';
        $publicKey = file_get_contents($publicKeyLocation);

        if (! $publicKey) {
            return false;
        }

        $publicKeyId = openssl_pkey_get_public($publicKey);

        if (! $publicKeyId) {
            return false;
        }

        if (openssl_verify($connectionKey, $signature, $publicKeyId, OPENSSL_ALGO_SHA256) !== 1) {
            return false;
        }

        return true;
    }

    /**
     * Find the connection key in WO config, server or env
     */
    private function findConnectionKey(): ?string
    {
        return otomatiesCore()->findVariable('OTOMATIES_CONNECT_KEY');
    }

    public function notifyMailFailure(\WP_Error $wpError): void
    {
        try {
            $connectionKey = $this->findConnectionKey();
            if (! $connectionKey) {
                return;
            }

            $endpoint = 'https://connect.otomaties.be/api/v1/wp/mail-failed';
            $body = [
                'site_url' => get_site_url(),
                'error' => $wpError->get_error_message(),
                'email' => $wpError->get_error_data(),
            ];

            $headers = [
                'Content-Type' => 'application/json',
                'X-Otomaties-Connection-Key' => $connectionKey,
            ];

            wp_remote_post($endpoint, [
                'headers' => $headers,
                'body' => wp_json_encode($body),
                'timeout' => 5,
                'sslverify' => otomatiesCore()->environment() === 'production',
            ]);
        } catch (\Exception $e) {
            // Silent fail
        }
    }
}
