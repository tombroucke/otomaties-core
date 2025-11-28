<?php

namespace OtomatiesCoreVendor\Composer\Installers;

/** @internal */
class ElggInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('plugin' => 'mod/{$name}/');
}
