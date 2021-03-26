<?php

declare(strict_types=1);

namespace IServ\Library\Zeit\Tests;

use IServ\Library\Zeit\TestClass;
use PHPUnit\Framework\TestCase;

/**
 * @covers \IServ\Library\Zeit\TestClass
 */
final class TestTest extends TestCase
{
    public function testTest(): void
    {
        $class = new TestClass();
        $this->assertTrue($class->returnsTrue());
    }
}
