<?php

namespace Otomaties\Core\Modules\HealthTests;

use Otomaties\Core\Modules\HealthTests\Enums\HealthCheckCategory;

class AccessToSensitiveFilesBlocked extends Abstracts\HealthTest implements Contracts\HealthTest
{
    protected string $type = 'async';

    protected string $category = HealthCheckCategory::SECURITY;

    public function passes() : bool
    {
        $response = wp_remote_get(
            home_url('test.sql'),
            ['sslverify' => otomatiesCore()->environment() === 'production',]
        );
        return wp_remote_retrieve_response_code($response) === 403;
    }

    public function passedResponse() : array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('Access to sensitive files is blocked', 'otomaties-health-check'),
            'description' => sprintf(
                '<p>%s</p>',
                __('Access to sensitive files is blocked', 'otomaties-health-check')
            ),
        ]);
    }

    public function failedResponse() : array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('Access to sensitive files is not blocked', 'otomaties-health-check'),
            'description' => sprintf(
                '<p>%s</p>',
                __('Add rules to block sensitive files to your .htaccess file', 'otomaties-health-check')
            )
        ]);
    }
}
