<?php

declare (strict_types=1);
namespace OtomatiesCoreVendor\Doctrine\Inflector;

/** @internal */
interface WordInflector
{
    public function inflect(string $word) : string;
}
