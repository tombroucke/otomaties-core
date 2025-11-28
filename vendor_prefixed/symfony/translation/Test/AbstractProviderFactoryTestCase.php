<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace OtomatiesCoreVendor\Symfony\Component\Translation\Test;

use OtomatiesCoreVendor\PHPUnit\Framework\Attributes\DataProvider;
use OtomatiesCoreVendor\PHPUnit\Framework\TestCase;
use OtomatiesCoreVendor\Symfony\Component\Translation\Exception\UnsupportedSchemeException;
use OtomatiesCoreVendor\Symfony\Component\Translation\Provider\Dsn;
use OtomatiesCoreVendor\Symfony\Component\Translation\Provider\ProviderFactoryInterface;
/** @internal */
abstract class AbstractProviderFactoryTestCase extends TestCase
{
    public abstract function createFactory() : ProviderFactoryInterface;
    /**
     * @return iterable<array{0: bool, 1: string}>
     */
    public static abstract function supportsProvider() : iterable;
    /**
     * @return iterable<array{0: string, 1: string}>
     */
    public static abstract function createProvider() : iterable;
    /**
     * @return iterable<array{0: string, 1?: string|null}>
     */
    public static abstract function unsupportedSchemeProvider() : iterable;
    /**
     * @dataProvider supportsProvider
     */
    #[DataProvider('supportsProvider')]
    public function testSupports(bool $expected, string $dsn)
    {
        $factory = $this->createFactory();
        $this->assertSame($expected, $factory->supports(new Dsn($dsn)));
    }
    /**
     * @dataProvider createProvider
     */
    #[DataProvider('createProvider')]
    public function testCreate(string $expected, string $dsn)
    {
        $factory = $this->createFactory();
        $provider = $factory->create(new Dsn($dsn));
        $this->assertSame($expected, (string) $provider);
    }
    /**
     * @dataProvider unsupportedSchemeProvider
     */
    #[DataProvider('unsupportedSchemeProvider')]
    public function testUnsupportedSchemeException(string $dsn, ?string $message = null)
    {
        $factory = $this->createFactory();
        $dsn = new Dsn($dsn);
        $this->expectException(UnsupportedSchemeException::class);
        if (null !== $message) {
            $this->expectExceptionMessage($message);
        }
        $factory->create($dsn);
    }
}
