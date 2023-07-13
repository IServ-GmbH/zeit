<?php

declare(strict_types=1);

namespace IServ\Library\Zeit;

interface Datetimeable
{
    /**
     * Convert to object to a DateTime representation.
     *
     * Please be aware that not all implementing classes may have a proper handling if it's only a date or a time value.
     */
    public function toDateTime(): \DateTimeImmutable;
}
