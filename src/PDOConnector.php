<?php
/**
 * Copyright (c) 2015 Shota Nozaki <emonkak@gmail.com>
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
 * The serializable implementation of PDOInterface
 */
class PDOConnector implements PDOInterface
{
    /**
     * @var string
     */
    private $dsn;

    /**
     * @var string|null
     */
    private $user;

    /**
     * @var string|null
     */
    private $password;

    /**
     * @var array|null
     */
    private $options;

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @param string      $dsn
     * @param string|null $user
     * @param string|null $password
     * @param array|null  $options
     */
    public function __construct($dsn, $user = null, $password = null, array $options = null)
    {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $password;
        $this->options = $options;
    }

    /**
     * Gets the PDO connection of the database. And connect to the database if
     * not connected yet.
     *
     * @return \PDO
     */
    public function getPdo()
    {
        if ($this->pdo === null) {
            $this->pdo = new \PDO($this->dsn, $this->user, $this->password, $this->options);
            $this->pdo->setAttribute(
                PDO::ATTR_STATEMENT_CLASS,
                array(__NAMESPACE__ . '\\PDOStatement', array())
            );
        }
        return $this->pdo;
    }

    /**
     * Returns whether the connection is available.
     */
    public function isConnected()
    {
        return !!$this->pdo;
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction()
    {
        return $this->getPdo()->beginTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        return $this->getPdo()->commit();
    }

    /**
     * {@inheritdoc}
     */
    public function errorCode()
    {
        return $this->getPdo()->errorCode();
    }

    /**
     * {@inheritdoc}
     */
    public function errorInfo()
    {
        return $this->getPdo()->errorInfo();
    }

    /**
     * {@inheritdoc}
     */
    public function exec($statement)
    {
        return $this->getPdo()->exec($statement);
    }

    /**
     * {@inheritdoc}
     */
    public function inTransaction()
    {
        return $this->getPdo()->inTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public function lastInsertId($name = null)
    {
        return $this->getPdo()->lastInsertId();
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($statement)
    {
        return $this->getPdo()->prepare($statement);
    }

    /**
     * {@inheritdoc}
     */
    public function query($statement)
    {
        return $this->getPdo()->query($statement);
    }

    /**
     * {@inheritdoc}
     */
    public function quote($string, $parameter_type = null)
    {
        return $this->getPdo()->quote($string, $parameter_type);
    }

    /**
     * {@inheritdoc}
     */
    public function rollback()
    {
        return $this->getPdo()->rollback();
    }

    public function __sleep()
    {
        return array('dsn', 'user', 'password', 'options');
    }
}
