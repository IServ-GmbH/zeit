<?php

declare(strict_types=1);

namespace IServ\Library\Zeit\Tests\Unit;

use IServ\Library\Zeit\Date;
use IServ\Library\Zeit\Time;
use PHPUnit\Framework\TestCase;

/**
 * @covers \IServ\Library\Zeit\Time
 * @uses \IServ\Library\Zeit\Date
 */
final class TimeTest extends TestCase
{
    public function testCreate(): void
    {
        $time = new Time('10:29:05');

        $this->assertExpectedTime($time);
    }

    public function testCreateFormStringParts(): void
    {
        $time = Time::fromParts('10', '29', '05');

        $this->assertExpectedTime($time);
    }

    public function testCreateFormIntParts(): void
    {
        $time = Time::fromParts(10, 29, 5);

        $this->assertExpectedTime($time);
    }

    public function testCreateFormDateTime(): void
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

    public function testInvalidValues(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Time::fromParts(25, 26, 27);
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
