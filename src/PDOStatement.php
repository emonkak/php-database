<?php

declare(strict_types=1);

namespace Emonkak\Database;

/**
 * @template TValue
 * @extends \PDOStatement<TValue>
 */
class PDOStatement extends \PDOStatement implements PDOStatementInterface
{
    protected function __construct()
    {
        // The constructor is required to avoid "User-supplied statement does
        // not accept constructor arguments" exception.
    }
}
