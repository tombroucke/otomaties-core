<?php

namespace OtomatiesCoreVendor\Composer\Installers;

class DframeInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('module' => 'modules/{$vendor}/{$name}/');
}
