<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\NullSavepoint;
use Emonkak\Database\PDOInterface;

/**
 * @covers Emonkak\Database\NullSavepoint
 */
class NullSavepointTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $pdo = $this->createMock(PDOInterface::class);
        $name = 'name';
        (new NullSavepoint())->create($pdo, $name);
    }

    public function testRelease()
    {
        $pdo = $this->createMock(PDOInterface::class);
        $name = 'name';
        (new NullSavepoint())->release($pdo, $name);
    }

    public function testRollbackTo()
    {
        $pdo = $this->createMock(PDOInterface::class);
        $name = 'name';
        (new NullSavepoint())->rollbackTo($pdo, $name);
    }
}
