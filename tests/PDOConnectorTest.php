<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDOConnector;
use Emonkak\Database\PDOInterface;

/**
 * @covers \Emonkak\Database\AbstractConnector
 * @covers \Emonkak\Database\PDOConnector
 */
class PDOConnectorTest extends AbstractConnectorTestCase
{
    protected function preparePdo(): PDOInterface
    {
        return new PDOConnector('sqlite::memory:', null, null, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]);
    }
}
