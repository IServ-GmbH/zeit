<?php

declare(strict_types=1);

namespace IServ\Library\Zeit\Clock;

final class SystemClock implements Clock
{
    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }

    /**
     * {@inheritDoc}
     */
    public function now(): \DateTimeImmutable
    {
        try {
            return new \DateTimeImmutable();
        } catch (\Exception $e) {
            throw new \RuntimeException('Could not create time object!', 0, $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function usleep(int $microseconds): void
    {
        if ($microseconds < 0) {
            throw new \InvalidArgumentException('$microseconds must be greater than or equal to zero!');
        }

        usleep($microseconds);
    }
}
