<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\ListenableStatement;
use Emonkak\Database\PDOInterface;
use Emonkak\Database\PDOListenerInterface;
use Emonkak\Database\PDOStatementInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Emonkak\Database\ListenableStatement
 */
class ListenableStatementTest extends TestCase
{
    public function testDelegate()
    {
        $queryString = 'SELECT 123 AS foo';

        $pdo = $this->createMock(PDOInterface::class);
        $listener = $this->createMock(PDOListenerInterface::class);
        $delegate = $this->createMock(PDOStatementInterface::class);
        $delegate
            ->expects($this->once())
            ->method('errorCode')
            ->willReturn(123);
        $delegate
            ->expects($this->once())
            ->method('errorInfo')
            ->willReturn(['HY000', 1, 'error']);
        $delegate
            ->expects($this->once())
            ->method('fetch')
            ->with(\PDO::FETCH_CLASS)
            ->willReturn((object) ['foo' => 123]);
        $delegate
            ->expects($this->once())
            ->method('fetchAll')
            ->with(\PDO::FETCH_CLASS, stdClass::class, [])
            ->willReturn([(object) ['foo' => 123]]);
        $delegate
            ->expects($this->once())
            ->method('fetchColumn')
            ->with(0)
            ->willReturn(123);
        $delegate
            ->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);
        $delegate
            ->expects($this->once())
            ->method('setFetchMode')
            ->with(\PDO::FETCH_CLASS, stdClass::class, [])
            ->willReturn(true);

        $stmt = new ListenableStatement($pdo, [$listener], $delegate, $queryString);

        $this->assertSame($delegate, $stmt->getIterator());
        $this->assertSame(123, $stmt->errorCode());
        $this->assertEquals(['HY000', 1, 'error'], $stmt->errorInfo());
        $this->assertEquals((object) ['foo' => 123], $stmt->fetch(\PDO::FETCH_CLASS));
        $this->assertEquals([(object) ['foo' => 123]], $stmt->fetchAll(\PDO::FETCH_CLASS, stdClass::class, []));
        $this->assertSame(123, $stmt->fetchColumn(0));
        $this->assertSame(1, $stmt->rowCount());
        $this->assertTrue($stmt->setFetchMode(\PDO::FETCH_CLASS, stdClass::class, []));
    }

    public function testExecute()
    {
        $queryString = 'SELECT ? AS foo, ? AS bar';

        $pdo = $this->createMock(PDOInterface::class);
        $listener = $this->createMock(PDOListenerInterface::class);
        $listener
            ->expects($this->once())
            ->method('onQuery')
            ->with(
                $this->identicalTo($pdo),
                $queryString,
                [123, 456],
                $this->greaterThan(0)
            );
        $delegate = $this->createMock(PDOStatementInterface::class);
        $delegate
            ->expects($this->once())
            ->method('bindValue')
            ->with(1, 123, \PDO::PARAM_INT)
            ->willReturn(true);
        $delegate
            ->expects($this->once())
            ->method('execute')
            ->with([456])
            ->willReturn(true);

        $stmt = new ListenableStatement($pdo, [$listener], $delegate, $queryString);

        $this->assertTrue($stmt->bindValue(1, 123, \PDO::PARAM_INT));
        $this->assertTrue($stmt->execute([456]));
    }

    public function testExecuteWithException()
    {
        $queryString = 'SELECT ? AS foo, ? AS bar';

        $pdo = $this->createMock(PDOInterface::class);
        $listener = $this->createMock(PDOListenerInterface::class);
        $listener
            ->expects($this->once())
            ->method('onQuery')
            ->with(
                $this->identicalTo($pdo),
                $queryString,
                [123, 456],
                $this->greaterThan(0)
            );
        $delegate = $this->createMock(PDOStatementInterface::class);
        $delegate
            ->expects($this->once())
            ->method('bindValue')
            ->with(1, 123, \PDO::PARAM_INT)
            ->willReturn(true);
        $delegate
            ->expects($this->once())
            ->method('execute')
            ->with([456])
            ->will($this->throwException(new \RuntimeException()));

        $stmt = new ListenableStatement($pdo, [$listener], $delegate, $queryString);

        $this->expectException(\RuntimeException::class);
        $this->assertTrue($stmt->bindValue(1, 123, \PDO::PARAM_INT));

        $stmt->execute([456]);
    }
}
