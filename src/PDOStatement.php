<?php

namespace Emonkak\Database;

/**
 * @template TValue
 * @template-extends \PDOStatement<TValue>
 */
class PDOStatement extends \PDOStatement implements PDOStatementInterface
{
    protected function __construct()
    {
        // The constructor is required to avoid "User-supplied statement does
        // not accept constructor arguments" exception.
    }
}
