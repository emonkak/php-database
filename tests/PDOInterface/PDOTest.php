<?php

namespace PDOInterface;

class PDOTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PDOInterface
     */
    private $pdo;

    public function setUp()
    {
        $this->pdo = new PDO('sqlite:memory');
    }

    public function testTransaction()
    {
        $this->assertTrue($this->pdo->beginTransaction());
        $this->assertTrue($this->pdo->inTransaction());
        $this->assertTrue($this->pdo->rollback());
    }

    public function testErrorCode()
    {
        $this->assertEquals(0, $this->pdo->errorCode());
    }

    public function testErrorInfo()
    {
        $this->assertEquals(array(0, null, null), $this->pdo->errorInfo());
    }

    public function testLastInsertId()
    {
        $this->assertEquals(0, $this->pdo->lastInsertId());
    }

    public function testPrepare()
    {
        $this->assertInstanceOf(
            'PDOInterface\\PDOStatementInterface',
            $this->pdo->prepare('SELECT 1')
        );
    }

    public function testQuery()
    {
        $stmt = $this->pdo->query('SELECT 1 AS num');
        $this->assertInstanceOf(
            'PDOInterface\\PDOStatementInterface',
            $stmt
        );

        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $this->assertEquals(array('num' => 1), $stmt->fetch());
    }
}
