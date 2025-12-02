<?php

namespace Otomaties\Core\Modules\HealthTests;

use Otomaties\Core\Modules\HealthTests\Dtos\HealthTestResponseDto;
use Otomaties\Core\Modules\HealthTests\Enums\HealthCheckCategory;

class DefaultTaglineIsDeleted extends Abstracts\HealthTest
{
    protected string $category = HealthCheckCategory::SEO;

    public function passes(): bool
    {
        return ! preg_match('/^Just another .+ site$/', get_option('blogdescription'));
    }

    public function passedResponse(HealthTestResponseDto $response): HealthTestResponseDto
    {
        return $response
            ->withLabel(__('The default tagline has been changed', 'otomaties-core'))
            ->withDescription(sprintf(
                '<p>%s</p>',
                __('The default tagline has been changed to something more meaningful', 'otomaties-core')
            ));
    }

    public function failedResponse(HealthTestResponseDto $response): HealthTestResponseDto
    {
        return $response
            ->withStatus('critical')
            ->withLabel(__('The default tagline has not been changed', 'otomaties-core'))
            ->withDescription(sprintf(
                '<p>%s</p>',
                __('The default tagline is still active on this website. Change the tagline to something more meaningful or consider removing the tagline.', 'otomaties-core') // phpcs:ignore Generic.Files.LineLength.TooLong
            ))
            ->withActions(sprintf(
                '<a href="%s" target="_blank">%s</a>',
                admin_url('options-general.php'),
                __('Change the tagline', 'otomaties-core')
            ));
    }
}
