<?php

namespace Otomaties\Core\Modules\HealthTests;

use Otomaties\Core\Modules\HealthTests\Dtos\HealthTestResponseDto;
use Otomaties\Core\Modules\HealthTests\Enums\HealthCheckCategory;

class AccessToSensitiveFilesBlocked extends Abstracts\HealthTest
{
    protected string $type = 'async';

    protected string $category = HealthCheckCategory::SECURITY;

    public function passes(): bool
    {
        $response = wp_remote_get(
            home_url('test.sql'),
            ['sslverify' => otomatiesCore()->environment() === 'production']
        );

        return wp_remote_retrieve_response_code($response) === 403;
    }

    public function passedResponse(HealthTestResponseDto $response): HealthTestResponseDto
    {
        return $response
            ->withLabel(__('Access to sensitive files is blocked', 'otomaties-core'))
            ->withDescription(sprintf(
                '<p>%s</p>',
                __('Access to sensitive files is blocked', 'otomaties-core')
            ));
    }

    public function failedResponse(HealthTestResponseDto $response): HealthTestResponseDto
    {
        return $response
            ->withStatus('critical')
            ->withLabel(__('Access to sensitive files is not blocked', 'otomaties-core'))
            ->withDescription(sprintf(
                '<p>%s</p>',
                __('Add rules to block sensitive files to your .htaccess file', 'otomaties-core')
            ));
    }
}
