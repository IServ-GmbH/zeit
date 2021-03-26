<?php

declare(strict_types=1);

namespace IServ\Library\Zeit\Clock;

interface Clock
{
    /**
     * Gets a current time instance.
     */
    public function getTime(): \DateTimeImmutable;

    /**
     * Sleep for x microseconds.
     */
    public function usleep(int $microseconds): void;
}
