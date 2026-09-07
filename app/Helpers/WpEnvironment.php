<?php

namespace Otomaties\Core\Helpers;

class WpEnvironment
{
    public static function defined(): bool
    {
        return defined('WP_ENV') && is_string(constant('WP_ENV'));
    }

    public static function get(): string
    {
        return self::defined() ? constant('WP_ENV') : 'production';
    }
}
