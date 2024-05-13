<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDOInterface;

abstract class AbstractConnectorTestCase extends AbstractPDOTestCase
{
    public function testIsConnected(): void
    {
        $this->assertFalse($this->pdo->isConnected());
        $this->assertInstanceOf(PDOInterface::class, $this->pdo->getPdo());
        $this->assertTrue($this->pdo->isConnected());

        $this->pdo->disconnect();
        $this->assertFalse($this->pdo->isConnected());
    }

    public function testSerialize(): void
    {
        $this->assertEquals($this->pdo, unserialize(serialize($this->pdo)));
    }
}
