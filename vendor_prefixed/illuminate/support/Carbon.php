<?php

namespace OtomatiesCoreVendor\Illuminate\Support;

use OtomatiesCoreVendor\Carbon\Carbon as BaseCarbon;
use OtomatiesCoreVendor\Carbon\CarbonImmutable as BaseCarbonImmutable;
use OtomatiesCoreVendor\Illuminate\Support\Traits\Conditionable;
use OtomatiesCoreVendor\Illuminate\Support\Traits\Dumpable;
use OtomatiesCoreVendor\Ramsey\Uuid\Uuid;
use OtomatiesCoreVendor\Symfony\Component\Uid\Ulid;
class Carbon extends BaseCarbon
{
    use Conditionable, Dumpable;
    /**
     * {@inheritdoc}
     */
    public static function setTestNow(mixed $testNow = null): void
    {
        BaseCarbon::setTestNow($testNow);
        BaseCarbonImmutable::setTestNow($testNow);
    }
    /**
     * Create a Carbon instance from a given ordered UUID or ULID.
     */
    public static function createFromId(Uuid|Ulid|string $id): static
    {
        if (is_string($id)) {
            $id = Ulid::isValid($id) ? Ulid::fromString($id) : Uuid::fromString($id);
        }
        return static::createFromInterface($id->getDateTime());
    }
    /**
     * Get the current date / time plus a given amount of time.
     */
    public function plus(int $years = 0, int $months = 0, int $weeks = 0, int $days = 0, int $hours = 0, int $minutes = 0, int $seconds = 0, int $microseconds = 0): static
    {
        return $this->add("\n            {$years} years {$months} months {$weeks} weeks {$days} days\n            {$hours} hours {$minutes} minutes {$seconds} seconds {$microseconds} microseconds\n        ");
    }
    /**
     * Get the current date / time minus a given amount of time.
     */
    public function minus(int $years = 0, int $months = 0, int $weeks = 0, int $days = 0, int $hours = 0, int $minutes = 0, int $seconds = 0, int $microseconds = 0): static
    {
        return $this->sub("\n            {$years} years {$months} months {$weeks} weeks {$days} days\n            {$hours} hours {$minutes} minutes {$seconds} seconds {$microseconds} microseconds\n        ");
    }
}
