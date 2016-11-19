<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDOAdapter;

class PDOAdapterTest extends AbstractPDOTest
{
    public function testGetPdo()
    {
        $pdo = new \PDO('sqlite::memory:');
        $adapter = new PDOAdapter($pdo);
        $this->assertSame($pdo, $adapter->getPdo());
    }

    protected function providePdo()
    {
        return new PDOAdapter(new \PDO('sqlite::memory:'));
    }
}
