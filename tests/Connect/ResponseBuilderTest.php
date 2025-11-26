<?php

declare(strict_types=1);

use Otomaties\Core\Modules\Connect\ResponseBuilder;
use PHPUnit\Framework\TestCase;

final class ResponseBuilderTest extends TestCase
{
    public function test_if_built_response_is_array()
    {
        $responseBuilder = new ResponseBuilder;
        $this->assertIsArray($responseBuilder->build());
    }

    public function test_if_simple_items_can_be_added()
    {
        $responseBuilder = new ResponseBuilder;
        $responseBuilder->key('value');
        $this->assertEquals(['key' => 'value'], $responseBuilder->build());
    }

    public function test_if_nested_items_can_be_added()
    {
        $responseBuilder = new ResponseBuilder;
        $responseBuilder->foo()
            ->nested()
            ->bar('value')
            ->endNested()
            ->endFoo();
        $this->assertEquals(['foo' => ['nested' => ['bar' => 'value']]], $responseBuilder->build());
    }

    public function test_if_items_can_be_added_to_existing_keys()
    {
        $responseBuilder = new ResponseBuilder;
        $responseBuilder->foo()
            ->bar('value')
            ->endFoo();
        $responseBuilder->foo()
            ->extraBar('extra_value')
            ->endFoo();
        $this->assertEquals(['foo' => ['bar' => 'value', 'extra_bar' => 'extra_value']], $responseBuilder->build());
    }
}
