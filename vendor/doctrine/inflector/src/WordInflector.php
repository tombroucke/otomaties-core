<?php

declare (strict_types=1);
namespace Otomaties\Core\Doctrine\Inflector;

interface WordInflector
{
    public function inflect(string $word): string;
}
