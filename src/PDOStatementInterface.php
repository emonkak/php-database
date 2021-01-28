<?php

namespace Emonkak\Database;

/**
 * The interface as a subset of PDOStatement.
 *
 * @implements \Traversable<mixed>
 */
interface PDOStatementInterface extends \Traversable
{
    /**
     * Binds a value to a parameter.
     *
     * @return bool
     */
    public function bindValue(string $param, mixed $value, int $type = \PDO::PARAM_STR);

    /**
     * Fetch the SQLSTATE associated with the last operation on the statement
     * handle.
     *
     * @return string
     */
    public function errorCode();

    /**
     * Fetch extended error information associated with the last operation on
     * the database handle.
     *
     * @return array
     */
    public function errorInfo();

    /**
     * Executes a prepared statement.
     *
     * @return bool
     */
    public function execute(?array $params = null);

    /**
     * Fetches the next row from a result set.
     *
     * @return mixed
     */
    public function fetch(int $mode = \PDO::ATTR_DEFAULT_FETCH_MODE, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0);

    /**
     * Returns an array containing all of the result set rows.
     *
     * @return array
     */
    public function fetchAll(int $mode = \PDO::ATTR_DEFAULT_FETCH_MODE, mixed ...$args);

    /**
     * Returns a single column from the next row of a result set.
     *
     * @return string|false
     */
    public function fetchColumn(int $column = 0);

    /**
     * Returns the number of rows affected by the last SQL statement.
     *
     * @return int
     */
    public function rowCount();

    /**
     * Returns the number of rows affected by the last SQL statement.
     *
     * @return bool
     */
    public function setFetchMode(int $mode, mixed ...$args);
}
