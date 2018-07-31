<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDO;

/**
 * @covers Emonkak\Database\PDO
 */
class PDOTest extends AbstractPDOTest
{
    protected function preparePdo()
    {
        return new PDO('sqlite::memory:', null, null, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]);
    }
}
