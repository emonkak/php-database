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
     *
     * @suppress PhanParamTooManyInternal
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
     *
     * @suppress PhanParamTooManyInternal
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
