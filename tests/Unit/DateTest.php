<?php

declare(strict_types=1);

namespace IServ\Library\Zeit\Tests\Unit;

use IServ\Library\Zeit\Date;
use IServ\Library\Zeit\Time;
use PHPUnit\Framework\TestCase;

/**
 * @covers \IServ\Library\Zeit\Date
 * @uses \IServ\Library\Zeit\Time
 */
final class DateTest extends TestCase
{
    public function testCreate(): void
    {
        $date = new Date('2021-04-22');

        $this->assertExpectedDate($date);
    }

    public function testCreateBC(): void
    {
        $date = Date::fromParts(-21, 4, 22);

        $this->assertSame('-0021-04-22', $date->getValue());
        $this->assertSame('-0021-04-22', (string)$date);
        $this->assertSame('-21', $date->getYear());
        $this->assertSame('4', $date->getMonth());
        $this->assertSame('22', $date->getDay());
        $this->assertSame('-0021-04-22', $date->toDateTime()->format('Y-m-d'));
    }

    public function testCreateFormStringParts(): void
    {
        $date = Date::fromParts('2021', '4', '22');

        $this->assertExpectedDate($date);
    }

    public function testCreateFormIntParts(): void
    {
        $date = Date::fromParts(2021, 4, 22);

        $this->assertExpectedDate($date);
    }

    public function testCreateFormDateTime(): void
    {
        $date = Date::fromDateTime(new \DateTimeImmutable('2021-04-22 10:29:05'));

        $this->assertExpectedDate($date);
    }

    public function testWithDate(): void
    {
        $date = new Date('2021-04-22');
        $time = new Time('10:29:05');

        $this->assertSame('2021-04-22 10:29:05', $date->withTime($time)->format('Y-m-d H:i:s'));
    }

    public function testInvalidValues(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Date::fromParts(1, 13, 5);
    }

    private function assertExpectedDate(Date $date): void
    {
        $this->assertSame('2021-04-22', $date->getValue());
        $this->assertSame('2021-04-22', (string)$date);
        $this->assertSame('2021', $date->getYear());
        $this->assertSame('4', $date->getMonth());
        $this->assertSame('22', $date->getDay());
        $this->assertSame('2021-04-22', $date->toDateTime()->format('Y-m-d'));
    }
}
