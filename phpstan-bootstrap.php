<?php

define('WFWAF_AUTO_PREPEND', rand(0, 1) == 1);
define('WFWAF_SUBDIRECTORY_INSTALL', rand(0, 1) == 1);

class wfConfig
{
    /**
     * @phpstan-return mixed
     */
    public static function get(string $key)
    {
        return null;
    }
}

class wfActivityReport
{
    /**
     * Get full report
     *
     * @return array<string, mixed>
     */
    public function getFullReport() : array
    {
        return [];
    }
}

function WC() : stdClass
{
    return new stdClass();
}
