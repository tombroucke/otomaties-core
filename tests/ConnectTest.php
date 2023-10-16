<?php declare(strict_types=1);

use Otomaties\Core\Connect;
use PHPUnit\Framework\TestCase;

final class ConnectTest extends TestCase
{
    public function testIfGeneralInfoIsArray()
    {
        $connect = new Connect('1.0.0', 'production');
        $this->assertIsArray($connect->generalInfo());
    }

    public function testIfGeneralInfoContainsRequiredFields()
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

    public function testIfBedrockIsBoolean()
    {
        $connect = new Connect('1.0.0', 'production');
        $generalInfo = $connect->generalInfo();
        $this->assertIsBool($generalInfo['bedrock']);
    }

    public function testIfVersionIsCorrect() {
        $connect = new Connect('1.13.0', 'production');
        $generalInfo = $connect->generalInfo();
        $this->assertEquals('1.13.0', $generalInfo['version']['otomaties_core']);
    }

    public function testIfActivePluginsIsCorrect() {
        $plugins = get_option('active_plugins');
        $connect = new Connect('1.0.0', 'production');
        $generalInfo = $connect->generalInfo();
        $this->assertEquals($plugins, $generalInfo['plugins']['active']);
    }

    public function testIfDisableIndexingIsBoolean()
    {
        $connect = new Connect('1.0.0', 'production');
        $generalInfo = $connect->generalInfo();
        $this->assertIsBool($generalInfo['reading']['disable_indexing']);
    }
}
