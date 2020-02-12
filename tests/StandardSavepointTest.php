<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\StandardSavepoint;
use Emonkak\Database\PDOInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers Emonkak\Database\StandardSavepoint
 */
class StandardSavepointTest extends TestCase
{
    public function testCreate()
    {
        $pdo = $this->createMock(PDOInterface::class);
        $pdo->expects($this->once())
            ->method('exec')
            ->with('SAVEPOINT name');
        $name = 'name';
        (new StandardSavepoint())->create($pdo, $name);
    }

    public function testRelease()
    {
        $pdo = $this->createMock(PDOInterface::class);
        $pdo->expects($this->once())
            ->method('exec')
            ->with('RELEASE SAVEPOINT name');
        $name = 'name';
        (new StandardSavepoint())->release($pdo, $name);
    }

    public function testRollbackTo()
    {
        $pdo = $this->createMock(PDOInterface::class);
        $pdo->expects($this->once())
            ->method('exec')
            ->with('ROLLBACK TO SAVEPOINT name');
        $name = 'name';
        (new StandardSavepoint())->rollbackTo($pdo, $name);
    }
}
