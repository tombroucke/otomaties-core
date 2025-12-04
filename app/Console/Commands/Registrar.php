<?php

namespace Otomaties\Core\Console\Commands;

class Registrar
{
    /** @var array<int, string> */
    protected array $commands = [
        CleanWpSeoTaxonomyMetaCommand::class,
    ];

    public function register(): void
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
