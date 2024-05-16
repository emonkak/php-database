<?php

declare(strict_types=1);

namespace Emonkak\Database\Tests;

use Emonkak\Database\AbstractConnector;
use Emonkak\Database\PDOConnector;
use Emonkak\Database\PDOInterface;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @extends AbstractConnectorTestCase<PDOConnector>
 */
#[CoversClass(AbstractConnector::class)]
#[CoversClass(PDOConnector::class)]
class PDOConnectorTest extends AbstractConnectorTestCase
{
    protected function preparePdo(): PDOInterface
    {
        return new PDOConnector('sqlite::memory:', null, null, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]);
    }
}
