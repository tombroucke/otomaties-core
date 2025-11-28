<?php

declare (strict_types=1);
namespace OtomatiesCoreVendor\Doctrine\Inflector\Rules\Portuguese;

use OtomatiesCoreVendor\Doctrine\Inflector\Rules\Pattern;
/** @internal */
final class Uninflected
{
    /** @return Pattern[] */
    public static function getSingular() : iterable
    {
        yield from self::getDefault();
    }
    /** @return Pattern[] */
    public static function getPlural() : iterable
    {
        yield from self::getDefault();
    }
    /** @return Pattern[] */
    private static function getDefault() : iterable
    {
        (yield new Pattern('tórax'));
        (yield new Pattern('tênis'));
        (yield new Pattern('ônibus'));
        (yield new Pattern('lápis'));
        (yield new Pattern('fênix'));
    }
}
