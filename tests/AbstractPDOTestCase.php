<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDOInterface;
use Emonkak\Database\PDOStatementInterface;
use PHPUnit\Framework\TestCase;

abstract class AbstractPDOTestCase extends TestCase
{
    protected $pdo;

    public function setUp(): void
    {
        $this->pdo = $this->preparePdo();
    }

    public function testBeginTransaction(): void
    {
        $this->assertTrue($this->pdo->beginTransaction());
        $this->assertTrue($this->pdo->rollback());
    }

    public function testBeginTransactionThrowsRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->pdo->beginTransaction();
        $this->pdo->beginTransaction();
    }

    public function testCommit(): void
    {
        $this->assertTrue($this->pdo->beginTransaction());
        $this->assertTrue($this->pdo->commit());
    }

    public function testCommitThrowsRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->pdo->commit();
    }

    public function testErrorCode(): void
    {
        $this->assertEquals(0, $this->pdo->errorCode());
    }

    public function testErrorInfo(): void
    {
        $error = $this->pdo->errorInfo();
        $this->assertIsArray($error);
        $this->assertCount(3, $error);
    }

    public function testExec(): void
    {
        $this->assertSame(0, $this->pdo->exec('SELECT 1'));
    }

    public function testInTransaction(): void
    {
        $this->assertFalse($this->pdo->inTransaction());
        $this->assertTrue($this->pdo->beginTransaction());
        $this->assertTrue($this->pdo->inTransaction());
        $this->assertTrue($this->pdo->rollback());
        $this->assertFalse($this->pdo->inTransaction());

        $this->assertFalse($this->pdo->inTransaction());
        $this->assertTrue($this->pdo->beginTransaction());
        $this->assertTrue($this->pdo->inTransaction());
        $this->assertTrue($this->pdo->commit());
        $this->assertFalse($this->pdo->inTransaction());
    }

    public function testLastInsertId(): void
    {
        $this->assertEquals(0, $this->pdo->lastInsertId());
    }

    public function testPrepare(): void
    {
        $stmt = $this->pdo->prepare('SELECT 1 AS foo');
        $this->assertInstanceOf(PDOStatementInterface::class, $stmt);
    }

    public function testQuery(): void
    {
        $stmt = $this->pdo->query('SELECT 1');
        $this->assertInstanceOf(PDOStatementInterface::class, $stmt);

        $stmt = $this->pdo->query('SELECT 1', \PDO::FETCH_ASSOC);
        $this->assertInstanceOf(PDOStatementInterface::class, $stmt);

        $stmt = $this->pdo->query('SELECT 1', \PDO::FETCH_COLUMN, 1);
        $this->assertInstanceOf(PDOStatementInterface::class, $stmt);

        $stmt = $this->pdo->query('SELECT 1', \PDO::FETCH_CLASS, Entity::class, []);
        $this->assertInstanceOf(PDOStatementInterface::class, $stmt);
    }

    public function testQuote(): void
    {
        $this->assertSame("'foo'", $this->pdo->quote('foo'));
    }

    public function testRollback(): void
    {
        $this->assertTrue($this->pdo->beginTransaction());
        $this->assertTrue($this->pdo->rollback());
    }

    public function testRollbackThrowsRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->pdo->rollback();
        $this->pdo->rollback();
    }

    abstract protected function preparePdo(): PDOInterface;
}
