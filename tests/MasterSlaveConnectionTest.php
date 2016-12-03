<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\MasterSlaveConnection;

/**
 * @covers Emonkak\Database\MasterSlaveConnection
 */
class MasterSlaveConnectionTest extends \PHPUnit_Framework_TestCase
{
    private $masterPdo;

    private $slavePdo;

    private $pdo;

    public function setUp()
    {
        $this->masterPdo = $this->getMock('Emonkak\Database\PDOInterface');

        $this->slavePdo = $this->getMock('Emonkak\Database\PDOInterface');
        $this->slavePdo->expects($this->never())->method('beginTransaction');
        $this->slavePdo->expects($this->never())->method('commit');
        $this->slavePdo->expects($this->never())->method('rollback');
        $this->slavePdo->expects($this->never())->method('inTransaction');

        $this->pdo = new MasterSlaveConnection($this->masterPdo, $this->slavePdo);
    }

    public function testGetMasterPdo()
    {
        $this->assertSame($this->masterPdo, $this->pdo->getMasterPdo());
    }

    public function testGetSlavePdo()
    {
        $this->assertSame($this->slavePdo, $this->pdo->getSlavePdo());
    }

    public function testInTransaction()
    {
        $this->masterPdo
            ->expects($this->once())
            ->method('beginTransaction')
            ->willReturn(true);
        $this->masterPdo
            ->expects($this->once())
            ->method('commit')
            ->willReturn(true);
        $this->masterPdo
            ->expects($this->once())
            ->method('errorCode')
            ->willReturn(123);
        $this->masterPdo
            ->expects($this->once())
            ->method('errorInfo')
            ->willReturn(array('HY000', 1, 'error'));
        $this->masterPdo
            ->expects($this->once())
            ->method('exec')
            ->with('SELECT 1')
            ->willReturn(0);
        $this->masterPdo
            ->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(123);
        $this->masterPdo
            ->expects($this->once())
            ->method('inTransaction')
            ->willReturn(true);
        $this->masterPdo
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT 1')
            ->willReturn($stmt1 = $this->getMock('Emonkak\Database\PDOStatementInterface'));
        $this->masterPdo
            ->expects($this->once())
            ->method('query')
            ->with('SELECT 1')
            ->willReturn($stmt2 = $this->getMock('Emonkak\Database\PDOStatementInterface'));
        $this->masterPdo
            ->expects($this->once())
            ->method('quote')
            ->with('foo')
            ->willReturn("'foo'");

        $this->assertTrue($this->pdo->beginTransaction());
        $this->assertTrue($this->pdo->inTransaction());
        $this->assertSame(123, $this->pdo->errorCode());
        $this->assertEquals(array('HY000', 1, 'error'), $this->pdo->errorInfo());
        $this->assertSame(0, $this->pdo->exec('SELECT 1'));
        $this->assertSame(123, $this->pdo->lastInsertId());
        $this->assertSame($stmt1, $this->pdo->prepare('SELECT 1'));
        $this->assertSame($stmt2, $this->pdo->query('SELECT 1'));
        $this->assertSame("'foo'", $this->pdo->quote('foo'));
        $this->assertTrue($this->pdo->commit());
    }

    public function testTransactionFailure()
    {
        $this->masterPdo
            ->expects($this->once())
            ->method('beginTransaction')
            ->will($this->throwException(new \Exception()));
        $this->masterPdo
            ->expects($this->once())
            ->method('inTransaction')
            ->willReturn(false);

        $this->slavePdo
            ->expects($this->once())
            ->method('errorCode')
            ->willReturn(123);
        $this->slavePdo
            ->expects($this->once())
            ->method('errorInfo')
            ->willReturn(array('HY000', 1, 'error'));
        $this->slavePdo
            ->expects($this->once())
            ->method('exec')
            ->with('SELECT 1')
            ->willReturn(0);
        $this->slavePdo
            ->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(123);
        $this->slavePdo
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT 1')
            ->willReturn($stmt1 = $this->getMock('Emonkak\Database\PDOStatementInterface'));
        $this->slavePdo
            ->expects($this->once())
            ->method('query')
            ->with('SELECT 1')
            ->willReturn($stmt2 = $this->getMock('Emonkak\Database\PDOStatementInterface'));
        $this->slavePdo
            ->expects($this->once())
            ->method('quote')
            ->with('foo')
            ->willReturn("'foo'");

        try {
            $this->pdo->beginTransaction();
        } catch (\Exception $e) {
            $this->assertSame(123, $this->pdo->errorCode());
            $this->assertFalse($this->pdo->inTransaction());
            $this->assertEquals(array('HY000', 1, 'error'), $this->pdo->errorInfo());
            $this->assertSame(0, $this->pdo->exec('SELECT 1'));
            $this->assertSame(123, $this->pdo->lastInsertId());
            $this->assertSame($stmt1, $this->pdo->prepare('SELECT 1'));
            $this->assertSame($stmt2, $this->pdo->query('SELECT 1'));
            $this->assertSame("'foo'", $this->pdo->quote('foo'));
            return;
        }
        $this->fail();
    }

    public function testAfterCommit()
    {
        $this->masterPdo
            ->expects($this->once())
            ->method('beginTransaction')
            ->willReturn(true);
        $this->masterPdo
            ->expects($this->once())
            ->method('commit')
            ->willReturn(true);
        $this->masterPdo
            ->expects($this->once())
            ->method('inTransaction')
            ->willReturn(false);

        $this->slavePdo
            ->expects($this->once())
            ->method('errorCode')
            ->willReturn(123);
        $this->slavePdo
            ->expects($this->once())
            ->method('errorInfo')
            ->willReturn(array('HY000', 1, 'error'));
        $this->slavePdo
            ->expects($this->once())
            ->method('exec')
            ->with('SELECT 1')
            ->willReturn(0);
        $this->slavePdo
            ->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(123);
        $this->slavePdo
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT 1')
            ->willReturn($stmt1 = $this->getMock('Emonkak\Database\PDOStatementInterface'));
        $this->slavePdo
            ->expects($this->once())
            ->method('query')
            ->with('SELECT 1')
            ->willReturn($stmt2 = $this->getMock('Emonkak\Database\PDOStatementInterface'));
        $this->slavePdo
            ->expects($this->once())
            ->method('quote')
            ->with('foo')
            ->willReturn("'foo'");

        $this->assertTrue($this->pdo->beginTransaction());
        $this->assertFalse($this->pdo->inTransaction());
        $this->assertTrue($this->pdo->commit());
        $this->assertSame(123, $this->pdo->errorCode());
        $this->assertEquals(array('HY000', 1, 'error'), $this->pdo->errorInfo());
        $this->assertSame(0, $this->pdo->exec('SELECT 1'));
        $this->assertSame(123, $this->pdo->lastInsertId());
        $this->assertSame($stmt1, $this->pdo->prepare('SELECT 1'));
        $this->assertSame($stmt2, $this->pdo->query('SELECT 1'));
        $this->assertSame("'foo'", $this->pdo->quote('foo'));
    }

    public function testAfterRollback()
    {
        $this->masterPdo
            ->expects($this->once())
            ->method('beginTransaction')
            ->willReturn(true);
        $this->masterPdo
            ->expects($this->once())
            ->method('rollback')
            ->willReturn(true);
        $this->masterPdo
            ->expects($this->once())
            ->method('inTransaction')
            ->willReturn(false);

        $this->slavePdo
            ->expects($this->once())
            ->method('errorCode')
            ->willReturn(123);
        $this->slavePdo
            ->expects($this->once())
            ->method('errorInfo')
            ->willReturn(array('HY000', 1, 'error'));
        $this->slavePdo
            ->expects($this->once())
            ->method('exec')
            ->with('SELECT 1')
            ->willReturn(0);
        $this->slavePdo
            ->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(123);
        $this->slavePdo
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT 1')
            ->willReturn($stmt1 = $this->getMock('Emonkak\Database\PDOStatementInterface'));
        $this->slavePdo
            ->expects($this->once())
            ->method('query')
            ->with('SELECT 1')
            ->willReturn($stmt2 = $this->getMock('Emonkak\Database\PDOStatementInterface'));
        $this->slavePdo
            ->expects($this->once())
            ->method('quote')
            ->with('foo')
            ->willReturn("'foo'");

        $this->assertTrue($this->pdo->beginTransaction());
        $this->assertFalse($this->pdo->inTransaction());
        $this->assertTrue($this->pdo->rollback());
        $this->assertSame(123, $this->pdo->errorCode());
        $this->assertEquals(array('HY000', 1, 'error'), $this->pdo->errorInfo());
        $this->assertSame(0, $this->pdo->exec('SELECT 1'));
        $this->assertSame(123, $this->pdo->lastInsertId());
        $this->assertSame($stmt1, $this->pdo->prepare('SELECT 1'));
        $this->assertSame($stmt2, $this->pdo->query('SELECT 1'));
        $this->assertSame("'foo'", $this->pdo->quote('foo'));
    }
}
