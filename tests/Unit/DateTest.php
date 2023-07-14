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
    public function provideInvalidParts(): iterable
    {
        yield 'way too early' => [-10000, 1, 1];
        yield 'way too far' => [10000, 1, 1];
        yield 'bad month 0' => [2000, 0, 1];
        yield 'bad month 13' => [2000, 13, 1];
        yield 'bad day 0' => [2000, 1, 0];
        yield 'bad day 32' => [2000, 1, 32];
    }

    public function testCreate(): void
    {
        $date = new Date('2021-04-22');

        $this->assertExpectedDate($date);
    }

    public function testCreateFailsOnBadFormat(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('$value must be in the format "YYYY-MM-DD" or "-YYYY-MM-DD"! Got ');

        new Date('9999-99-YO');
    }

    /**
     * @dataProvider provideInvalidParts
     *
     * @param string|int $year
     * @param string|int $month
     * @param string|int $day
     */
    public function testCreateFailsOnBadData($year, $month, $day): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Date(sprintf('%s-%02d-%02d', $year, $month, $day));
    }

    public function testCreateBc(): void
    {
        $date = Date::fromParts(-21, 4, 22);

        $this->assertSame('-0021-04-22', $date->getValue());
        $this->assertSame('-0021-04-22', (string)$date);
        $this->assertSame('-21', $date->getYear());
        $this->assertSame('4', $date->getMonth());
        $this->assertSame('22', $date->getDay());
        $this->assertSame('-0021-04-22', $date->toDateTime()->format('Y-m-d'));
    }

    public function testCreateFromStringParts(): void
    {
        $date = Date::fromParts('2021', '4', '22');

        $this->assertExpectedDate($date);
    }

    public function testCreateFromIntParts(): void
    {
        $date = Date::fromParts(2021, 4, 22);

        $this->assertExpectedDate($date);
    }

    public function testCreateFromDateTime(): void
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

    /**
     * @dataProvider provideInvalidParts
     *
     * @param string|int $year
     * @param string|int $month
     * @param string|int $day
     */
    public function testInvalidValues($year, $month, $day): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Date::fromParts($year, $month, $day);
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
