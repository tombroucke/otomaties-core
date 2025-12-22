<?php

namespace Otomaties\Core;

use Otomaties\Core\Console\Commands\Registrar;
use OtomatiesCoreVendor\Illuminate\Container\Container;
use OtomatiesCoreVendor\Illuminate\Support\Collection;

class Plugin extends Container
{
    /** @var array<int, string> */
    private array $modules = [
        Modules\Admin::class,
        Modules\Admin\Branding::class,
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
        Modules\HealthTests::class,
    ];

    public function __construct(private string $version = '1.0.0', private string $env = 'production')
    {
        //
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
        $this->initMigrations();

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

    public function findVariable(string $name): mixed
    {
        if (defined($name)) {
            return constant($name);
        }

        return $_ENV[$name] ?? $_SERVER[$name] ?? getenv($name) ?: null;
    }

    private function register(): void
    {
        $this->bind(View::class, fn () => new View($this->config('paths.views')));
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

    private function initMigrations(): void
    {
        add_action('admin_init', function () {
            $this->make(Database\Migrations\Migrator::class)
                ->run();
        });
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
}
