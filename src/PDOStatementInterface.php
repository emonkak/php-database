<?php

namespace Emonkak\Database;

/**
 * The interface as a subset of PDOStatement.
 */
interface PDOStatementInterface extends \Traversable
{
    /**
     * Binds a value to a parameter.
     *
     * @param mixed        $parameter
     * @param mixed        $value
     * @param integer|null $data_type
     * @return boolean
     */
    public function bindValue($parameter, $value, $data_type = null);

    /**
     * Closes the cursor, enabling the statement to be executed again.
     *
     * @return boolean
     */
    public function closeCursor();

    /**
     * Returns the number of columns in the result set.
     *
     * @return integer
     */
    public function columnCount();

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
     * @param array|null $input_parameters
     * @return array
     */
    public function execute($input_parameters = null);

    /**
     * Fetches the next row from a result set.
     *
     * @param integer|null $fetch_style
     * @param integer|null $cursor_orientation
     * @param integer|null $cursor_offset
     * @return array
     */
    public function fetch($fetch_style = null, $cursor_orientation = null, $cursor_offset = null);

    /**
     * Returns an array containing all of the result set rows.
     *
     * @param integer|null        $fetch_style
     * @param integer|string|null $fetch_argument
     * @param array|null          $ctor_args
     * @return array
     */
    public function fetchAll($fetch_style = null, $fetch_argument = null, $ctor_args = null);

    /**
     * Returns a single column from the next row of a result set.
     *
     * @param integer $column_number
     * @return string
     */
    public function fetchColumn($column_number = 0);

    /**
     * Advances to the next rowset in a multi-rowset statement handle.
     *
     * @return boolean
     */
    public function nextRowset();

    /**
     * Returns the number of rows affected by the last SQL statement.
     *
     * @return integer
     */
    public function rowCount();
}
