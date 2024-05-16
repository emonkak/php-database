<?php

declare(strict_types=1);

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDOInterface;
use Emonkak\Database\StandardSavepoint;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StandardSavepoint::class)]
class StandardSavepointTest extends TestCase
{
    public function testCreate(): void
    {
        $pdo = $this->createMock(PDOInterface::class);
        $pdo->expects($this->once())
            ->method('exec')
            ->with('SAVEPOINT name');
        $name = 'name';
        (new StandardSavepoint())->create($pdo, $name);
    }

    public function testRelease(): void
    {
        $pdo = $this->createMock(PDOInterface::class);
        $pdo->expects($this->once())
            ->method('exec')
            ->with('RELEASE SAVEPOINT name');
        $name = 'name';
        (new StandardSavepoint())->release($pdo, $name);
    }

    public function testRollbackTo(): void
    {
        $pdo = $this->createMock(PDOInterface::class);
        $pdo->expects($this->once())
            ->method('exec')
            ->with('ROLLBACK TO SAVEPOINT name');
        $name = 'name';
        (new StandardSavepoint())->rollbackTo($pdo, $name);
    }
}
