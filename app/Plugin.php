<?php

namespace Otomaties\Core;

use Otomaties\Core\Console\Commands\Registrar;
use Otomaties\Core\Illuminate\Container\Container;
use Otomaties\Core\Illuminate\Support\Collection;

class Plugin extends Container
{
    private array $modules = [
        Modules\Admin::class,
        Modules\Frontend::class,
        Modules\Emojis::class,
        Modules\Shortcodes::class,
        Modules\Mollie::class,
        Modules\WooCommerce::class,
        Modules\Privacy::class,
        Modules\Revision::class,
        Modules\Discussion::class,
        Modules\Security::class,
        Modules\Connect::class,
    ];

    public function __construct(private string $version = '1.0.0', private string $env = 'production')
    {
        //
    }

    private function register()
    {
        $this->bind(View::class, fn () => new View($this->config('paths.views')));
    }

    public function config(string $key): mixed
    {
        $config = $this->make(Config::class);

        return $config->get($key);
    }

    public function initialize(): self
    {
        $this->register();

        $this->loadTextDomain();
        $this->loadModules();
        $this->initCommands();

        return $this;
    }

    private function loadTextDomain(): void
    {
        add_action('init', function () {
            load_muplugin_textdomain('otomaties-core', basename($this->config('paths.base')) . '/resources/languages');
        });
    }

    private function initCommands(): void
    {
        $this
            ->make(Registrar::class)
            ->register();
    }

    private function loadModules(): self
    {
        (new Collection($this->modules))
            ->each(function ($className) {
                ($this->make($className))
                    ->init();
            });

        return $this;
    }

    public function environment(): string
    {
        return $this->env;
    }

    public function version(): string
    {
        return $this->version;
    }
}
