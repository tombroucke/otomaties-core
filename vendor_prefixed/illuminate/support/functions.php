<?php

namespace OtomatiesCoreVendor\Illuminate\Support;

use OtomatiesCoreVendor\Carbon\CarbonInterface;
use OtomatiesCoreVendor\Carbon\CarbonInterval;
use OtomatiesCoreVendor\Illuminate\Support\Defer\DeferredCallback;
use OtomatiesCoreVendor\Illuminate\Support\Defer\DeferredCallbackCollection;
use OtomatiesCoreVendor\Illuminate\Support\Facades\Date;
use OtomatiesCoreVendor\Symfony\Component\Process\PhpExecutableFinder;
if (!\function_exists('OtomatiesCoreVendor\\Illuminate\\Support\\defer')) {
    /**
     * Defer execution of the given callback.
     *
     * @param  callable|null  $callback
     * @param  string|null  $name
     * @param  bool  $always
     * @return ($callback is null ? \Illuminate\Support\Defer\DeferredCallbackCollection : \Illuminate\Support\Defer\DeferredCallback)
     * @internal
     */
    function defer(?callable $callback = null, ?string $name = null, bool $always = \false) : DeferredCallback|DeferredCallbackCollection
    {
        if ($callback === null) {
            return app(DeferredCallbackCollection::class);
        }
        return tap(new DeferredCallback($callback, $name, $always), fn($deferred) => app(DeferredCallbackCollection::class)[] = $deferred);
    }
}
if (!\function_exists('OtomatiesCoreVendor\\Illuminate\\Support\\php_binary')) {
    /**
     * Determine the PHP Binary.
     * @internal
     */
    function php_binary() : string
    {
        return (new PhpExecutableFinder())->find(\false) ?: 'php';
    }
}
if (!\function_exists('OtomatiesCoreVendor\\Illuminate\\Support\\artisan_binary')) {
    /**
     * Determine the proper Artisan executable.
     * @internal
     */
    function artisan_binary() : string
    {
        return \defined('ARTISAN_BINARY') ? ARTISAN_BINARY : 'artisan';
    }
}
// Time functions...
if (!\function_exists('OtomatiesCoreVendor\\Illuminate\\Support\\now')) {
    /**
     * Create a new Carbon instance for the current time.
     *
     * @param  \DateTimeZone|\UnitEnum|string|null  $tz
     * @return \Illuminate\Support\Carbon
     * @internal
     */
    function now($tz = null) : CarbonInterface
    {
        return Date::now(enum_value($tz));
    }
}
if (!\function_exists('OtomatiesCoreVendor\\Illuminate\\Support\\microseconds')) {
    /**
     * Get the current date / time plus the given number of microseconds.
     * @internal
     */
    function microseconds(int $microseconds) : CarbonInterval
    {
        return CarbonInterval::microseconds($microseconds);
    }
}
if (!\function_exists('OtomatiesCoreVendor\\Illuminate\\Support\\milliseconds')) {
    /**
     * Get the current date / time plus the given number of milliseconds.
     * @internal
     */
    function milliseconds(int $milliseconds) : CarbonInterval
    {
        return CarbonInterval::milliseconds($milliseconds);
    }
}
if (!\function_exists('OtomatiesCoreVendor\\Illuminate\\Support\\seconds')) {
    /**
     * Get the current date / time plus the given number of seconds.
     * @internal
     */
    function seconds(int $seconds) : CarbonInterval
    {
        return CarbonInterval::seconds($seconds);
    }
}
if (!\function_exists('OtomatiesCoreVendor\\Illuminate\\Support\\minutes')) {
    /**
     * Get the current date / time plus the given number of minutes.
     * @internal
     */
    function minutes(int $minutes) : CarbonInterval
    {
        return CarbonInterval::minutes($minutes);
    }
}
if (!\function_exists('OtomatiesCoreVendor\\Illuminate\\Support\\hours')) {
    /**
     * Get the current date / time plus the given number of hours.
     * @internal
     */
    function hours(int $hours) : CarbonInterval
    {
        return CarbonInterval::hours($hours);
    }
}
if (!\function_exists('OtomatiesCoreVendor\\Illuminate\\Support\\days')) {
    /**
     * Get the current date / time plus the given number of days.
     * @internal
     */
    function days(int $days) : CarbonInterval
    {
        return CarbonInterval::days($days);
    }
}
if (!\function_exists('OtomatiesCoreVendor\\Illuminate\\Support\\weeks')) {
    /**
     * Get the current date / time plus the given number of weeks.
     * @internal
     */
    function weeks(int $weeks) : CarbonInterval
    {
        return CarbonInterval::weeks($weeks);
    }
}
if (!\function_exists('OtomatiesCoreVendor\\Illuminate\\Support\\months')) {
    /**
     * Get the current date / time plus the given number of months.
     * @internal
     */
    function months(int $months) : CarbonInterval
    {
        return CarbonInterval::months($months);
    }
}
if (!\function_exists('OtomatiesCoreVendor\\Illuminate\\Support\\years')) {
    /**
     * Get the current date / time plus the given number of years.
     * @internal
     */
    function years(int $years) : CarbonInterval
    {
        return CarbonInterval::years($years);
    }
}
