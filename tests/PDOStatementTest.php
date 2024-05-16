<?php

declare(strict_types=1);

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDO;
use Emonkak\Database\PDOInterface;
use Emonkak\Database\PDOStatement;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @extends AbstractPDOStatementTestCase<PDO>
 */
#[CoversClass(PDOStatement::class)]
class PDOStatementTest extends AbstractPDOStatementTestCase
{
    protected function preparePdo(): PDOInterface
    {
        return new PDO('sqlite::memory:', null, null, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]);
    }
}
