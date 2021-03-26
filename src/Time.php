<?php

declare(strict_types=1);

namespace IServ\Library\Zeit;

final class Time implements Datetimeable, \Stringable
{
    /** @var string */
    private $value;

    /** @var string */
    private $hour;

    /** @var string */
    private $minute;

    /** @var string */
    private $second;

    public function __construct(string $value)
    {
        if (!preg_match('/^(\d\d):(\d\d):(\d\d)$/', $value, $parts)) {
            throw new \InvalidArgumentException('$value must be in the format "HH:MM:SS"!');
        }

        $this->value = $value;
        [$this->hour, $this->minute, $this->second] = array_map(
            static function (string $in): string {
                return ltrim($in, '0');
            },
            array_slice($parts, 1)
        );
    }

    /**
     * @param string|int $hours
     * @param string|int $minutes
     * @param string|int $seconds
     */
    public static function fromParts($hours, $minutes, $seconds = 0): self
    {
        if ((int)$hours < 0 || (int)$hours > 24) {
            throw new \InvalidArgumentException(sprintf('Invalid value for hour given: %s', $hours));
        }

        if ((int)$minutes < 0 || (int)$minutes > 59) {
            throw new \InvalidArgumentException(sprintf('Invalid value for minute given: %s', $minutes));
        }

        if ((int)$seconds < 0 || (int)$seconds > 59) {
            throw new \InvalidArgumentException(sprintf('Invalid value for second given: %s', $seconds));
        }

        return new self(sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds));
    }

    public static function fromDateTime(\DateTimeInterface $date): self
    {
        return new self($date->format('H:i:s'));
    }

    /**
     * {@inheritDoc}
     */
    public function toDateTime(): \DateTimeImmutable
    {
        return $this->withDate();
    }

    /**
     * Converts the Time to a DateTimeImmutable object. Can be combined with a Date.
     */
    public function withDate(?Date $date = null): \DateTimeImmutable
    {
        $value = $this->value;
        if (null !== $date) {
            $value = $date->getValue() . ' ' . $value;
        }

        try {
            return new \DateTimeImmutable($value);
        } catch (\Exception $e) {
            throw new \RuntimeException('Could not convert to DateTime!', 0, $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getHour(): string
    {
        return $this->hour;
    }

    public function getMinute(): string
    {
        return $this->minute;
    }

    public function getSecond(): string
    {
        return $this->second;
    }
}
