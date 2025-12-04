<?php

namespace Otomaties\Core\Database\Migrations;

abstract class Migration
{
    abstract public function up(): void;

    public function hasFinished(): bool
    {
        return get_option($this->optionName(), false);
    }

    public function finish(): void
    {
        update_option($this->optionName(), true);
    }

    private function optionName(): string
    {
        $reflection = new \ReflectionClass(static::class);
        $filename = $reflection->getFileName();

        return 'otomaties_migration_' . md5($filename);
    }
}
