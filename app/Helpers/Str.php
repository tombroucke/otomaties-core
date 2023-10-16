<?php

namespace Otomaties\Core\Helpers;

/**
 * String helper
 */
class Str
{
    /**
     * Convert a string to snake case
     *
     * @param string $name
     * @return string
     */
    public static function snake(string $name) : string
    {
        $nameWithUnderscores = preg_replace('/(?<!^)[A-Z]/', '_$0', $name);
        if (!$nameWithUnderscores) {
            return $name;
        }
        $lowerCase = strtolower($nameWithUnderscores);
        return $lowerCase;
    }
}
