<?php

declare(strict_types=1);

use Otomaties\Core\Modules\Connect;
use PHPUnit\Framework\TestCase;

final class ConnectTest extends TestCase
{
    public function test_if_general_info_is_array()
    {
        $connect = new Connect('1.0.0', 'production');
        $this->assertIsArray($connect->generalInfo());
    }

    public function test_if_general_info_contains_required_fields()
    {
        $connect = new Connect('1.0.0', 'production');
        $generalInfo = $connect->generalInfo();

        $this->assertArrayHasKey('env', $generalInfo);
        $this->assertArrayHasKey('bedrock', $generalInfo);
        $this->assertArrayHasKey('version', $generalInfo);
        $this->assertArrayHasKey('plugins', $generalInfo);
        $this->assertArrayHasKey('reading', $generalInfo);
        $this->assertArrayHasKey('security', $generalInfo);

        $this->assertArrayHasKey('php', $generalInfo['version']);
        $this->assertArrayHasKey('wordpress', $generalInfo['version']);
        $this->assertArrayHasKey('otomaties_core', $generalInfo['version']);
    }

    public function test_if_bedrock_is_boolean()
    {
        $connect = new Connect('1.0.0', 'production');
        $generalInfo = $connect->generalInfo();
        $this->assertIsBool($generalInfo['bedrock']);
    }

    public function test_if_version_is_correct()
    {
        $connect = new Connect('1.13.0', 'production');
        $generalInfo = $connect->generalInfo();
        $this->assertEquals('1.13.0', $generalInfo['version']['otomaties_core']);
    }

    public function test_if_active_plugins_is_correct()
    {
        $plugins = get_option('active_plugins');
        $connect = new Connect('1.0.0', 'production');
        $generalInfo = $connect->generalInfo();
        $this->assertEquals($plugins, $generalInfo['plugins']['active']);
    }

    public function test_if_disable_indexing_is_boolean()
    {
        $connect = new Connect('1.0.0', 'production');
        $generalInfo = $connect->generalInfo();
        $this->assertIsBool($generalInfo['reading']['disable_indexing']);
    }
}
