<?php

declare(strict_types=1);

namespace IServ\Library\Zeit\Tests\Unit\Exception;

use IServ\Library\Zeit\Exception\TypeException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \IServ\Library\Zeit\Exception\TypeException
 */
final class TypeExceptionTest extends TestCase
{
    public function provideEvilData(): iterable
    {
        yield [5, 'string', null, 'Invalid argument: Got integer but string expected.'];
        yield ['bob', ['string', 'integer'], null, 'Invalid argument: Got string but string, integer expected.'];
        yield [new \DateTimeImmutable(), ['string', \DateTime::class], null, 'Invalid argument: Got DateTimeImmutable but string, DateTime expected.'];
        yield ['apple', 'banana', '$peach', 'Invalid argument for $peach: Got string but banana expected.'];
    }

    /**
     * @dataProvider provideEvilData
     */
    public function testCreate($given, $want, ?string $name, string $expecetedMessage): void
    {
        $ex = TypeException::invalid($given, $want, $name);

        $this->assertSame($expecetedMessage, $ex->getMessage());
        $this->assertSame(0, $ex->getCode());
    }
}
