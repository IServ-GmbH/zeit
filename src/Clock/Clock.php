<?php

declare(strict_types=1);

namespace IServ\Library\Zeit\Clock;

interface Clock
{
    /**
     * Gets a current DateTimeImmutable instance.
     */
    public function now(): \DateTimeImmutable;

    /**
     * Sleep for x microseconds.
     */
    public function usleep(int $microseconds): void;
}
