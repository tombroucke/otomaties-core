<?php

namespace Otomaties\Core;

use Otomaties\Core\Connect\ResponseBuilder;

/**
 * Clean up admin section
 */
class Connect
{
    /**
     * The REST API endpoint
     */
    const REST_API_ENDPOINT = 'otomaties-connect/v1';

    /**
     * Set up the Connect class
     */
    public function __construct(private string $otomatiesCoreVersion, private string $wpEnv = 'production') {}

    /**
     * Register the REST API routes
     */
    public function registerRestRoutes(): void
    {
        register_rest_route(self::REST_API_ENDPOINT, '/info', [
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
        $builder = new ResponseBuilder;

        $debugLogFileLocation = constant('WP_CONTENT_DIR') . '/debug.log';
        $debugLogFileUrl = content_url() . '/debug.log';

        $builder
            ->env($this->wpEnv)
            ->bedrock(class_exists('\\Roots\\WPConfig\\Config'))
            ->administratorEmail(get_option('admin_email'))
            ->version()
            ->php(phpversion())
            ->wordpress(get_bloginfo('version'))
            ->otomatiesCore($this->otomatiesCoreVersion)
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
            $wfActivityReport = new \wfActivityReport;
            $wordfenceFirewallInactive =
                (! WFWAF_AUTO_PREPEND || WFWAF_SUBDIRECTORY_INSTALL)
                && empty($_GET['wafAction'])
                && ! \wfConfig::get('dismissAutoPrependNotice')
                && ! \wfConfig::get('touppPromptNeeded');

            $scanResults = \wfIssues::shared()->getIssues();
            $scanResults['last-scan-time'] = \wfConfig::get('scanTime');

            $builder->security()
                ->wordfence()
                ->firewallActive(! $wordfenceFirewallInactive)
                ->report($wfActivityReport->getFullReport())
                ->scanResults($scanResults)
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

        $publicKeyLocation = plugin_dir_path(dirname(__FILE__)) . 'keys/otomaties_connect.pem';
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
        if (defined('OTOMATIES_CONNECT_KEY')) {
            return constant('OTOMATIES_CONNECT_KEY');
        }
        if (isset($_SERVER['OTOMATIES_CONNECT_KEY'])) {
            return $_SERVER['OTOMATIES_CONNECT_KEY'];
        }
        if (isset($_ENV['OTOMATIES_CONNECT_KEY'])) {
            return $_ENV['OTOMATIES_CONNECT_KEY'];
        }

        return null;
    }
}
