<?php

namespace Emonkak\Database;

/**
 * The serializable implementation of PDOInterface by PDO.
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
     * @return PDO
     */
    public function getPdo()
    {
        if ($this->pdo === null) {
            $this->pdo = new PDO($this->dsn, $this->user, $this->password, $this->options);
        }
        return $this->pdo;
    }

    /**
     * Returns whether the connection is available.
     *
     * @return boolean
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
    public function query($statement, $param1 = null, $param2 = null, $param3 = null)
    {
        return $this->getPdo()->query($statement, $param1, $param2, $param3);
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
