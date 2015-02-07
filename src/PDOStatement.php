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
    public function setFetchMode($fetch_style, $fetch_argument = null, $ctor_args = null)
    {
        if ($ctor_args !== null) {
            return parent::setFetchMode($fetch_style, $fetch_argument, $ctor_args);
        }

        if ($fetch_argument !== null) {
            return parent::setFetchMode($fetch_style, $fetch_argument);
        }

        return parent::setFetchMode($fetch_style);
    }
}
