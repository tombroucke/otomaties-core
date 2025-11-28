<?php

namespace OtomatiesCoreVendor\Composer\Installers;

/** @internal */
class MakoInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('package' => 'app/packages/{$name}/');
}
