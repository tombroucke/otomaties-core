<?php

namespace Otomaties\Core\Database\Migrations;

use OtomatiesCoreVendor\Illuminate\Support\Collection;

class Migrator
{
    public function run(): void
    {
        (new Collection(glob(otomatiesCore()->config('paths.base') . '/database/migrations/*.php')))
            ->map(fn ($file) => include $file)
            ->filter(fn ($migration) => $migration instanceof Migration)
            ->reject(fn (Migration $migration) => $migration->hasFinished())
            ->each(function (Migration $migration) {
                $migration->up();
                $migration->finish();
            });
    }
}
