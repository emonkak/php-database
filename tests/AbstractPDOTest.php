<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDOStatementInterface;
use PHPUnit\Framework\TestCase;

abstract class AbstractPDOTest extends TestCase
{
    protected $pdo;

    public function setUp(): void
    {
        $this->pdo = $this->preparePdo();
    }

    public function testBeginTransaction()
    {
        $this->assertTrue($this->pdo->beginTransaction());
        $this->assertTrue($this->pdo->rollback());
    }

    public function testBeginTransactionThrowsRuntimeException()
    {
        $this->expectException(\RuntimeException::class);
        $this->pdo->beginTransaction();
        $this->pdo->beginTransaction();
    }

    public function testCommit()
    {
        $this->assertTrue($this->pdo->beginTransaction());
        $this->assertTrue($this->pdo->commit());
    }

    public function testCommitThrowsRuntimeException()
    {
        $this->expectException(\RuntimeException::class);
        $this->pdo->commit();
    }

    public function testErrorCode()
    {
        $this->assertEquals(0, $this->pdo->errorCode());
    }

    public function testErrorInfo()
    {
        $error = $this->pdo->errorInfo();
        $this->assertIsArray($error);
        $this->assertCount(3, $error);
    }

    public function testExec()
    {
        $this->assertSame(0, $this->pdo->exec('SELECT 1'));
    }

    public function testInTransaction()
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

    public function testLastInsertId()
    {
        $this->assertEquals(0, $this->pdo->lastInsertId());
    }

    public function testPrepare()
    {
        $stmt = $this->pdo->prepare('SELECT 1 AS foo');
        $this->assertInstanceOf(PDOStatementInterface::class, $stmt);
    }

    public function testQuery()
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

    public function testQuote()
    {
        $this->assertSame("'foo'", $this->pdo->quote('foo'));
    }

    public function testRollback()
    {
        $this->assertTrue($this->pdo->beginTransaction());
        $this->assertTrue($this->pdo->rollback());
    }

    public function testRollbackThrowsRuntimeException()
    {
        $this->expectException(\RuntimeException::class);
        $this->pdo->rollback();
        $this->pdo->rollback();
    }

    abstract protected function preparePdo();
}
