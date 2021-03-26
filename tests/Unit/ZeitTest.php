<?php

declare(strict_types=1);

namespace IServ\Library\Zeit\Tests\Unit;

use IServ\Library\Zeit\Clock\FixedClock;
use IServ\Library\Zeit\Clock\SystemClock;
use IServ\Library\Zeit\Zeit;
use PHPUnit\Framework\TestCase;

/**
 * @covers \IServ\Library\Zeit\Zeit
 * @uses \IServ\Library\Zeit\Date
 * @uses \IServ\Library\Zeit\Time
 * @uses \IServ\Library\Zeit\Clock\FixedClock
 * @uses \IServ\Library\Zeit\Clock\SystemClock
 */
final class ZeitTest extends TestCase
{
    private const DESIRED_TIME = '2019-07-29T11:47:23+02:00';

    public function testNowWithFixedClock(): void
    {
        Zeit::setClock($clock = FixedClock::fromString(self::DESIRED_TIME));

        $now = Zeit::now();
        $this->assertEquals($clock->getTime(), $now);
        $this->assertEquals($clock->getTime()->format('c'), $now->format('c'));

        $nowUtc = Zeit::nowUtc();
        $this->assertEquals('2019-07-29T09:47:23+00:00', $nowUtc->format('c'));

        $date = Zeit::date();
        $this->assertEquals('2019-07-29', $date->getValue());

        $time = Zeit::time();
        $this->assertEquals('11:47:23', $time->getValue());
    }

    public function testNowDoesNotChangeWithFixedClock(): void
    {
        Zeit::setClock($clock = FixedClock::fromString(self::DESIRED_TIME));
        $time = $clock->getTime();

        $now = Zeit::now();
        $this->assertEquals($time, $now);

        usleep(50);

        $now = Zeit::now();
        $this->assertEquals($time, $now);
    }

    public function testNowDoesChangeWithDefaultClock(): void
    {
        Zeit::setClock($clock = SystemClock::create());
        $time = $clock->getTime();

        $now = Zeit::now();
        $this->assertNotEquals($time, $now);

        usleep(50);

        $now = Zeit::now();
        $this->assertNotEquals($time, $now);
    }

    public function testCreate(): void
    {
        $dateTime = Zeit::create(self::DESIRED_TIME);

        $this->assertSame(self::DESIRED_TIME, $dateTime->format('c'));
    }

    public function testCreateFromTimestamp(): void
    {
        $dateTime = Zeit::createFromTimestamp(1564393643);

        $this->assertSame(1564393643, $dateTime->getTimestamp());
        $this->assertSame('2019-07-29T09:47:23+00:00', $dateTime->format('c'));
    }
}
