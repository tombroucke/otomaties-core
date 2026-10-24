<?php

declare (strict_types=1);
namespace OtomatiesCoreVendor\Carbon\Doctrine;

use OtomatiesCoreVendor\Carbon\CarbonInterface;
use OtomatiesCoreVendor\Doctrine\DBAL\Platforms\AbstractPlatform;
/** @internal */
interface CarbonDoctrineType
{
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform) : string;
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform) : ?CarbonInterface;
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform) : ?string;
}
