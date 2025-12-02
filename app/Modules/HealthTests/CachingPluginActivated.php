<?php

namespace Otomaties\Core\Modules\HealthTests;

use Otomaties\Core\Modules\HealthTests\Dtos\HealthTestResponseDto;
use Otomaties\Core\Modules\HealthTests\Enums\HealthCheckCategory;

class CachingPluginActivated extends Abstracts\HealthTest
{
    private ?string $activePlugin = null;

    protected string $category = HealthCheckCategory::PERFORMANCE;

    const CACHINGPLUGINS = [
        'wp-rocket' => [
            'label' => 'WP Rocket',
            'file' => 'wp-rocket/wp-rocket.php',
        ],
        'wp-super-page-cache-pr' => [
            'label' => 'WP Super Cache Pro',
            'file' => 'wp-super-page-cache-pro/wp-cloudflare-super-page-cache-pro.php',
        ],
        'wp-cloudflare-page-cach' => [
            'label' => 'WP Cloudflare Page Cache',
            'file' => 'wp-cloudflare-page-cache/wp-cloudflare-super-page-cache.php',
        ],
    ];

    public function passes(): bool
    {
        if ($this->activePlugin) {
            return true;
        }

        foreach (self::CACHINGPLUGINS as $plugin) {
            if (is_plugin_active($plugin['file'])) {
                $this->activePlugin = $plugin['label'];

                return true;
            }
        }

        return false;
    }

    public function passedResponse(HealthTestResponseDto $response): HealthTestResponseDto
    {
        return $response
            ->withLabel(__('Caching plugin is activated', 'otomaties-core'))
            ->withDescription(sprintf(
                '<p>%s</p>',
                sprintf(__('%s is installed and activated', 'otomaties-core'), $this->activePlugin)
            ));
    }

    public function failedResponse(HealthTestResponseDto $response): HealthTestResponseDto
    {
        return $response
            ->withStatus('recommended')
            ->withLabel(__('There is no active caching plugin', 'otomaties-core'))
            ->withDescription(sprintf(
                '<p>%s</p>',
                __('Please install and activate a caching plugin to improve site performance.', 'otomaties-core') // phpcs:ignore Generic.Files.LineLength
            ))
            ->withActions(sprintf(
                '<a href="%s" target="_blank">%s</a>',
                admin_url('plugins.php'),
                __('Activate Caching plugin', 'otomaties-core')
            ));
    }
}
