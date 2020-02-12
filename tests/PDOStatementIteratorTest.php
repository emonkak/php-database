<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDOStatementInterface;
use Emonkak\Database\PDOStatementIterator;
use PHPUnit\Framework\TestCase;

/**
 * @covers Emonkak\Database\PDOStatementIterator
 */
class PDOStatementIteratorTest extends TestCase
{
    private $stmt;

    private $iterator;

    public function setUp()
    {
        $this->stmt = $this->createMock(PDOStatementInterface::class);
        $this->iterator = new PDOStatementIterator($this->stmt);
    }

    public function test()
    {
        $this->stmt
            ->expects($this->at(0))
            ->method('fetch')
            ->willReturn(['foo' => 1, 'bar' => 2]);
        $this->stmt
            ->expects($this->at(1))
            ->method('fetch')
            ->willReturn(['foo' => 3, 'bar' => 4]);
        $this->stmt
            ->expects($this->at(2))
            ->method('fetch')
            ->willReturn(['foo' => 5, 'bar' => 6]);
        $this->stmt
            ->expects($this->any())
            ->method('fetch')
            ->willReturn(false);

        $expected = [
            ['foo' => 1, 'bar' => 2],
            ['foo' => 3, 'bar' => 4],
            ['foo' => 5, 'bar' => 6],
        ];

        $this->assertEquals($expected, iterator_to_array($this->iterator));
        $this->assertEquals([], iterator_to_array($this->iterator));
    }
}
