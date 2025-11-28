<?php

namespace Otomaties\Core\Modules;

class Mollie
{
    /**
     * Add actions and filters
     */
    public function init(): void
    {
        add_filter('mollie-payments-for-woocommerce_webhook_url', [$this, 'webhookBasicAuth'], 10, 2);
    }

    /**
     * Add basic auth to webhook url
     *
     * @param  string  $returnUrl  The return url
     * @param  \WC_Order  $order  The order
     */
    public function webhookBasicAuth(string $returnUrl, \WC_Order $order): string
    {
        if (! function_exists('env') || ! env('BASIC_AUTH_USER') || ! env('BASIC_AUTH_PASS')) {
            return $returnUrl;
        }

        $parsedUrl = parse_url($returnUrl);
        $protocol = ($parsedUrl['scheme'] ?? 'https') . '://';

        $basicAuthReturnUrl = preg_replace(
            '/^' . preg_quote($protocol, '/') . '/',
            $protocol . env('BASIC_AUTH_USER') . ':' . env('BASIC_AUTH_PASS') . '@',
            $returnUrl
        );

        return $basicAuthReturnUrl ?? $returnUrl;
    }
}
