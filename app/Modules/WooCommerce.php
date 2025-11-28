<?php

namespace Otomaties\Core\Modules;

use OtomatiesCoreVendor\Illuminate\Support\Str;

class WooCommerce
{
    /**
     * Add actions and filters
     */
    public function init(): void
    {
        add_filter('woocommerce_generate_order_key', [$this, 'rejectPatternsInOrderKey'], 10);
    }

    /**
     * Reject certain patterns in WooCommerce order key
     *
     * @param  string  $key  The order key
     */
    public function rejectPatternsInOrderKey($key = ''): string
    {
        if (Str::contains(strtolower($key), 'fck')) {
            return wc_generate_order_key();
        }

        return $key;
    }
}
