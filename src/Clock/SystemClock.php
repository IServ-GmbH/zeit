<?php

declare(strict_types=1);

namespace IServ\Library\Zeit\Clock;

/**
 * @api
 */
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
    #[\Override]
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function usleep(int $microseconds): void
    {
        usleep($microseconds);
    }
}
