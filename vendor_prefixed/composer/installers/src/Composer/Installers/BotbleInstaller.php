<?php

namespace OtomatiesCoreVendor\Composer\Installers;

/** @internal */
class BotbleInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('plugin' => 'platform/plugins/{$name}/', 'theme' => 'platform/themes/{$name}/');
}
