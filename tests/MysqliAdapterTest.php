<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\MysqliAdapter;
use Emonkak\Database\PDOInterface;

/**
 * @covers \Emonkak\Database\MysqliAdapter
 *
 * @requires extension mysqli
 */
class MysqliAdapterTest extends AbstractPDOTestCase
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

    public function testGetMysqli(): void
    {
        $this->assertInstanceOf('mysqli', $this->pdo->getMysqli());
    }

    public function testBeginTransactionWithFailure(): void
    {
        $mysqli = $this->getMockBuilder('mysqli')
            ->disableOriginalConstructor()
            ->getMock();
        $mysqli
            ->expects($this->once())
            ->method('real_query')
            ->willReturn(false);

        $adapter = new MysqliAdapter($mysqli);

        $this->assertFalse($adapter->beginTransaction());
    }

    public function testExecWithFailure(): void
    {
        $mysqli = $this->getMockBuilder('mysqli')
            ->disableOriginalConstructor()
            ->getMock();
        $mysqli
            ->expects($this->once())
            ->method('real_query')
            ->willReturn(false);

        $adapter = new MysqliAdapter($mysqli);

        $this->assertFalse($adapter->exec('SELECT 1'));
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
