<?php

declare (strict_types=1);
namespace OtomatiesCoreVendor\Doctrine\Inflector\Rules\Turkish;

use OtomatiesCoreVendor\Doctrine\Inflector\GenericLanguageInflectorFactory;
use OtomatiesCoreVendor\Doctrine\Inflector\Rules\Ruleset;
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
