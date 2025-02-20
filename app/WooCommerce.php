<?php

namespace Otomaties\Core;

class WooCommerce
{
    /**
     * Reject certain patterns in WooCommerce order key
     *
     * @param  string  $key  The order key
     */
    public function rejectPatternsInOrderKey($key = ''): string
    {
        if (strpos(strtolower($key), 'fck') !== false) {
            return wc_generate_order_key();
        }

        return $key;
    }
}
