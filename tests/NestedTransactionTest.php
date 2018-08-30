<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\NestedTransaction;
use Emonkak\Database\PDOInterface;

/**
 * @covers Emonkak\Database\NestedTransaction
 */
class NestedTransactionTest extends \PHPUnit_Framework_TestCase
{
    private $pdo;

    private $nestedTransaction;

    public function setUp()
    {
        $this->pdo = $this->createMock(PDOInterface::class);
        $this->nestedTransaction = new NestedTransaction($this->pdo);
    }

    public function testCommit()
    {
        $this->pdo
            ->expects($this->at(0))
            ->method('beginTransaction')
            ->willReturn(true);
        $this->pdo
            ->expects($this->at(1))
            ->method('exec')
            ->with($this->identicalTo('SAVEPOINT level_1'));
        $this->pdo
            ->expects($this->at(2))
            ->method('exec')
            ->with($this->identicalTo('RELEASE SAVEPOINT level_1'));
        $this->pdo
            ->expects($this->at(3))
            ->method('commit')
            ->willReturn(true);
        $this->pdo
            ->expects($this->at(4))
            ->method('commit')
            ->willReturn(true);

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
            ->method('exec')
            ->with($this->identicalTo('SAVEPOINT level_1'));
        $this->pdo
            ->expects($this->at(2))
            ->method('exec')
            ->with($this->identicalTo('ROLLBACK TO SAVEPOINT level_1'));
        $this->pdo
            ->expects($this->at(3))
            ->method('rollback')
            ->willReturn(true);
        $this->pdo
            ->expects($this->at(4))
            ->method('rollback')
            ->willReturn(true);

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
