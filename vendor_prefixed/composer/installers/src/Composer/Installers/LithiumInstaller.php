<?php

namespace OtomatiesCoreVendor\Composer\Installers;

/** @internal */
class LithiumInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('library' => 'libraries/{$name}/', 'source' => 'libraries/_source/{$name}/');
}
