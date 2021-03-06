<?php

declare(strict_types=1);

namespace IServ\Library\Zeit;

final class Date implements Datetimeable, \Stringable
{
    private const BC = '-';

    private string $value;
    private string $year;
    private string $month;
    private string $day;

    public function __construct(string $value)
    {
        // Enforce basic format
        if (!preg_match('/^(-)?(\d\d\d\d)-(\d\d)-(\d\d)$/', $value, $parts)) {
            throw new \InvalidArgumentException(sprintf('$value must be in the format "YYYY-MM-DD" or "-YYYY-MM-DD"! Got "%s".', $value));
        }

        // Split into parts to ease handling
        $this->value = $value;
        [$bc, $this->year, $this->month, $this->day] = array_map(
            static function (string $in): string {
                return ltrim($in, '0');
            },
            array_slice($parts, 1)
        );

        if (self::BC === $bc) {
            $this->year = self::BC . $this->year;
        }

        // Finally some basic validation
        self::validateYear($this->year); // not that it'd ever trigger due to the regex ¯\_(ツ)_/¯
        self::validateMonth($this->month);
        self::validateDay($this->day);
    }

    public static function fromParts(int|string $year, int|string $month, int|string $day): self
    {
        // We duplicate the validation here to assist with better messages which would otherwise
        // be mapped to the generic "bad format" check.
        self::validateYear($year);
        self::validateMonth($month);
        self::validateDay($day);

        // Use AC year with manual minus sign to ease clean formatting
        $isBC = false;
        if ((int)$year < 0) {
            $year = (int)$year * -1;
            $isBC = true;
        }

        return new self(sprintf('%s%04d-%02d-%02d', $isBC ? self::BC : '', $year, $month, $day));
    }

    public static function fromDateTime(\DateTimeInterface $date): self
    {
        return new self($date->format('Y-m-d'));
    }

    /**
     * {@inheritDoc}
     */
    public function toDateTime(): \DateTimeImmutable
    {
        return $this->withTime();
    }

    /**
     * Converts the Date to a DateTimeImmutable object. Can be combined with a Time.
     */
    public function withTime(?Time $time = null): \DateTimeImmutable
    {
        $value = $this->value;
        if (null !== $time) {
            $value .= ' ' . $time->getValue();
        }

        try {
            return new \DateTimeImmutable($value);
        } catch (\Exception $e) {
            throw new \RuntimeException('Could not convert to DateTime!', previous: $e);
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

    public function getYear(): string
    {
        return $this->year;
    }

    public function getMonth(): string
    {
        return $this->month;
    }

    public function getDay(): string
    {
        return $this->day;
    }

    private static function validateYear(int|string $year): void
    {
        if ((int)$year < -9999 || (int)$year > 9999) {
            throw new \InvalidArgumentException(sprintf('Cannot handle the given year: %s', $year));
        }
    }

    private static function validateMonth(int|string $month): void
    {
        if ((int)$month < 1 || (int)$month > 12) {
            throw new \InvalidArgumentException(sprintf('Invalid value for month given: %s', $month));
        }
    }

    private static function validateDay(int|string $day): void
    {
        if ((int)$day < 1 || (int)$day > 31) {
            throw new \InvalidArgumentException(sprintf('Invalid value for day given: %s', $day));
        }
    }
}
