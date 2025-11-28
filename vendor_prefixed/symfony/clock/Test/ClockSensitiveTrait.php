<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace OtomatiesCoreVendor\Symfony\Component\Clock\Test;

use OtomatiesCoreVendor\PHPUnit\Framework\Attributes\After;
use OtomatiesCoreVendor\PHPUnit\Framework\Attributes\Before;
use OtomatiesCoreVendor\PHPUnit\Framework\Attributes\BeforeClass;
use OtomatiesCoreVendor\Symfony\Component\Clock\Clock;
use OtomatiesCoreVendor\Symfony\Component\Clock\ClockInterface;
use OtomatiesCoreVendor\Symfony\Component\Clock\MockClock;
use function OtomatiesCoreVendor\Symfony\Component\Clock\now;
/**
 * Helps with mocking the time in your test cases.
 *
 * This trait provides one self::mockTime() method that freezes the time.
 * It restores the global clock after each test case.
 * self::mockTime() accepts either a string (eg '+1 days' or '2022-12-22'),
 * a DateTimeImmutable, or a boolean (to freeze/restore the global clock).
 *
 * @author Nicolas Grekas <p@tchwork.com>
 * @internal
 */
trait ClockSensitiveTrait
{
    public static function mockTime(string|\DateTimeImmutable|bool $when = \true) : ClockInterface
    {
        Clock::set(match (\true) {
            \false === $when => self::saveClockBeforeTest(\false),
            \true === $when => new MockClock(),
            $when instanceof \DateTimeImmutable => new MockClock($when),
            default => new MockClock(now($when)),
        });
        return Clock::get();
    }
    /**
     * @beforeClass
     *
     * @before
     *
     * @internal
     */
    #[Before]
    #[BeforeClass]
    public static function saveClockBeforeTest(bool $save = \true) : ClockInterface
    {
        static $originalClock;
        if ($save && $originalClock) {
            self::restoreClockAfterTest();
        }
        return $save ? $originalClock = Clock::get() : $originalClock;
    }
    /**
     * @after
     *
     * @internal
     */
    #[After]
    protected static function restoreClockAfterTest() : void
    {
        Clock::set(self::saveClockBeforeTest(\false));
    }
}
