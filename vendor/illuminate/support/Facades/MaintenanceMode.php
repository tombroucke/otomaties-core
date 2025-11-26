<?php

namespace Otomaties\Core\Illuminate\Support\Facades;

use Otomaties\Core\Illuminate\Foundation\MaintenanceModeManager;
class MaintenanceMode extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return MaintenanceModeManager::class;
    }
}
