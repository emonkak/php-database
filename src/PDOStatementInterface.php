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
     * @param mixed $parameter
     * @param mixed $value
     * @param int $data_type
     * @return bool
     */
    public function bindValue($parameter, $value, $data_type = \PDO::PARAM_STR);

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
     * @param array $input_parameters
     * @return bool
     */
    public function execute($input_parameters = null);

    /**
     * Fetches the next row from a result set.
     *
     * @param int $fetch_style
     * @param int $cursor_orientation
     * @param int $cursor_offset
     * @return mixed
     */
    public function fetch($fetch_style = null, $cursor_orientation = null, $cursor_offset = null);

    /**
     * Returns an array containing all of the result set rows.
     *
     * @param int $fetch_style
     * @param int|string $fetch_argument
     * @param array $ctor_args
     * @return array
     */
    public function fetchAll($fetch_style = null, $fetch_argument = null, $ctor_args = null);

    /**
     * Returns a single column from the next row of a result set.
     *
     * @param int $column_number
     * @return string|false
     */
    public function fetchColumn($column_number = 0);

    /**
     * Returns the number of rows affected by the last SQL statement.
     *
     * @return int
     */
    public function rowCount();

    /**
     * Returns the number of rows affected by the last SQL statement.
     *
     * @param int $mode
     * @param mixed $param1
     * @param mixed $param2
     * @return bool
     */
    public function setFetchMode($mode, $param1 = null, $param2 = null);
}
