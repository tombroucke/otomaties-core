<?php

namespace Otomaties\Core\Console\Commands;

class Registrar
{
    protected array $commands = [
        CleanWpSeoTaxonomyMetaCommand::class,
    ];

    public function register()
    {
        if (! defined('WP_CLI') || ! WP_CLI) {
            return;
        }

        foreach ($this->commands as $commandClass) {
            \WP_CLI::add_command(
                $commandClass::COMMAND_NAME,
                function ($args, $assocArgs) use ($commandClass) {
                    $commandInstance = otomatiesCore()->make($commandClass);
                    $commandInstance->handle($args, $assocArgs);
                },
                [
                    'shortdesc' => $commandClass::COMMAND_DESCRIPTION,
                    'synopsis' => $commandClass::COMMAND_ARGUMENTS,
                ]
            );
        }
    }
}
