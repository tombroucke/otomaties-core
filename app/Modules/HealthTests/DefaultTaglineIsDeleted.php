<?php

namespace Otomaties\Core\Modules\HealthTests;

use Otomaties\Core\Modules\HealthTests\Enums\HealthCheckCategory;

class DefaultTaglineIsDeleted extends Abstracts\HealthTest implements Contracts\HealthTest
{
    protected string $category = HealthCheckCategory::SEO;

    public function passes() : bool
    {
        return ! preg_match('/^Just another .+ site$/', get_option('blogdescription'));
    }

    public function passedResponse() : array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('The default tagline has been changed', 'otomaties-health-check'),
            'status' => 'good',
            'description' => sprintf(
                '<p>%s</p>',
                __('The default tagline has been changed to something more meaningful', 'otomaties-health-check')
            ),
        ]);
    }

    public function failedResponse() : array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('The default tagline has not been changed', 'otomaties-health-check'),
            'description' => sprintf(
                '<p>%s</p>',
                __('The default tagline is still active on this website. Change the tagline to something more meaningful or consider removing the tagline.', 'otomaties-health-check') // phpcs:ignore Generic.Files.LineLength.TooLong
            ),
            'actions' => sprintf(
                '<a href="%s" target="_blank">%s</a>',
                admin_url('options-general.php'),
                __('Change the tagline', 'otomaties-health-check')
            )
        ]);
    }
}
