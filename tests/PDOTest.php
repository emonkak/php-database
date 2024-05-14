<?php

declare(strict_types=1);

namespace Emonkak\Database\Tests;

use Emonkak\Database\PDO;
use Emonkak\Database\PDOInterface;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @extends AbstractPDOTestCase<PDO>
 */
#[CoversClass(PDO::class)]
class PDOTest extends AbstractPDOTestCase
{
    protected function preparePdo(): PDOInterface
    {
        return new PDO('sqlite::memory:', null, null, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]);
    }
}
