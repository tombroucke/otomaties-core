<?php

declare (strict_types=1);
namespace Otomaties\Core\Doctrine\Inflector\Rules\Turkish;

use Otomaties\Core\Doctrine\Inflector\Rules\Patterns;
use Otomaties\Core\Doctrine\Inflector\Rules\Ruleset;
use Otomaties\Core\Doctrine\Inflector\Rules\Substitutions;
use Otomaties\Core\Doctrine\Inflector\Rules\Transformations;
final class Rules
{
    public static function getSingularRuleset(): Ruleset
    {
        return new Ruleset(new Transformations(...Inflectible::getSingular()), new Patterns(...Uninflected::getSingular()), (new Substitutions(...Inflectible::getIrregular()))->getFlippedSubstitutions());
    }
    public static function getPluralRuleset(): Ruleset
    {
        return new Ruleset(new Transformations(...Inflectible::getPlural()), new Patterns(...Uninflected::getPlural()), new Substitutions(...Inflectible::getIrregular()));
    }
}
