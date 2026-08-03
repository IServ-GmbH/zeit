<?php

declare(strict_types=1);

namespace IServ\Library\Zeit\Tests\Unit;

use IServ\Library\Zeit\Date;
use IServ\Library\Zeit\Time;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Time::class)]
#[UsesClass(Date::class)]
final class TimeTest extends TestCase
{
    public static function provideInvalidParts(): iterable
    {
        yield 'negative hour' => [-1, 0, 0];
        yield '24 hour' => [24, 0, 0];
        yield 'negative minute' => [0, -2, 0];
        yield '60 minute' => [0, 60, 0];
        yield 'negative second' => [0, 0, -3];
        yield '60 second' => [0, 0, 60];
    }

    public function testCreate(): void
    {
        $time = new Time('10:29:05');

        $this->assertExpectedTime($time);
    }

    public function testCreateWithZeroSeconds(): void
    {
        $time = new Time('10:29:00');

        $this->assertSame('10:29:00', $time->getValue());
        $this->assertSame('10:29:00', (string)$time);
        $this->assertSame('10', $time->getHour());
        $this->assertSame('29', $time->getMinute());
        $this->assertSame('0', $time->getSecond());
        $this->assertSame('10:29:00', $time->toDateTime()->format('H:i:s'));
    }

    public function testCreateFailsOnBadFormat(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('$value must be in the format "HH:MM:SS"!');

        new Time('99:99:YO');
    }

    /**
     * @param string|int $hours
     * @param string|int $minutes
     * @param string|int $seconds
     */
    #[DataProvider('provideInvalidParts')]
    public function testCreateFailsOnBadData($hours, $minutes, $seconds): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Time(sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds));
    }

    public function testCreateFromStringParts(): void
    {
        $time = Time::fromParts('10', '29', '05');

        $this->assertExpectedTime($time);
    }

    public function testCreateFromIntParts(): void
    {
        $time = Time::fromParts(10, 29, 5);
        $this->assertExpectedTime($time);

        // Without seconds
        $time = Time::fromParts(10, 29);
        $this->assertSame('10:29:00', $time->getValue());
    }

    public function testCreateFromDateTime(): void
    {
        $time = Time::fromDateTime(new \DateTimeImmutable('2021-04-22 10:29:05'));

        $this->assertExpectedTime($time);
    }

    public function testWithDate(): void
    {
        $time = new Time('10:29:05');
        $date = new Date('2021-04-22');

        $this->assertSame('2021-04-22 10:29:05', $time->withDate($date)->format('Y-m-d H:i:s'));
    }

    /**
     * @param string|int $hours
     * @param string|int $minutes
     * @param string|int $seconds
     */
    #[DataProvider('provideInvalidParts')]
    public function testInvalidValues($hours, $minutes, $seconds): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Time::fromParts($hours, $minutes, $seconds);
    }

    private function assertExpectedTime(Time $time): void
    {
        $this->assertSame('10:29:05', $time->getValue());
        $this->assertSame('10:29:05', (string)$time);
        $this->assertSame('10', $time->getHour());
        $this->assertSame('29', $time->getMinute());
        $this->assertSame('5', $time->getSecond());
        $this->assertSame('10:29:05', $time->toDateTime()->format('H:i:s'));
    }
}
