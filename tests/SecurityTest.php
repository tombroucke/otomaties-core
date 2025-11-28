<?php

declare(strict_types=1);

use Otomaties\Core\Modules\Security;
use Otomaties\Core\View;
use PHPUnit\Framework\TestCase;

final class SecurityTest extends TestCase
{
    public function test_if_has_debug_notices()
    {
        $view = new View('resources/views');
        $security = new Security($view);

        $this->expectOutputRegex('/Disable debugging for better security/');
        $security->debugNotice();
    }

    public function test_login_has_generic_errors()
    {
        $view = new View('resources/views');
        $security = new Security($view);
        $this->assertNotEmpty($security->genericLoginErrors([]));
        $this->assertStringContainsString('Could not log you in', $security->genericLoginErrors([]));
        $this->assertStringContainsString('https://example.com/login', $security->genericLoginErrors([]));
    }

    public function test_if_attachment_is_https()
    {
        $view = new View('resources/views');
        $security = new Security($view);
        $attachment = $security->forceAttachmentHttps('http://example.com/test.jpg');
        $this->assertEquals('https://example.com/test.jpg', $attachment);
    }

    public function test_if_critical_options_cant_be_updated()
    {
        $view = new View('resources/views');
        $security = new Security($view);
        $this->assertEquals(0, $security->disableUpdateCriticalOptions(1, 'users_can_register'));
        $this->assertEquals('subscriber', $security->disableUpdateCriticalOptions('administrator', 'default_role'));
        $this->assertEquals('other_value', $security->disableUpdateCriticalOptions('other_value', 'other_key'));
    }

    public function test_if_critical_options_update_notice_is_displayed()
    {
        $view = new View('resources/views');
        $security = new Security($view);

        // expect output to contain "Otomaties core has disabled updating of"
        $this->expectOutputRegex('/Otomaties core has disabled updating of/');
        $security->showSecurityNotices();

        custom_change_current_screen((object) [
            'id' => 'plugins',
            'base' => 'plugins',
        ]);
        // expect no output
        $this->expectOutputString('');
        $security->showSecurityNotices();
    }

    public function test_if_critical_options_update_notice_is_not_displayed()
    {
        $view = new View('resources/views');
        $security = new Security($view);

        custom_change_current_screen((object) [
            'id' => 'plugins',
            'base' => 'plugins',
        ]);
        // expect no output
        $this->expectOutputString('');
        $security->showSecurityNotices();
    }
}
