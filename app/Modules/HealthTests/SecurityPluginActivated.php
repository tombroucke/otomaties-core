<?php

namespace Otomaties\Core\Modules\HealthTests;

use Otomaties\Core\Modules\HealthTests\Dtos\HealthTestResponseDto;
use Otomaties\Core\Modules\HealthTests\Enums\HealthCheckCategory;

class SecurityPluginActivated extends Abstracts\HealthTest
{
    protected string $category = HealthCheckCategory::SECURITY;

    public function passes(): bool
    {
        return is_plugin_active('sucuri-scanner/sucuri.php')
            || is_plugin_active('wordfence/wordfence.php')
            || is_plugin_active('wp-defender/wp-defender.php')
            || is_plugin_active('defender-security/wp-defender.php');
    }

    public function passedResponse(HealthTestResponseDto $response): HealthTestResponseDto
    {
        return $response
            ->withStatus('good')
            ->withLabel(__('Wordfence is activated', 'otomaties-core'))
            ->withDescription(sprintf(
                '<p>%s</p>',
                __('Wordfence is installed and activated', 'otomaties-core')
            ));
    }

    public function failedResponse(HealthTestResponseDto $response): HealthTestResponseDto
    {
        return $response
            ->withStatus('critical')
            ->withLabel(__('Wordfence is not activated', 'otomaties-core'))
            ->withDescription(sprintf(
                '<p>%s</p>',
                sprintf(
                    __('Wordfence is not active on this website. Visit %s for more information', 'otomaties-core'), // phpcs:ignore Generic.Files.LineLength.TooLong
                    '<a href="https://www.wordfence.com/" target="_blank">Wordfence</a>'
                )
            ))
            ->withActions(sprintf(
                '<a href="%s" target="_blank">%s</a>',
                admin_url('plugins.php'),
                __('Activate Wordfence', 'otomaties-core')
            ));
    }
}
