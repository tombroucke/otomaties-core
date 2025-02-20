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
    public function getFullReport(): array
    {
        return [];
    }
}

class wfIssues
{
    /**
     * Returns the singleton wfIssues.
     *
     * @return wfIssues
     */
    public static function shared()
    {
        static $_issues = null;
        if ($_issues === null) {
            $_issues = new wfIssues;
        }

        return $_issues;
    }

    public function getIssues($offset = 0, $limit = 100, $ignoredOffset = 0, $ignoredLimit = 100)
    {
        return [];
    }
}

function WC(): stdClass
{
    return new stdClass;
}
