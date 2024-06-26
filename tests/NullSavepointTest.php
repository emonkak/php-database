<?php

declare(strict_types=1);

namespace Emonkak\Database\Tests;

use Emonkak\Database\NullSavepoint;
use Emonkak\Database\PDOInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(NullSavepoint::class)]
class NullSavepointTest extends TestCase
{
    public function testCreate(): void
    {
        $pdo = $this->createMock(PDOInterface::class);
        $pdo
            ->expects($this->never())
            ->method($this->anything());
        $name = 'name';
        (new NullSavepoint())->create($pdo, $name);
    }

    public function testRelease(): void
    {
        $pdo = $this->createMock(PDOInterface::class);
        $pdo
            ->expects($this->never())
            ->method($this->anything());
        $name = 'name';
        (new NullSavepoint())->release($pdo, $name);
    }

    public function testRollbackTo(): void
    {
        $pdo = $this->createMock(PDOInterface::class);
        $pdo
            ->expects($this->never())
            ->method($this->anything());
        $name = 'name';
        (new NullSavepoint())->rollbackTo($pdo, $name);
    }
}
