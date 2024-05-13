<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDOAdapter;
use Emonkak\Database\PDOInterface;

/**
 * @covers \Emonkak\Database\PDOAdapter
 */
class PDOAdapterTest extends AbstractPDOTestCase
{
    public function testGetPdo(): void
    {
        $this->assertInstanceOf('PDO', $this->pdo->getPdo());
    }

    protected function preparePdo(): PDOInterface
    {
        return new PDOAdapter(new \PDO('sqlite::memory:', null, null, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]));
    }
}
