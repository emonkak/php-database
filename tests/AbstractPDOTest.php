<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDOInterface;

abstract class AbstractPDOTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->pdo = $this->providePdo();
    }

    public function testBeginTransaction()
    {
        $this->assertTrue($this->pdo->beginTransaction());
        $this->assertTrue($this->pdo->rollback());
    }

    public function testCommit()
    {
        $this->assertTrue($this->pdo->beginTransaction());
        $this->assertTrue($this->pdo->commit());
    }

    public function testErrorCode()
    {
        $this->assertEquals(0, $this->pdo->errorCode());
    }

    public function testErrorInfo()
    {
        $this->assertEquals(array(0, null, null), $this->pdo->errorInfo());
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
    }

    public function testLastInsertId()
    {
        $this->assertEquals(0, $this->pdo->lastInsertId());
    }

    public function testPrepare()
    {
        $stmt = $this->pdo->prepare('SELECT 1');
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $stmt->setFetchMode(\PDO::FETCH_COLUMN, 1);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass', array());
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

        $stmt = $this->pdo->query('SELECT 1', \PDO::FETCH_CLASS, 'stdClass', array());
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

    abstract protected function providePdo();
}
