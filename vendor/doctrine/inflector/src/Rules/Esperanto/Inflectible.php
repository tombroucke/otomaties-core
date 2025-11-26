<?php

declare (strict_types=1);
namespace Otomaties\Core\Doctrine\Inflector\Rules\Esperanto;

use Otomaties\Core\Doctrine\Inflector\Rules\Pattern;
use Otomaties\Core\Doctrine\Inflector\Rules\Substitution;
use Otomaties\Core\Doctrine\Inflector\Rules\Transformation;
use Otomaties\Core\Doctrine\Inflector\Rules\Word;
class Inflectible
{
    /** @return Transformation[] */
    public static function getSingular(): iterable
    {
        yield new Transformation(new Pattern('oj$'), 'o');
    }
    /** @return Transformation[] */
    public static function getPlural(): iterable
    {
        yield new Transformation(new Pattern('o$'), 'oj');
    }
    /** @return Substitution[] */
    public static function getIrregular(): iterable
    {
        yield new Substitution(new Word(''), new Word(''));
    }
}
