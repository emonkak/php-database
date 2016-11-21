<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDOConnector;

/**
 * @covers Emonkak\Database\AbstractConnector
 * @covers Emonkak\Database\PDOConnector
 *
 * @requires extension sqlite3
 */
class PDOConnectorTest extends AbstractConnectorTest
{
    protected function preparePdo()
    {
        return new PDOConnector('sqlite::memory:', null, null, array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ));
    }
}
