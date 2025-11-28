<?php

namespace Otomaties\Core;

use OtomatiesCoreVendor\Illuminate\Config\Repository;
use OtomatiesCoreVendor\Illuminate\Support\Collection;

class Config extends Repository
{
    public function __construct()
    {
        $this->items = $this->loadConfig();
    }

    private function loadConfig(): array
    {
        return (new Collection(glob(dirname(__DIR__) . '/config/*.php')))
            ->mapWithKeys(function ($configFile) {
                $key = basename($configFile, '.php');

                return [$key => require $configFile];
            })
            ->toArray();
    }
}
