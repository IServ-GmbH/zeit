<?php

declare(strict_types=1);

namespace IServ\Library\Zeit\Clock;

use Psr\Clock\ClockInterface;

interface Clock extends ClockInterface
{
    /**
     * Sleep for x microseconds.
     *
     * @psalm-param 0|positive-int $microseconds
     */
    public function usleep(int $microseconds): void;
}
