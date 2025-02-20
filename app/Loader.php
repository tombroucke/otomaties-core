<?php

namespace Otomaties\Core;

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 */
class Loader
{
    /**
     * The array of actions registered with WordPress.
     *
     * @var array<int|string, mixed> The actions registered with WordPress to fire when the plugin loads.
     */
    protected $actions;

    /**
     * The array of filters registered with WordPress.
     *
     * @var array<int|string, mixed> The filters registered with WordPress to fire when the plugin loads.
     */
    protected $filters;

    /**
     * Initialize the collections used to maintain the actions and filters.
     */
    public function __construct()
    {

        $this->actions = [];
        $this->filters = [];
    }

    /**
     * Add a new action to the collection to be registered with WordPress.
     *
     * @param  string  $hook  The name of the WordPress action that is being registered.
     * @param  mixed  $component  A reference to the instance of the object on which the action is defined.
     * @param  string  $callback  The name of the function definition on the $component.
     * @param  int  $priority  Optional. The priority at which the function should be fired. Default is 10.
     * @param  int  $accepted_args  Optional. The number of arguments that should be passed to the $callback.
     *                              Default is 1.
     */
    public function addAction($hook, $component, $callback, $priority = 10, $accepted_args = 1): void
    {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Add a new filter to the collection to be registered with WordPress.
     *
     * @param  string  $hook  The name of the WordPress filter that is being registered.
     * @param  mixed  $component  A reference to the instance of the object on which the filter is defined.
     * @param  string  $callback  The name of the function definition on the $component.
     * @param  int  $priority  Optional. The priority at which the function should be fired. Default is 10.
     * @param  int  $accepted_args  Optional. The number of arguments that should be passed to the $callback.
     *                              Default is 1
     */
    public function addFilter($hook, $component, $callback, $priority = 10, $accepted_args = 1): void
    {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * A utility function that is used to register the actions and hooks into a single
     * collection.
     *
     * @param  array<int|string, mixed>  $hooks  The collection of hooks that is being registered
     * @param  string  $hook  The name of the WordPress filter that is being registered.
     * @param  mixed  $component  A reference to the instance of the object on which the filter is defined.
     * @param  string  $callback  The name of the function definition on the $component.
     * @param  int  $priority  The priority at which the function should be fired.
     * @param  int  $accepted_args  The number of arguments that should be passed to the $callback.
     * @return array<int|string, mixed> The collection of actions and filters registered with WordPress.
     */
    private function add($hooks, $hook, $component, $callback, $priority, $accepted_args): array
    {

        $hooks[] = [
            'hook' => $hook,
            'component' => $component,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
        ];

        return $hooks;
    }

    /**
     * Register the filters and actions with WordPress.
     */
    public function run(): void
    {

        foreach ($this->filters as $hook) {
            add_filter(
                $hook['hook'],
                [$hook['component'], $hook['callback']],
                $hook['priority'],
                $hook['accepted_args']
            );
        }

        foreach ($this->actions as $hook) {
            add_action(
                $hook['hook'],
                [$hook['component'], $hook['callback']],
                $hook['priority'],
                $hook['accepted_args']
            );
        }
    }
}
