<?php

declare (strict_types=1);
namespace Otomaties\Core\Carbon\Doctrine;

use Otomaties\Core\Carbon\Carbon;
use DateTime;
use Otomaties\Core\Doctrine\DBAL\Platforms\AbstractPlatform;
use Otomaties\Core\Doctrine\DBAL\Types\VarDateTimeType;
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
