<?php

declare(strict_types=1);

namespace Emonkak\Database\Tests;

use Emonkak\Database\AbstractConnector;
use Emonkak\Database\MysqliConnector;
use Emonkak\Database\PDOInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

/**
 * @extends AbstractConnectorTestCase<MysqliConnector>
 */
#[CoversClass(AbstractConnector::class)]
#[CoversClass(MysqliConnector::class)]
#[RequiresPhpExtension('mysqli')]
class MysqliConnectorTest extends AbstractConnectorTestCase
{
    private static \mysqli_driver $driver;

    private static int $previous_report_mode;

    public static function setUpBeforeClass(): void
    {
        self::$driver = $driver = new \mysqli_driver();
        self::$previous_report_mode = $driver->report_mode;

        $driver->report_mode = MYSQLI_REPORT_ALL & ~MYSQLI_REPORT_INDEX;
    }

    public static function tearDownAfterClass(): void
    {
        self::$driver->report_mode = self::$previous_report_mode;

        self::$driver = null;
        self::$previous_report_mode = null;
    }

    protected function preparePdo(): PDOInterface
    {
        return new MysqliConnector(
            $GLOBALS['db_host'],
            $GLOBALS['db_username'],
            $GLOBALS['db_password'],
            $GLOBALS['db_name'],
            (int) $GLOBALS['db_port']
        );
    }
}
