<?php

declare(strict_types=1);

namespace IServ\Library\Zeit\Tests\Unit\Clock;

use IServ\Library\Zeit\Clock\FixedClock;
use PHPUnit\Framework\TestCase;

/**
 * @covers \IServ\Library\Zeit\Clock\FixedClock
 */
final class FixedClockTest extends TestCase
{
    private const DESIRED_TIME = '2019-07-29 11:47:23.0 +02:00';

    public function testFromDateTime(): void
    {
        $date = new \DateTimeImmutable(self::DESIRED_TIME);
        $clock = FixedClock::fromDateTime($date);

        $this->assertEquals($date, $clock->now());
    }

    public function testFromString(): void
    {
        $date = new \DateTimeImmutable(self::DESIRED_TIME);
        $clock = FixedClock::fromString(self::DESIRED_TIME);

        $this->assertEquals($date, $clock->now());

        $this->expectException(\InvalidArgumentException::class);
        FixedClock::fromString('Yo, I am no valid time!');
    }

    public function testTimeDoesNotChange(): void
    {
        $date = new \DateTimeImmutable(self::DESIRED_TIME);
        $clock = FixedClock::fromDateTime($date);

        $this->assertEquals($date, $clock->now());

        usleep(5);

        $this->assertEquals($date, $clock->now());
    }

    public function testSleep(): void
    {
        $clock = FixedClock::fromDateTime(new \DateTimeImmutable('2020-05-22'));

        $this->assertEquals('22.05.2020 00:00:00.000000', $clock->now()->format('d.m.Y H:i:s.u'));

        $clock->usleep(1);
        $this->assertEquals('22.05.2020 00:00:00.000001', $clock->now()->format('d.m.Y H:i:s.u'));

        $clock->usleep(1000000);
        $this->assertEquals('22.05.2020 00:00:01.000001', $clock->now()->format('d.m.Y H:i:s.u'));

        $clock->reset();
        $this->assertEquals('22.05.2020 00:00:00.000000', $clock->now()->format('d.m.Y H:i:s.u'));

        $this->expectException(\InvalidArgumentException::class);
        $clock->usleep(-1);
    }
}
