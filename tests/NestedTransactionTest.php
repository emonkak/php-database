<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\NestedTransaction;
use Emonkak\Database\PDOInterface;
use Emonkak\Database\SavepointInterface;

/**
 * @covers Emonkak\Database\NestedTransaction
 * @covers Emonkak\Database\NestedTransactionState
 */
class NestedTransactionTest extends \PHPUnit_Framework_TestCase
{
    private $pdo;

    private $savepoint;

    private $nestedTransaction;

    public function setUp()
    {
        $this->pdo = $this->createMock(PDOInterface::class);
        $this->savepoint = $this->createMock(SavepointInterface::class);
        $this->nestedTransaction = new NestedTransaction($this->pdo, $this->savepoint);
    }

    public function testCommit()
    {
        $this->pdo
            ->expects($this->at(0))
            ->method('beginTransaction')
            ->willReturn(true);
        $this->pdo
            ->expects($this->at(1))
            ->method('commit')
            ->willReturn(true);
        $this->pdo
            ->expects($this->at(2))
            ->method('commit')
            ->willReturn(true);
        $this->savepoint
            ->expects($this->at(0))
            ->method('create')
            ->with(
                $this->identicalTo($this->pdo),
                $this->identicalTo('level_1')
            );
        $this->savepoint
            ->expects($this->at(1))
            ->method('release')
            ->with(
                $this->identicalTo($this->pdo),
                $this->identicalTo('level_1')
            );

        $this->assertSame(0, $this->nestedTransaction->getTransactionLevel());
        $this->assertFalse($this->nestedTransaction->inTransaction());

        $this->assertTrue($this->nestedTransaction->beginTransaction());
        $this->assertSame(1, $this->nestedTransaction->getTransactionLevel());
        $this->assertTrue($this->nestedTransaction->inTransaction());

        $this->assertTrue($this->nestedTransaction->beginTransaction());
        $this->assertSame(2, $this->nestedTransaction->getTransactionLevel());
        $this->assertTrue($this->nestedTransaction->inTransaction());

        $this->assertTrue($this->nestedTransaction->commit());
        $this->assertSame(1, $this->nestedTransaction->getTransactionLevel());
        $this->assertTrue($this->nestedTransaction->inTransaction());

        $this->assertTrue($this->nestedTransaction->commit());
        $this->assertSame(0, $this->nestedTransaction->getTransactionLevel());
        $this->assertFalse($this->nestedTransaction->inTransaction());

        $this->assertTrue($this->nestedTransaction->commit());
        $this->assertSame(0, $this->nestedTransaction->getTransactionLevel());
        $this->assertFalse($this->nestedTransaction->inTransaction());
    }

    public function testRollback()
    {
        $this->pdo
            ->expects($this->at(0))
            ->method('beginTransaction')
            ->willReturn(true);
        $this->pdo
            ->expects($this->at(1))
            ->method('rollback')
            ->willReturn(true);
        $this->pdo
            ->expects($this->at(2))
            ->method('rollback')
            ->willReturn(true);
        $this->savepoint
            ->expects($this->at(0))
            ->method('create')
            ->with(
                $this->identicalTo($this->pdo),
                $this->identicalTo('level_1')
            );
        $this->savepoint
            ->expects($this->at(1))
            ->method('rollbackTo')
            ->with(
                $this->identicalTo($this->pdo),
                $this->identicalTo('level_1')
            );

        $this->assertSame(0, $this->nestedTransaction->getTransactionLevel());
        $this->assertFalse($this->nestedTransaction->inTransaction());

        $this->assertTrue($this->nestedTransaction->beginTransaction());
        $this->assertSame(1, $this->nestedTransaction->getTransactionLevel());
        $this->assertTrue($this->nestedTransaction->inTransaction());

        $this->assertTrue($this->nestedTransaction->beginTransaction());
        $this->assertSame(2, $this->nestedTransaction->getTransactionLevel());
        $this->assertTrue($this->nestedTransaction->inTransaction());

        $this->assertTrue($this->nestedTransaction->rollback());
        $this->assertSame(1, $this->nestedTransaction->getTransactionLevel());
        $this->assertTrue($this->nestedTransaction->inTransaction());

        $this->assertTrue($this->nestedTransaction->rollback());
        $this->assertSame(0, $this->nestedTransaction->getTransactionLevel());
        $this->assertFalse($this->nestedTransaction->inTransaction());

        $this->assertTrue($this->nestedTransaction->rollback());
        $this->assertSame(0, $this->nestedTransaction->getTransactionLevel());
        $this->assertFalse($this->nestedTransaction->inTransaction());
    }
}
