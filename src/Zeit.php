<?php

declare(strict_types=1);

namespace IServ\Library\Zeit;

use IServ\Library\Zeit\Clock\Clock;
use IServ\Library\Zeit\Clock\SystemClock;
use IServ\Library\Zeit\Exception\TypeException;

/**
 * Time and date factory utils.
 *
 * Yes! It's called "Zeit" with full intent to get a catchy short name without conflicting with existing classes.
 */
final class Zeit
{
    /**
     * @var Clock|null
     */
    private static $clock;

    /**
     * Gets now
     */
    public static function now(): \DateTimeImmutable
    {
        return self::clock()->getTime();
    }

    /**
     * Gets now as UTC
     */
    public static function nowUtc(): \DateTimeImmutable
    {
        return self::clock()->getTime()->setTimezone(new \DateTimeZone('+0000'));
    }

    /**
     * Gets the current date
     */
    public static function date(): Date
    {
        return Date::fromDateTime(self::now());
    }

    /**
     * Gets the current time
     */
    public static function time(): Time
    {
        return Time::fromDateTime(self::now());
    }

    /**
     * Creates a date from given $time input
     */
    public static function create(string $time): \DateTimeImmutable
    {
        try {
            return new \DateTimeImmutable($time);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Could not convert time to object!', 0, $e);
        }
    }

    /**
     * Creates a date from given unix timestamp in seconds
     *
     * @param int|string $timestamp
     *
     * @psalm-suppress DocblockTypeContradiction
     */
    public static function createFromTimestamp($timestamp): \DateTimeImmutable
    {
        if (!is_int($timestamp) && !is_string($timestamp)) {
            throw TypeException::invalid($timestamp, ['int', 'string']);
        }

        try {
            return new \DateTimeImmutable('@' . $timestamp);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Could not convert time to object!', 0, $e);
        }
    }

    /**
     * Allows to set a custom clock (for testing)Möchten w
     */
    public static function setClock(Clock $clock): void
    {
        self::$clock = $clock;
    }

    /**
     * Our white rabbit
     */
    private static function clock(): Clock
    {
        if (null === self::$clock) {
            self::$clock = SystemClock::create();
        }

        return self::$clock;
    }
}
