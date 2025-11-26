<?php

declare (strict_types=1);
namespace Otomaties\Core\Doctrine\Inflector;

class NoopWordInflector implements WordInflector
{
    public function inflect(string $word): string
    {
        return $word;
    }
}
