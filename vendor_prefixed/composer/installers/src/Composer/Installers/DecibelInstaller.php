<?php

namespace OtomatiesCoreVendor\Composer\Installers;

class DecibelInstaller extends BaseInstaller
{
    /** @var array */
    /** @var array<string, string> */
    protected $locations = array('app' => 'app/{$name}/');
}
