<?php

declare(strict_types=1);

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDO;
use Emonkak\Database\PDOInterface;

/**
 * @covers \Emonkak\Database\PDOStatement
 *
 * @extends AbstractPDOStatementTestCase<PDO>
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
