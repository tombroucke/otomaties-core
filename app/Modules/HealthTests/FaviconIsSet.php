<?php

namespace Otomaties\Core\Modules\HealthTests;

use Otomaties\Core\Modules\HealthTests\Enums\HealthCheckCategory;

class FaviconIsSet extends Abstracts\HealthTest implements Contracts\HealthTest
{
    protected string $category = HealthCheckCategory::APPEARANCE;

    public function passes() : bool
    {
        $favicon = get_site_icon_url();
        return ! empty($favicon);
    }

    public function passedResponse() : array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('Favicon is set', 'otomaties-health-check'),
            'description' => sprintf(
                '<p>%s</p>',
                __('The favicon is set', 'otomaties-health-check')
            ),
        ]);
    }

    public function failedResponse() : array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('Favicon is not set', 'otomaties-health-check'),
            'description' => sprintf(
                '<p>%s</p>',
                sprintf(
                    __('The favicon is not set on this website. Visit the %s to set your favicon', 'otomaties-health-check'), // phpcs:ignore Generic.Files.LineLength.TooLong
                    sprintf(
                        '<a href="%s" target="_blank">%s</a>',
                        admin_url('customize.php'),
                        __('Customizer', 'otomaties-health-check')
                    )
                )
            )
        ]);
    }
}
