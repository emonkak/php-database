<?php

namespace Emonkak\Database;

/**
 * The implementation of PDOInterface by PDO.
 */
class PDO extends \PDO implements PDOInterface
{
    /**
     * @param string      $dsn
     * @param string|null $user
     * @param string|null $password
     * @param array|null  $options
     */
    public function __construct($dsn, $user = null, $password = null, array $options = null)
    {
        parent::__construct($dsn, $user, $password, $options);

        $this->setAttribute(
            \PDO::ATTR_STATEMENT_CLASS,
            array(__NAMESPACE__ . '\\PDOStatement', array())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function query($statement, $param1 = null, $param2 = null, $param3 = null)
    {
        if ($param3 !== null) {
            return parent::query($statement, $param1, $param2, $param3);
        }

        if ($param2 !== null) {
            return parent::query($statement, $param1, $param2);
        }

        if ($param1 !== null) {
            return parent::query($statement, $param1);
        }

        return parent::query($statement);
    }
}
