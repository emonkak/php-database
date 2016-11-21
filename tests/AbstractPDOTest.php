<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDOInterface;

abstract class AbstractPDOTest extends \PHPUnit_Framework_TestCase
{
    protected $pdo;

    public function setUp()
    {
        $this->pdo = $this->preparePdo();
    }

    public function testBeginTransaction()
    {
        $this->assertTrue($this->pdo->beginTransaction());
        $this->assertTrue($this->pdo->rollback());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testBeginTransactionThrowsRuntimeException()
    {
        $this->pdo->beginTransaction();
        $this->pdo->beginTransaction();
    }

    public function testCommit()
    {
        $this->assertTrue($this->pdo->beginTransaction());
        $this->assertTrue($this->pdo->commit());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testCommitThrowsRuntimeException()
    {
        $this->pdo->commit();
    }

    public function testErrorCode()
    {
        $this->assertEquals(0, $this->pdo->errorCode());
    }

    public function testErrorInfo()
    {
        $error = $this->pdo->errorInfo();
        $this->assertInternalType('array', $error);
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
        $this->assertInstanceOf('Emonkak\Database\PDOStatementInterface', $stmt);
    }

    public function testQuery()
    {
        $stmt = $this->pdo->query('SELECT 1');
        $this->assertInstanceOf('Emonkak\Database\PDOStatementInterface', $stmt);

        $stmt = $this->pdo->query('SELECT 1', \PDO::FETCH_ASSOC);
        $this->assertInstanceOf('Emonkak\Database\PDOStatementInterface', $stmt);

        $stmt = $this->pdo->query('SELECT 1', \PDO::FETCH_COLUMN, 1);
        $this->assertInstanceOf('Emonkak\Database\PDOStatementInterface', $stmt);

        $stmt = $this->pdo->query('SELECT 1', \PDO::FETCH_CLASS, 'Emonkak\Database\Tests\Entity', array());
        $this->assertInstanceOf('Emonkak\Database\PDOStatementInterface', $stmt);
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

    /**
     * @expectedException RuntimeException
     */
    public function testRollbackThrowsRuntimeException()
    {
        $this->pdo->rollback();
        $this->pdo->rollback();
    }

    abstract protected function preparePdo();
}
