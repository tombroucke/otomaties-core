<?php

declare(strict_types=1);

use Otomaties\Core\Security;
use PHPUnit\Framework\TestCase;

final class SecurityTest extends TestCase
{
    public function test_if_has_debug_notices()
    {
        $security = new Security('production');
        ob_start();
        $security->debugNotice();
        $debugNotice = ob_get_clean();
        $this->assertNotEmpty($debugNotice);
        $this->assertStringContainsString('Disable debugging for better security', $debugNotice);
        $this->assertStringContainsString('Disallow file editing for better security', $debugNotice);
        $this->assertStringContainsString('Install & activate Wordfence, Sucuri Security or WP Defender for optimal security.', $debugNotice);
    }

    public function test_login_has_generic_errors()
    {
        $security = new Security('production');
        $this->assertNotEmpty($security->genericLoginErrors([]));
        $this->assertStringContainsString('Could not log you in', $security->genericLoginErrors([]));
        $this->assertStringContainsString('https://example.com/login', $security->genericLoginErrors([]));
    }

    public function test_if_attachment_is_https()
    {
        $security = new Security('production');
        $attachment = $security->forceAttachmentHttps('http://example.com/test.jpg');
        $this->assertEquals('https://example.com/test.jpg', $attachment);
    }

    public function test_if_critical_options_cant_be_updated()
    {
        $security = new Security('production');
        $this->assertEquals(0, $security->disableUpdateCriticalOptions(1, 'users_can_register'));
        $this->assertEquals('subscriber', $security->disableUpdateCriticalOptions('administrator', 'default_role'));
        $this->assertEquals('other_value', $security->disableUpdateCriticalOptions('other_value', 'other_key'));
    }

    public function test_if_critical_options_update_notice_is_displayed()
    {
        $security = new Security('production');
        ob_start();
        $security->showSecurityNotices();
        $notices = ob_get_clean();
        $this->assertStringContainsString('Otomaties core has disabled updating of', $notices);

        custom_change_current_screen((object) [
            'id' => 'plugins',
            'base' => 'plugins',
        ]);

        ob_start();
        $security->showSecurityNotices();
        $notices = ob_get_clean();
        $this->assertStringNotContainsString('Otomaties core has disabled updating of', $notices);
    }
}
