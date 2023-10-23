<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Otomaties\Core\Connect\ResponseBuilder;

final class ResponseBuilderTest extends TestCase
{
    public function testIfBuiltResponseIsArray()
    {
        $responseBuilder = new ResponseBuilder();
        $this->assertIsArray($responseBuilder->build());
    }

    public function testIfSimpleItemsCanBeAdded()
    {
        $responseBuilder = new ResponseBuilder();
        $responseBuilder->key('value');
        $this->assertEquals(['key' => 'value'], $responseBuilder->build());
    }

    public function testIfNestedItemsCanBeAdded()
    {
        $responseBuilder = new ResponseBuilder();
        $responseBuilder->foo()
            ->nested()
            ->bar('value')
            ->endNested()
            ->endFoo();
        $this->assertEquals(['foo' => ['nested' => ['bar' => 'value']]], $responseBuilder->build());
    }

    public function testIfItemsCanBeAddedToExistingKeys()
    {
        $responseBuilder = new ResponseBuilder();
        $responseBuilder->foo()
            ->bar('value')
        ->endFoo();
        $responseBuilder->foo()
            ->extraBar('extra_value')
        ->endFoo();
        $this->assertEquals(['foo' => ['bar' => 'value', 'extra_bar' => 'extra_value']], $responseBuilder->build());
    }
}
