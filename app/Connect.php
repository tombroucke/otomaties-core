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
     *
     * @param string $otomatiesCoreVersion
     * @param string $wpEnv
     */
    public function __construct(private string $otomatiesCoreVersion, private string $wpEnv = 'production')
    {
    }

    /**
     * Register the REST API routes
     *
     * @return void
     */
    public function registerRestRoutes() : void
    {
        register_rest_route(self::REST_API_ENDPOINT, '/info', [
            'methods'  => 'GET',
            'callback' => [$this, 'generalInfo'],
            'permission_callback' => [$this, 'checkPermission'],
        ]);
    }

    /**
     * Get general info about the site
     *
     * @return array<string, mixed>
     */
    public function generalInfo() : array
    {
        $builder = new ResponseBuilder();

        $debugLogFileLocation = constant('WP_CONTENT_DIR') . '/debug.log';
        $debugLogFileUrl = content_url() . '/debug.log';

        $builder
            ->env($this->wpEnv)
            ->bedrock(class_exists('\\Roots\\WPConfig\\Config'))
            ->version()
                ->php(phpversion())
                ->wordpress(get_bloginfo('version'))
                ->otomaties_core($this->otomatiesCoreVersion)
            ->endVersion()
            ->plugins()
                ->active(get_option('active_plugins'))
            ->endPlugins()
            ->reading()
                ->disable_indexing(!get_option('blog_public'))
            ->endReading()
            ->security()
                ->debug_log(!defined('WP_DEBUG') || constant('WP_DEBUG') === true)
                ->debug_log_file(file_exists($debugLogFileLocation) ? $debugLogFileUrl : false)
                ->disallow_file_edit(!defined('DISALLOW_FILE_EDIT') || constant('DISALLOW_FILE_EDIT') === false)
            ->endSecurity();

        if (is_plugin_active('wordfence/wordfence.php')) {
            $wfActivityReport = new \wfActivityReport();
            $wordfenceFirewallInactive =
                (!WFWAF_AUTO_PREPEND || WFWAF_SUBDIRECTORY_INSTALL)
                && empty($_GET['wafAction'])
                && !\wfConfig::get('dismissAutoPrependNotice')
                && !\wfConfig::get('touppPromptNeeded');
            
            $builder->security()
                ->wordfence()
                ->firewall_active(!$wordfenceFirewallInactive)
                ->report($wfActivityReport->getFullReport())
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
     * Check the public key signature to see if the request is valid
     *
     * @param \WP_REST_Request $request
     * @return boolean
     */
    public function checkPermission(\WP_REST_Request $request) : bool
    {
        $connectionKey = $this->findConnectionKey();
        
        if (!$connectionKey) {
            return false;
        }

        $signature = base64_decode($request->get_header('X-Otomaties-Signature') ?? '');

        $publicKeyLocation = plugin_dir_path(dirname(__FILE__)) . 'keys/otomaties_connect.pem';
        $publicKey = file_get_contents($publicKeyLocation);

        if (!$publicKey) {
            return false;
        }

        $publicKeyId = openssl_pkey_get_public($publicKey);

        if (!$publicKeyId) {
            return false;
        }

        if (openssl_verify($connectionKey, $signature, $publicKeyId, OPENSSL_ALGO_SHA256) !== 1) {
            return false;
        }
        
        return true;
    }

    /**
     * Find the connection key in WO config, server or env
     *
     * @return string|null
     */
    private function findConnectionKey() : ?string
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