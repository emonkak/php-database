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
    public function exec(string $statement);

    /**
     * Returns the ID of the last inserted row or sequence value.
     *
     * @param string|null $name
     * @return string|false
     */
    public function lastInsertId(?string $name = null);

    /**
     * Prepares a statement for execution and returns a statement object.
     *
     * @param string $statement
     * @param array $options
     * @return PDOStatementInterface|false
     */
    public function prepare(string $statement, array $options = []);

    /**
     * Executes an SQL statement, returning a result set as a statement object.
     *
     * @param string $query
     * @param int|null $fetchMode
     * @param mixed ...$fetchModeArgs
     * @return PDOStatementInterface|false
     */
    public function query(string $query, ?int $fetchMode = null, ...$fetchModeArgs);

    /**
     * Quotes a string for use in a query.
     *
     * @param string $string
     * @param int $type
     * @return string|false
     */
    public function quote(string $string, int $type = \PDO::PARAM_STR);
}
