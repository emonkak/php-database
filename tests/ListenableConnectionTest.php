<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\ListenableConnection;

/**
 * @covers Emonkak\Database\ListenableConnection
 */
class ListenableConnectionTest extends \PHPUnit_Framework_TestCase
{
    private $delegate;

    private $listener;

    private $pdo;

    public function setUp()
    {
        $this->delegate = $this->getMock('Emonkak\Database\PDOInterface');
        $this->listener = $this->getMock('Emonkak\Database\PDOListenerInterface');
        $this->pdo = new ListenableConnection($this->delegate);
        $this->pdo->addListaner($this->listener);
    }

    public function testDelegate()
    {
        $this->delegate
            ->expects($this->once())
            ->method('errorCode')
            ->willReturn(123);
        $this->delegate
            ->expects($this->once())
            ->method('errorInfo')
            ->willReturn(array('HY000', 1, 'error'));
        $this->delegate
            ->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(123);
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
        $this->assertSame(123, $this->pdo->errorCode());
        $this->assertEquals(array('HY000', 1, 'error'), $this->pdo->errorInfo());
        $this->assertSame(123, $this->pdo->lastInsertId());
        $this->assertSame("'foo'", $this->pdo->quote('foo'));
    }

    public function testBeginTransaction()
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

    public function testExec()
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
                array(),
                $this->greaterThan(0)
            );

        $this->assertSame(0, $this->pdo->exec('SELECT 1'));
    }

    public function testPrepare()
    {
        $this->delegate
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT 1')
            ->willReturn($this->getMock('Emonkak\Database\PDOStatementInterface'));

        $this->assertInstanceOf('Emonkak\Database\ListenableStatement', $this->pdo->prepare('SELECT 1'));
    }

    public function testQuery()
    {
        $this->delegate
            ->expects($this->once())
            ->method('query')
            ->with('SELECT 1')
            ->willReturn($stmt = $this->getMock('Emonkak\Database\PDOStatementInterface'));
        $this->listener
            ->expects($this->once())
            ->method('onQuery')
            ->with(
                $this->identicalTo($this->delegate),
                'SELECT 1',
                array(),
                $this->greaterThan(0)
            );

        $this->assertInstanceOf('Emonkak\Database\ListenableStatement', $this->pdo->query('SELECT 1'));
    }

    public function testCommit()
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

    public function testRollback()
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
