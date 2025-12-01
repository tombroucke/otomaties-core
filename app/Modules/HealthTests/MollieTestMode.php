<?php

namespace Otomaties\Core\Modules\HealthTests;

class MollieTestMode extends Abstracts\HealthTest implements Contracts\HealthTest
{
    public function passes(): bool
    {
        $testModeEnabled = get_option('mollie-payments-for-woocommerce_test_mode_enabled') !== 'yes';

        return otomatiesCore()->environment() !== 'production' || $testModeEnabled;
    }

    public function active(): bool
    {
        if (! is_plugin_active('mollie-payments-for-woocommerce/mollie-payments-for-woocommerce.php')) {
            return false;
        }

        return parent::active();
    }

    public function passedResponse(): array
    {
        $label = otomatiesCore()->environment() === 'production' ?
            __('Mollie is in Live mode', 'otomaties-health-check') :
            __('Mollie is in test mode but the environment is not production', 'otomaties-health-check');

        return array_merge($this->defaultResponse, [
            'label' => $label,
            'description' => sprintf(
                '<p>%s</p>',
                $label
            ),
        ]);
    }

    public function failedResponse(): array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('Mollie is in test mode', 'otomaties-health-check'),
            'status' => 'critical',
            'description' => sprintf(
                '<p>%s</p>',
                __('Mollie is in test mode', 'otomaties-health-check')
            ),
            'actions' => sprintf(
                '<a href="%s" target="_blank">%s</a>',
                admin_url('admin.php?page=wc-settings&tab=mollie_settings'),
                __('Disable test mode', 'otomaties-health-check')
            ),
        ]);
    }
}
