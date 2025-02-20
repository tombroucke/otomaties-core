<?php

namespace Otomaties\Core;

class Mollie
{
    /**
     * Add basic auth to webhook url
     *
     * @param  string  $returnUrl  The return url
     */
    public function webhookBasicAuth(string $returnUrl): string
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
