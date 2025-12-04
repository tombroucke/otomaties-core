<?php

namespace Otomaties\Core\Console\Commands\Contracts;

interface CommandContract
{
    /**
     * Handle the command
     *
     * @param  array<int, string>  $args
     * @param  array<string, mixed>  $assocArgs
     */
    public function handle(array $args, array $assocArgs): void;
}
