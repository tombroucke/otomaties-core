<?php

declare (strict_types=1);
namespace OtomatiesCoreVendor\Carbon\Doctrine;

use OtomatiesCoreVendor\Carbon\Carbon;
use DateTime;
use OtomatiesCoreVendor\Doctrine\DBAL\Platforms\AbstractPlatform;
use OtomatiesCoreVendor\Doctrine\DBAL\Types\VarDateTimeType;
class DateTimeType extends VarDateTimeType implements CarbonDoctrineType
{
    /** @use CarbonTypeConverter<Carbon> */
    use CarbonTypeConverter;
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Carbon
    {
        return $this->doConvertToPHPValue($value);
    }
}
