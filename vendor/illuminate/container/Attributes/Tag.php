<?php

declare (strict_types=1);
namespace Otomaties\Core\Illuminate\Container\Attributes;

use Attribute;
use Otomaties\Core\Illuminate\Contracts\Container\Container;
use Otomaties\Core\Illuminate\Contracts\Container\ContextualAttribute;
#[Attribute(Attribute::TARGET_PARAMETER)]
final class Tag implements ContextualAttribute
{
    public function __construct(public string $tag)
    {
    }
    /**
     * Resolve the tag.
     *
     * @param  self  $attribute
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return mixed
     */
    public static function resolve(self $attribute, Container $container)
    {
        return $container->tagged($attribute->tag);
    }
}
