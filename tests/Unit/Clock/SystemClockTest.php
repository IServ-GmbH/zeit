<?php

declare(strict_types=1);

namespace IServ\Library\Zeit\Tests\Unit\Clock;

use IServ\Library\Zeit\Clock\SystemClock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SystemClock::class)]
final class SystemClockTest extends TestCase
{
    public function testCreate(): void
    {
        $now = new \DateTimeImmutable();
        $clock = SystemClock::create();

        $this->assertGreaterThanOrEqual($now, $clock->now());
    }

    public function testSleep(): void
    {
        $clock = SystemClock::create();
        $time = $clock->now();

        $clock->usleep(1);

        $this->assertGreaterThanOrEqual($time, $clock->now());
    }
}
