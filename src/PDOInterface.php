<?php

namespace Emonkak\Database;

/**
 * The interface as a subset of PDO.
 */
interface PDOInterface
{
    /**
     * Initiates a transaction.
     *
     * @return boolean
     */
    public function beginTransaction();

    /**
     * Commits a transaction.
     *
     * @return boolean
     */
    public function commit();

    /**
     * Fetch the SQLSTATE associated with the last operation on the database
     * handle.
     *
     * @return string|null
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
     * @return integer
     */
    public function exec($statement);

    /**
     * Checks if inside a transaction.
     *
     * @return boolean
     */
    public function inTransaction();

    /**
     * Returns the ID of the last inserted row or sequence value.
     *
     * @param string|null $name
     * @return string
     */
    public function lastInsertId($name = null);

    /**
     * Prepares a statement for execution and returns a statement object.
     *
     * @param string $statement
     * @return PDOStatementInterface
     */
    public function prepare($statement);

    /**
     * Executes an SQL statement, returning a result set as a statement object.
     *
     * @param string     $statement
     * @param mixed|null $param1
     * @param mixed|null $param2
     * @param mixed|null $param3
     * @return PDOStatementInterface
     */
    public function query($statement, $param1 = null, $param2 = null, $param3 = null);

    /**
     * Quotes a string for use in a query.
     *
     * @param string       $string
     * @param integer|null $parameter_type
     * @return PDOStatementInterface
     */
    public function quote($string, $parameter_type = null);

    /**
     * Rolls back a transaction.
     *
     * @return boolean
     */
    public function rollback();
}
