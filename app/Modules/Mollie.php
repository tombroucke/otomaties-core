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
        $basicAuthUser = otomatiesCore()->findVariable('BASIC_AUTH_USER');
        $basicAuthPass = otomatiesCore()->findVariable('BASIC_AUTH_PASS');

        if (! $basicAuthUser || ! $basicAuthPass) {
            return $returnUrl;
        }

        $parsedUrl = parse_url($returnUrl);
        $protocol = ($parsedUrl['scheme'] ?? 'https') . '://';

        $basicAuthReturnUrl = preg_replace(
            '/^' . preg_quote($protocol, '/') . '/',
            $protocol . $basicAuthUser . ':' . $basicAuthPass . '@',
            $returnUrl
        );

        return $basicAuthReturnUrl ?? $returnUrl;
    }
}
