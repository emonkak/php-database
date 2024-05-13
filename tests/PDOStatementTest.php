<?php

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDO;
use Emonkak\Database\PDOInterface;

/**
 * @covers \Emonkak\Database\PDOStatement
 */
class PDOStatementTest extends AbstractPDOStatementTestCase
{
    protected function preparePdo(): PDOInterface
    {
        return new PDO('sqlite::memory:', null, null, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]);
    }
}
