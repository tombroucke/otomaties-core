<?php

namespace Otomaties\Core\Modules\HealthTests;

use Otomaties\Core\Modules\HealthTests\Dtos\HealthTestResponseDto;
use Otomaties\Core\Modules\HealthTests\Enums\HealthCheckCategory;

class FaviconIsSet extends Abstracts\HealthTest
{
    protected string $category = HealthCheckCategory::APPEARANCE;

    public function passes(): bool
    {
        $favicon = get_site_icon_url();

        return ! empty($favicon);
    }

    public function passedResponse(HealthTestResponseDto $response): HealthTestResponseDto
    {
        return $response
            ->withLabel(__('Favicon is set', 'otomaties-core'))
            ->withDescription(sprintf(
                '<p>%s</p>',
                __('The favicon is set', 'otomaties-core')
            ));
    }

    public function failedResponse(HealthTestResponseDto $response): HealthTestResponseDto
    {
        return $response
            ->withStatus('recommended')
            ->withLabel(__('Favicon is not set', 'otomaties-core'))
            ->withDescription(sprintf(
                '<p>%s</p>',
                sprintf(
                    __('The favicon is not set on this website. Visit the %s to set your favicon', 'otomaties-core'), // phpcs:ignore Generic.Files.LineLength.TooLong
                )
            ))
            ->withActions(sprintf(
                '<a href="%s" target="_blank">%s</a>',
                admin_url('customize.php'),
                __('Set Favicon', 'otomaties-core')
            ));
    }
}
