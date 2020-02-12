<?php

namespace Emonkak\Database;

/**
 * The implementation of PDOStatementInterface by PDOStatement.
 */
class PDOStatement extends \PDOStatement implements PDOStatementInterface
{
    protected function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($fetch_style = null, $cursor_orientation = null, $cursor_offset = null)
    {
        if ($fetch_style === null) {
            return parent::fetch();
        }

        if ($cursor_orientation === null) {
            return parent::fetch($fetch_style);
        }

        if ($cursor_offset === null) {
            return parent::fetch($fetch_style, $cursor_orientation);
        }

        return parent::fetch($fetch_style, $cursor_orientation, $cursor_offset);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAll($fetch_style = null, $fetch_argument = null, $ctor_args = null)
    {
        if ($fetch_style === null) {
            return parent::fetchAll();
        }

        if ($fetch_argument === null) {
            return parent::fetchAll($fetch_style);
        }

        if ($ctor_args === null) {
            return parent::fetchAll($fetch_style, $fetch_argument);
        }

        return parent::fetchAll($fetch_style, $fetch_argument, $ctor_args);
    }

    /**
     * {@inheritdoc}
     */
    public function setFetchMode($mode, $param1 = null, $param2 = null)
    {
        if ($param1 === null) {
            return parent::setFetchMode($mode);
        }

        if ($param2 === null) {
            return parent::setFetchMode($mode, $param1);
        }

        return parent::setFetchMode($mode, $param1, $param2);
    }
}
