<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\NestedTransaction;
use Emonkak\Database\PDOInterface;
use Emonkak\Database\SavepointInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Emonkak\Database\NestedTransaction
 * @covers \Emonkak\Database\NestedTransactionState
 */
class NestedTransactionTest extends TestCase
{
    /**
     * @var PDOInterface&\PHPUnit\Framework\MockObject\MockObject
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private $pdo;

    /**
     * @var SavepointInterface&\PHPUnit\Framework\MockObject\MockObject
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private $savepoint;

    /**
     * @var NestedTransaction
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private $nestedTransaction;

    public function setUp(): void
    {
        $this->pdo = $this->createMock(PDOInterface::class);
        $this->savepoint = $this->createMock(SavepointInterface::class);
        $this->nestedTransaction = new NestedTransaction($this->pdo, $this->savepoint);
    }

    public function testCommit(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('beginTransaction')
            ->willReturn(true);
        $this->pdo
            ->expects($this->exactly(2))
            ->method('commit')
            ->willReturn(true);
        $this->savepoint
            ->expects($this->once())
            ->method('create')
            ->with(
                $this->identicalTo($this->pdo),
                $this->identicalTo('level_1')
            );
        $this->savepoint
            ->expects($this->once())
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

    public function testRollback(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('beginTransaction')
            ->willReturn(true);
        $this->pdo
            ->expects($this->exactly(2))
            ->method('rollback')
            ->willReturn(true);
        $this->savepoint
            ->expects($this->once())
            ->method('create')
            ->with(
                $this->identicalTo($this->pdo),
                $this->identicalTo('level_1')
            );
        $this->savepoint
            ->expects($this->once())
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
