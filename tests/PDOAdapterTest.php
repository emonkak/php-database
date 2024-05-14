<?php

declare(strict_types=1);

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDOAdapter;
use Emonkak\Database\PDOInterface;

/**
 * @covers \Emonkak\Database\PDOAdapter
 *
 * @extends AbstractPDOTestCase<PDOAdapter>
 */
class PDOAdapterTest extends AbstractPDOTestCase
{
    public function testGetPdo(): void
    {
        $this->assertInstanceOf(\PDO::class, $this->pdo->getPdo());
    }

    protected function preparePdo(): PDOInterface
    {
        return new PDOAdapter(new \PDO('sqlite::memory:', null, null, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]));
    }
}
