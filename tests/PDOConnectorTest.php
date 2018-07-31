<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDOConnector;

/**
 * @covers Emonkak\Database\AbstractConnector
 * @covers Emonkak\Database\PDOConnector
 */
class PDOConnectorTest extends AbstractConnectorTest
{
    protected function preparePdo()
    {
        return new PDOConnector('sqlite::memory:', null, null, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]);
    }
}
