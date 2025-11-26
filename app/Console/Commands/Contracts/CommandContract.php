<?php

namespace Otomaties\Core\Console\Commands\Contracts;

interface CommandContract
{
    public function handle(array $args, array $assocArgs): void;
}
