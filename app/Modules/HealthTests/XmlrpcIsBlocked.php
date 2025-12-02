<?php

namespace Otomaties\Core\Modules\HealthTests;

use Otomaties\Core\Modules\HealthTests\Dtos\HealthTestResponseDto;
use Otomaties\Core\Modules\HealthTests\Enums\HealthCheckCategory;

class XmlrpcIsBlocked extends Abstracts\HealthTest
{
    protected string $category = HealthCheckCategory::SECURITY;

    protected string $type = 'async';

    public function passes(): bool
    {
        $response = wp_remote_get(
            site_url('xmlrpc.php'),
            ['sslverify' => otomatiesCore()->environment() === 'production']
        );

        return wp_remote_retrieve_response_code($response) === 403;
    }

    public function passedResponse(HealthTestResponseDto $response): HealthTestResponseDto
    {
        return $response
            ->withLabel(__('Access to xmlrpc.php is blocked', 'otomaties-core'))
            ->withDescription(sprintf(
                '<p>%s</p>',
                __('Access to xmlrpc.php is blocked', 'otomaties-core')
            ));
    }

    public function failedResponse(HealthTestResponseDto $response): HealthTestResponseDto
    {
        return $response
            ->withStatus('critical')
            ->withLabel(__('Access to xmlrpc.php is not blocked', 'otomaties-core'))
            ->withDescription(sprintf(
                '<p>%s</p>',
                __('Add rules to block xmlrpc.php to your .htaccess file', 'otomaties-core')
            ));
    }
}
