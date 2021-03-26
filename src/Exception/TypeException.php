<?php

declare(strict_types=1);

namespace IServ\Library\Zeit\Exception;

final class TypeException extends \InvalidArgumentException
{
    /**
     * Creates an invalid argument exception
     *
     * @param mixed $given
     * @param string|string[] $expected
     * @param string|null $name
     */
    public static function invalid($given, $expected, ?string $name = null): self
    {
        // Psalm cannot handle the polyfill (https://github.com/vimeo/psalm/issues/4773)
        //$got = get_debug_type($given);
        $got = is_object($given) ? get_class($given) : gettype($given);
        $want = is_array($expected) ? implode(', ', $expected) : $expected;

        if (null !== $name) {
            return new self(sprintf('Invalid argument for %s: Got %s but %s expected.', $name, $got, $want));
        }

        return new self(sprintf('Invalid argument: Got %s but %s expected.', $got, $want));
    }
}
