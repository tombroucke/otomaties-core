<?php

namespace OtomatiesCoreVendor\Composer\Installers;

/** @internal */
class PrestashopInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('module' => 'modules/{$name}/', 'theme' => 'themes/{$name}/');
}
