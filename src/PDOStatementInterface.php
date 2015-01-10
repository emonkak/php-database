<?php
/**
 * Copyright (c) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace PDOInterface;

/**
 * Interface as a subset of PDOStatement.
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
