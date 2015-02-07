<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDOConnector;

class PDOConnectorTest extends AbstractPDOTest
{
    public function testIsConnected()
    {
        $this->assertFalse($this->pdo->isConnected());
        $this->assertInstanceOf('PDO', $this->pdo->getPdo());
        $this->assertTrue($this->pdo->isConnected());
    }

    public function testSerialize()
    {
        $this->assertEquals($this->pdo, unserialize(serialize($this->pdo)));
    }

    protected function providePdo()
    {
        return new PDOConnector('sqlite::memory:');
    }
}
