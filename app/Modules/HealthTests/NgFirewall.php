<?php

namespace Otomaties\Core\Modules\HealthTests;

use OtomatiesCoreVendor\Illuminate\Support\Collection;
use Otomaties\Core\Modules\HealthTests\Enums\HealthCheckCategory;
use Otomaties\Core\Modules\HealthTests\Dtos\HealthTestResponseDto;

class NgFirewall extends Abstracts\HealthTest
{
    protected string $category = HealthCheckCategory::SECURITY;

    protected string $type = 'async';

    public function passes(): bool
    {
        $unblockedUrls = (new Collection([
            '?fck',
        ]))->reject(function ($url) {
            $response = wp_remote_get(
                home_url($url),
                ['sslverify' => otomatiesCore()->environment() === 'production']
            );

            return wp_remote_retrieve_response_code($response) !== 200;
        });

        return $unblockedUrls->isEmpty();
    }

    public function passedResponse(HealthTestResponseDto $response): HealthTestResponseDto
    {
        return $response
            ->withLabel(__('nG Firewall is enabled', 'otomaties-core'))
            ->withDescription(sprintf(
                '<p>%s</p>',
                __('The nG Firewall is active on this website.', 'otomaties-core')
            ))
            ->withActions(sprintf(
                '<a href="%s" target="_blank">%s</a>',
                'https://perishablepress.com/ng-firewall/',
                __('Learn more about nG Firewall', 'otomaties-core')
            ));
    }

    public function failedResponse(HealthTestResponseDto $response): HealthTestResponseDto
    {
        return $response
            ->withStatus('critical')
            ->withLabel(__('nG Firewall is not enabled', 'otomaties-core'))
            ->withDescription(sprintf(
                '<p>%s</p>',
                __('The nG Firewall is not active on this website.', 'otomaties-core')
            ))
            ->withActions(sprintf(
                '<a href="%s" target="_blank">%s</a>',
                'https://perishablepress.com/ng-firewall/',
                __('Learn more about nG Firewall', 'otomaties-core')
            ));
    }
}
