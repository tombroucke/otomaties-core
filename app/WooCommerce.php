<?php

namespace Otomaties\Core;

class WooCommerce
{
	public function rejectPatternsInOrderKey($key = '') {
		if (strpos(strtolower($key), 'fck') !== false) {
			return wc_generate_order_key();
		}
		return $key;
	}
}
