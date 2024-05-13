<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\MysqliAdapter;
use Emonkak\Database\MysqliStmtAdapter;
use Emonkak\Database\PDOInterface;

/**
 * @covers \Emonkak\Database\MysqliStmtAdapter
 *
 * @requires extension mysqli
 */
class MysqliStmtAdapterTest extends AbstractPDOStatementTestCase
{
    private static $driver;

    private static $previous_report_mode;

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

    public function testExecuteWithFailure(): void
    {
        $stmt = $this->getMockBuilder('mysqli_stmt')
            ->disableOriginalConstructor()
            ->getMock();
        $stmt
            ->expects($this->once())
            ->method('execute')
            ->willReturn(false);

        $adapter = new MysqliStmtAdapter($stmt);

        $this->assertFalse($adapter->execute());
    }

    public function testFetchColumnWithInvalidIndex(): void
    {
        $stmt = $this->pdo->prepare('SELECT 1');
        $this->assertTrue($stmt->execute([]));
        $this->assertFalse($stmt->fetchColumn(1));
    }

    protected function preparePdo(): PDOInterface
    {
        $mysqli = new \mysqli(
            $GLOBALS['db_host'],
            $GLOBALS['db_username'],
            $GLOBALS['db_password'],
            $GLOBALS['db_name'],
            $GLOBALS['db_port']
        );
        return new MysqliAdapter($mysqli);
    }
}
