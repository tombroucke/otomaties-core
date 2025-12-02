<?php

namespace Otomaties\Core\Modules\HealthTests;

use Otomaties\Core\Modules\HealthTests\Dtos\HealthTestResponseDto;

class MollieTestMode extends Abstracts\HealthTest
{
    public function passes(): bool
    {
        $testModeEnabled = get_option('mollie-payments-for-woocommerce_test_mode_enabled') !== 'yes';

        return otomatiesCore()->environment() !== 'production' || $testModeEnabled;
    }

    public function isActive(): bool
    {
        if (! is_plugin_active('mollie-payments-for-woocommerce/mollie-payments-for-woocommerce.php')) {
            return false;
        }

        return parent::isActive();
    }

    public function passedResponse(HealthTestResponseDto $response): HealthTestResponseDto
    {
        $label = otomatiesCore()->environment() === 'production' ?
            __('Mollie is in Live mode', 'otomaties-core') :
            __('Mollie is in test mode but the environment is not production', 'otomaties-core');

        return $response
            ->withLabel($label)
            ->withDescription(sprintf(
                '<p>%s</p>',
                $label
            ));
    }

    public function failedResponse(HealthTestResponseDto $response): HealthTestResponseDto
    {
        return $response
            ->withStatus('critical')
            ->withLabel(__('Mollie is in test mode', 'otomaties-core'))
            ->withDescription(sprintf(
                '<p>%s</p>',
                __('Mollie is in test mode', 'otomaties-core')
            ))
            ->withActions(sprintf(
                '<a href="%s" target="_blank">%s</a>',
                admin_url('admin.php?page=wc-settings&tab=mollie_settings'),
                __('Disable test mode', 'otomaties-core')
            ));
    }
}
