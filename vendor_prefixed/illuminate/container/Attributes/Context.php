<?php

namespace OtomatiesCoreVendor\Illuminate\Container\Attributes;

use Attribute;
use OtomatiesCoreVendor\Illuminate\Contracts\Container\Container;
use OtomatiesCoreVendor\Illuminate\Contracts\Container\ContextualAttribute;
use OtomatiesCoreVendor\Illuminate\Log\Context\Repository;
/** @internal */
#[Attribute(Attribute::TARGET_PARAMETER)]
class Context implements ContextualAttribute
{
    /**
     * Create a new attribute instance.
     */
    public function __construct(public string $key, public mixed $default = null, public bool $hidden = \false)
    {
    }
    /**
     * Resolve the context value.
     *
     * @param  self  $attribute
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return mixed
     */
    public static function resolve(self $attribute, Container $container) : mixed
    {
        $repository = $container->make(Repository::class);
        return match ($attribute->hidden) {
            \true => $repository->getHidden($attribute->key, $attribute->default),
            \false => $repository->get($attribute->key, $attribute->default),
        };
    }
}
