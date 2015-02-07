<?php

namespace Emonkak\Database;

/**
 * The implementation of PDOStatementInterface by PDOStatement.
 */
class PDOStatement extends \PDOStatement implements PDOStatementInterface
{
    private function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function setFetchMode($mode, $param1 = null, $param2 = null)
    {
        if ($param2 !== null) {
            return parent::setFetchMode($mode, $param1, $param2);
        }

        if ($param1 !== null) {
            return parent::setFetchMode($mode, $param1);
        }

        return parent::setFetchMode($mode);
    }
}
