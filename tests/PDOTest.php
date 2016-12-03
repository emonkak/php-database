<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDO;

/**
 * @covers Emonkak\Database\PDO
 *
 * @requires extension sqlite3
 */
class PDOTest extends AbstractPDOTest
{
    protected function preparePdo()
    {
        return new PDO('sqlite::memory:', null, null, array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ));
    }
}
