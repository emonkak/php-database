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
     * @param string $statement
     * @return PDOStatementInterface
     */
    public function query($statement);

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
