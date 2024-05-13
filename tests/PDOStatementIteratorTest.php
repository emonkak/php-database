<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDOStatementInterface;
use Emonkak\Database\PDOStatementIterator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Emonkak\Database\PDOStatementIterator
 */
class PDOStatementIteratorTest extends TestCase
{
    private $stmt;

    private $iterator;

    public function setUp(): void
    {
        $this->stmt = $this->createMock(PDOStatementInterface::class);
        $this->iterator = new PDOStatementIterator($this->stmt);
    }

    public function testIterator(): void
    {
        $this->stmt
            ->expects($this->exactly(5))
            ->method('fetch')
            ->willReturnOnConsecutiveCalls(
                ['foo' => 1, 'bar' => 2],
                ['foo' => 3, 'bar' => 4],
                ['foo' => 5, 'bar' => 6],
                false,
                false
            );

        $expected = [
            ['foo' => 1, 'bar' => 2],
            ['foo' => 3, 'bar' => 4],
            ['foo' => 5, 'bar' => 6],
        ];

        $this->assertEquals($expected, iterator_to_array($this->iterator));
        $this->assertEquals([], iterator_to_array($this->iterator));
    }
}
