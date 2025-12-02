<?php

define('WFWAF_AUTO_PREPEND', rand(0, 1) === 1);
define('WFWAF_SUBDIRECTORY_INSTALL', rand(0, 1) === 1);

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

class wfFirewall
{
    const PROTECTION_MODE_EXTENDED = 'extended';

    public function isSubDirectoryInstallation(): bool
    {
        return true;
    }

    public function protectionMode(): string
    {
        return 'basic';
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
            $_issues = new self;
        }

        return $_issues;
    }

    public function getIssues($offset = 0, $limit = 100, $ignoredOffset = 0, $ignoredLimit = 100)
    {
        return [];
    }
}
