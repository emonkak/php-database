<?php

declare(strict_types=1);

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDOConnector;
use Emonkak\Database\PDOInterface;

/**
 * @covers \Emonkak\Database\AbstractConnector
 * @covers \Emonkak\Database\PDOConnector
 *
 * @extends AbstractConnectorTestCase<PDOConnector>
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
