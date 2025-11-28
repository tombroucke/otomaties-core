<?php

namespace OtomatiesCoreVendor\Illuminate\Support\Facades;

use OtomatiesCoreVendor\Illuminate\Foundation\MaintenanceModeManager;
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
