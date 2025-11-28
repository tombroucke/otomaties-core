<?php

namespace OtomatiesCoreVendor\Composer\Installers;

/** @internal */
class AnnotateCmsInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('module' => 'addons/modules/{$name}/', 'component' => 'addons/components/{$name}/', 'service' => 'addons/services/{$name}/');
}
