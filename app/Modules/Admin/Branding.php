<?php

namespace Otomaties\Core\Modules\Admin;

use Otomaties\Core\View;

/**
 * Clean up admin section
 */
class Branding
{
    public function __construct(private View $view)
    {
        //
    }

    public function init(): void
    {
        add_action('admin_bar_menu', [$this, 'adminBarLogo'], 1);
        add_action('login_head', [$this, 'loginLogo'], 100);
        add_filter('admin_footer_text', [$this, 'adminFooterBranding'], 1);
    }

    /**
     * Add tb logo to admin bar
     *
     * @param  \WP_Admin_Bar  $wp_admin_bar  admin bar object.
     */
    public function adminBarLogo(\WP_Admin_Bar $wp_admin_bar): void
    {
        ob_start();
        include otomatiesCore()->config('paths.assets') . '/img/minilogo.svg';
        $minilogo = ob_get_clean();

        $wp_admin_bar->add_node([
            'id' => 'otomaties-core',
            'title' => (string) $minilogo,
            'meta' => [
                'class' => 'tb-logo',
                'html' => $this
                    ->view
                    ->return('admin/admin-bar-logo.html'),
            ],
        ]);
    }

    /**
     * TB logo on login page
     */
    public function loginLogo(): void
    {
        $this->view
            ->render('admin/login-logo', [
                'logo' => otomatiesCore()->config('assets.baseUri') . '/img/logo.svg',
            ]);
    }

    /**
     * Custom footer branding
     *
     * @param  string  $text  default text.
     */
    public function adminFooterBranding(string $text): string
    {
        return sprintf('<a target="_blank" href="%s">%s</a>', 'https://tombroucke.be', __('Website by', 'otomaties-core') . ' Tom Broucke'); // phpcs:ignore Generic.Files.LineLength
    }
}
