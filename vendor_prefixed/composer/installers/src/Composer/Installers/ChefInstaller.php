<?php

namespace OtomatiesCoreVendor\Composer\Installers;

/** @internal */
class ChefInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('cookbook' => 'Chef/{$vendor}/{$name}/', 'role' => 'Chef/roles/{$name}/');
}
