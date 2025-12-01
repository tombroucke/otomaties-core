<?php

namespace Otomaties\Core\Modules\HealthTests;

use Otomaties\Core\Modules\HealthTests\Enums\HealthCheckCategory;
use OtomatiesCoreVendor\Illuminate\Support\Collection;

class NgFirewall extends Abstracts\HealthTest implements Contracts\HealthTest
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

    public function passedResponse(): array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('nG Firewall is enabled', 'otomaties-health-check'),
            'description' => sprintf(
                '<p>%s</p>',
                sprintf(
                    __('Visit %s for more information about the nG Firewall', 'otomaties-health-check'),
                    '<a href="https://perishablepress.com/ng-firewall/" target="_blank">nG Firewall</a>'
                )
            ),
        ]);
    }

    public function failedResponse(): array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('nG Firewall is not enabled', 'otomaties-health-check'),
            'description' => sprintf(
                '<p>%s</p>',
                sprintf(
                    __('The nG Firewall is not active on this website. Visit %s for more information', 'otomaties-health-check'),  // phpcs:ignore Generic.Files.LineLength.TooLong
                    '<a href="https://perishablepress.com/ng-firewall/" target="_blank">nG Firewall</a>'
                )
            ),
        ]);
    }
}
