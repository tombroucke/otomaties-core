<?php

declare (strict_types=1);
namespace Otomaties\Core\Doctrine\Inflector\Rules\NorwegianBokmal;

use Otomaties\Core\Doctrine\Inflector\GenericLanguageInflectorFactory;
use Otomaties\Core\Doctrine\Inflector\Rules\Ruleset;
final class InflectorFactory extends GenericLanguageInflectorFactory
{
    protected function getSingularRuleset(): Ruleset
    {
        return Rules::getSingularRuleset();
    }
    protected function getPluralRuleset(): Ruleset
    {
        return Rules::getPluralRuleset();
    }
}
