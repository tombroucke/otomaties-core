<?php

namespace Otomaties\Core\Modules;

use OtomatiesCoreVendor\Illuminate\Support\Collection;

class HealthTests
{
    private ?Collection $tests = null;

    public function init(): void
    {
        add_filter('site_status_tests', [$this, 'addTests']);
        add_filter('site_status_tests', [$this, 'removeBackgroundUpdatesTest']);
        add_action('rest_api_init', [$this, 'addAsyncTestRoutes']);
    }

    /**
     * Get all available test
     */
    public function tests(): Collection
    {
        if (! isset($this->tests)) {
            $this->tests = (new Collection(glob(__DIR__ . '/HealthTests/*.php')))
                ->map(function ($file) {
                    $class = basename($file, '.php');
                    $namespace = __NAMESPACE__ . '\\HealthTests\\';

                    return new ($namespace . $class);
                })
                ->filter(fn ($test) => $test->isActive())
                ->mapWithKeys(function ($test, $key) {
                    return [$test->name() => [
                        'label' => $test->name(),
                        'test' => [$test, 'respond'],
                        'type' => $test->type(),
                    ]];
                });
        }

        return $this->tests;
    }

    public function addTests($tests): array
    {
        $this->directTests()
            ->each(function ($test, $key) use (&$tests) {
                $tests['direct'][$key] = [
                    'label' => $test['label'],
                    'test' => $test['test'],
                ];
            });

        $this->asyncTests()
            ->each(function ($test, $key) use (&$tests) {
                $tests['async'][$key] = [
                    'label' => $test['label'],
                    'test' => rest_url(sprintf('otomaties-health-check/v1/tests/%s', $key)),
                    'has_rest' => true,
                    'async_direct_test' => $test['test'],
                ];
                add_action('wp_ajax_' . $key, $test['test']);
            });

        return $tests;
    }

    public function addAsyncTestRoutes(): void
    {
        $this->asyncTests()
            ->each(function ($test, $key) {
                register_rest_route(
                    'otomaties-health-check/v1',
                    sprintf(
                        '/tests/%s',
                        $key
                    ),
                    [
                        'methods' => 'GET',
                        'callback' => $test['test'],
                        'permission_callback' => function () use ($key) {
                            return $this->validateRequestPermission($key);
                        },
                    ]
                );
            });
    }

    public function removeBackgroundUpdatesTest($tests): array
    {

        if (class_exists('\\Roots\\WPConfig\\Config') && isset($tests['async']['background_updates'])) {
            unset($tests['async']['background_updates']);
        }

        return $tests;
    }

    private function directTests(): Collection
    {
        return (new Collection($this->tests()))
            ->filter(function ($test) {
                return ($test['type'] ?? 'direct') === 'direct';
            });
    }

    private function asyncTests(): Collection
    {
        return (new Collection($this->tests()))
            ->filter(function ($test) {
                return ($test['type'] ?? 'direct') !== 'direct';
            });
    }

    private function validateRequestPermission($check): bool
    {
        $capability = apply_filters(
            "otomaties_core_site_health_test_rest_capability_{$check}",
            'view_site_health_checks',
            $check
        );

        return current_user_can($capability);
    }
}
