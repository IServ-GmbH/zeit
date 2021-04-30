<?php

declare(strict_types=1);

namespace IServ\Library\Zeit\Clock;

/**
 * Clock implementation for testing. Returns the *same* time for all calls!
 */
final class FixedClock implements Clock
{
    /** @var \DateTimeImmutable */
    private $time;

    /** @var \DateTimeImmutable */
    private $originalTime;

    private function __construct(\DateTimeImmutable $time)
    {
        $this->time = $time;
        $this->originalTime = clone $time;
    }

    /**
     * Creates a clock from the given DateTime
     */
    public static function fromDateTime(\DateTimeImmutable $time): self
    {
        return new self($time);
    }

    /**
     * Creates a clock from by parsing the given string as DateTime
     */
    public static function fromString(string $time): self
    {
        try {
            return new self(new \DateTimeImmutable($time));
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Could not create time from string!', 0, $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function now(): \DateTimeImmutable
    {
        return $this->time;
    }

    /**
     * {@inheritDoc}
     */
    public function usleep(int $microseconds): void
    {
        if ($microseconds < 0) {
            throw new \InvalidArgumentException('$microseconds must be greater than or equal to zero!');
        }

        $this->time = $this->time->modify(sprintf('+%d microseconds', $microseconds));
    }

    /**
     * Allows to reset the fixed clock to its initial value.
     */
    public function reset(): void
    {
        $this->time = clone $this->originalTime;
    }
}
