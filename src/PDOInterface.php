<?php

namespace Emonkak\Database;

/**
 * The interface as a subset of PDO.
 */
interface PDOInterface extends PDOTransactionInterface
{
    /**
     * Fetch the SQLSTATE associated with the last operation on the database
     * handle.
     *
     * @return ?string
     */
    public function errorCode();

    /**
     * Fetch extended error information associated with the last operation on
     * the statement handle.
     *
     * @return array
     */
    public function errorInfo();

    /**
     * Execute an SQL statement and return the number of affected rows.
     *
     * @param string $statement
     * @return int|false
     */
    public function exec($statement);

    /**
     * Returns the ID of the last inserted row or sequence value.
     *
     * @param string $name
     * @return string
     */
    public function lastInsertId($name = null);

    /**
     * Prepares a statement for execution and returns a statement object.
     *
     * @param string $statement
     * @return PDOStatementInterface|false
     */
    public function prepare($statement);

    /**
     * Executes an SQL statement, returning a result set as a statement object.
     *
     * @param string $statement
     * @param mixed $param1
     * @param mixed $param2
     * @param mixed $param3
     * @return PDOStatementInterface|false
     */
    public function query($statement, $param1 = null, $param2 = null, $param3 = null);

    /**
     * Quotes a string for use in a query.
     *
     * @param string $string
     * @param int $parameter_type
     * @return string
     */
    public function quote($string, $parameter_type = \PDO::PARAM_STR);
}
