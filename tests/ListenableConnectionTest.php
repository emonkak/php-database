<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\ListenableConnection;
use Emonkak\Database\ListenableStatement;
use Emonkak\Database\PDOInterface;
use Emonkak\Database\PDOListenerInterface;
use Emonkak\Database\PDOStatementInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Emonkak\Database\ListenableConnection
 */
class ListenableConnectionTest extends TestCase
{
    /**
     * @var PDOInterface&\PHPUnit\Framework\MockObject\MockObject
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private $delegate;

    /**
     * @var PDOListenerInterface&\PHPUnit\Framework\MockObject\MockObject
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private $listener;

    /**
     * @var ListenableConnection
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private $pdo;

    public function setUp(): void
    {
        $this->delegate = $this->createMock(PDOInterface::class);
        $this->listener = $this->createMock(PDOListenerInterface::class);
        $this->pdo = new ListenableConnection($this->delegate);
        $this->pdo->addListaner($this->listener);
    }

    public function testDelegate(): void
    {
        $this->delegate
            ->expects($this->once())
            ->method('errorCode')
            ->willReturn('123');
        $this->delegate
            ->expects($this->once())
            ->method('errorInfo')
            ->willReturn(['HY000', 1, 'error']);
        $this->delegate
            ->expects($this->once())
            ->method('lastInsertId')
            ->willReturn('123');
        $this->delegate
            ->expects($this->once())
            ->method('inTransaction')
            ->willReturn(true);
        $this->delegate
            ->expects($this->once())
            ->method('quote')
            ->with('foo')
            ->willReturn("'foo'");

        $this->assertTrue($this->pdo->inTransaction());
        $this->assertSame('123', $this->pdo->errorCode());
        $this->assertEquals(['HY000', 1, 'error'], $this->pdo->errorInfo());
        $this->assertSame('123', $this->pdo->lastInsertId());
        $this->assertSame("'foo'", $this->pdo->quote('foo'));
    }

    public function testBeginTransaction(): void
    {
        $this->delegate
            ->expects($this->once())
            ->method('beginTransaction')
            ->willReturn(true);
        $this->listener
            ->expects($this->once())
            ->method('onBeginTransaction')
            ->with($this->identicalTo($this->delegate));

        $this->assertTrue($this->pdo->beginTransaction());
    }

    public function testExec(): void
    {
        $this->delegate
            ->expects($this->once())
            ->method('exec')
            ->with('SELECT 1')
            ->willReturn(0);
        $this->listener
            ->expects($this->once())
            ->method('onQuery')
            ->with(
                $this->identicalTo($this->delegate),
                'SELECT 1',
                [],
                $this->greaterThan(0)
            );

        $this->assertSame(0, $this->pdo->exec('SELECT 1'));
    }

    public function testPrepare(): void
    {
        $this->delegate
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT 1')
            ->willReturn($this->createMock(PDOStatementInterface::class));

        $this->assertInstanceOf(ListenableStatement::class, $this->pdo->prepare('SELECT 1'));
    }

    public function testQuery(): void
    {
        $this->delegate
            ->expects($this->once())
            ->method('query')
            ->with('SELECT 1')
            ->willReturn($stmt = $this->createMock(PDOStatementInterface::class));
        $this->listener
            ->expects($this->once())
            ->method('onQuery')
            ->with(
                $this->identicalTo($this->delegate),
                'SELECT 1',
                [],
                $this->greaterThan(0)
            );

        $this->assertInstanceOf(ListenableStatement::class, $this->pdo->query('SELECT 1'));
    }

    public function testCommit(): void
    {
        $this->delegate
            ->expects($this->once())
            ->method('commit')
            ->willReturn(true);
        $this->listener
            ->expects($this->once())
            ->method('onCommit')
            ->with($this->identicalTo($this->delegate));

        $this->assertTrue($this->pdo->commit());
    }

    public function testRollback(): void
    {
        $this->delegate
            ->expects($this->once())
            ->method('rollback')
            ->willReturn(true);
        $this->listener
            ->expects($this->once())
            ->method('onRollback')
            ->with($this->identicalTo($this->delegate));

        $this->assertTrue($this->pdo->rollback());
    }
}
