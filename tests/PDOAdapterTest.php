<?php

declare(strict_types=1);

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDOAdapter;
use Emonkak\Database\PDOInterface;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @extends AbstractPDOTestCase<PDOAdapter>
 */
#[CoversClass(PDOAdapter::class)]
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
