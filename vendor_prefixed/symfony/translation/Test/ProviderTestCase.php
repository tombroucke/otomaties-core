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
use OtomatiesCoreVendor\PHPUnit\Framework\MockObject\MockObject;
use OtomatiesCoreVendor\PHPUnit\Framework\TestCase;
use OtomatiesCoreVendor\Psr\Log\LoggerInterface;
use OtomatiesCoreVendor\Symfony\Component\HttpClient\MockHttpClient;
use OtomatiesCoreVendor\Symfony\Component\Translation\Dumper\XliffFileDumper;
use OtomatiesCoreVendor\Symfony\Component\Translation\Loader\LoaderInterface;
use OtomatiesCoreVendor\Symfony\Component\Translation\Provider\ProviderInterface;
use OtomatiesCoreVendor\Symfony\Component\Translation\TranslatorBagInterface;
use OtomatiesCoreVendor\Symfony\Contracts\HttpClient\HttpClientInterface;
/**
 * A test case to ease testing a translation provider.
 *
 * @author Mathieu Santostefano <msantostefano@protonmail.com>
 * @internal
 */
abstract class ProviderTestCase extends TestCase
{
    protected HttpClientInterface $client;
    protected LoggerInterface|MockObject $logger;
    protected string $defaultLocale;
    protected LoaderInterface|MockObject $loader;
    protected XliffFileDumper|MockObject $xliffFileDumper;
    protected TranslatorBagInterface|MockObject $translatorBag;
    public static abstract function createProvider(HttpClientInterface $client, LoaderInterface $loader, LoggerInterface $logger, string $defaultLocale, string $endpoint) : ProviderInterface;
    /**
     * @return iterable<array{0: ProviderInterface, 1: string}>
     */
    public static abstract function toStringProvider() : iterable;
    /**
     * @dataProvider toStringProvider
     */
    #[DataProvider('toStringProvider')]
    public function testToString(ProviderInterface $provider, string $expected)
    {
        $this->assertSame($expected, (string) $provider);
    }
    protected function getClient() : MockHttpClient
    {
        return $this->client ??= new MockHttpClient();
    }
    protected function getLoader() : LoaderInterface
    {
        return $this->loader ??= $this->createMock(LoaderInterface::class);
    }
    protected function getLogger() : LoggerInterface
    {
        return $this->logger ??= $this->createMock(LoggerInterface::class);
    }
    protected function getDefaultLocale() : string
    {
        return $this->defaultLocale ??= 'en';
    }
    protected function getXliffFileDumper() : XliffFileDumper
    {
        return $this->xliffFileDumper ??= $this->createMock(XliffFileDumper::class);
    }
    protected function getTranslatorBag() : TranslatorBagInterface
    {
        return $this->translatorBag ??= $this->createMock(TranslatorBagInterface::class);
    }
}
