<?php

namespace Emonkak\Database;

/**
 * Represents a database connection that can be connected/disconnected.
 *
 * @template TPDO of PDOInterface
 */
abstract class AbstractConnector implements PDOInterface
{
    /**
     * @var ?TPDO
     */
    private $pdo;

    /**
     * Gets the PDO connection of the database. And connect to the database if not connected yet.
     *
     * @return TPDO
     */
    public function getPdo(): PDOInterface
    {
        if ($this->pdo === null) {
            $this->pdo = $this->doConnect();
        }
        return $this->pdo;
    }

    /**
     * Returns whether the connection is available.
     */
    public function isConnected(): bool
    {
        return $this->pdo !== null;
    }

    /**
     * Disconnects the connection.
     */
    public function disconnect(): void
    {
        if ($this->pdo !== null) {
            $this->doDisconnect($this->pdo);
            $this->pdo = null;
        }
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
    public function exec(string $statement)
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
    public function lastInsertId(?string $name = null)
    {
        return $this->getPdo()->lastInsertId();
    }

    /**
     * {@inheritdoc}
     */
    public function prepare(string $statement, array $options = [])
    {
        return $this->getPdo()->prepare($statement, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function query(string $query, ?int $fetchMode = null, ...$fetchModeArgs)
    {
        return $this->getPdo()->query($query, $fetchMode, ...$fetchModeArgs);
    }

    /**
     * {@inheritdoc}
     */
    public function quote(string $string, int $type = \PDO::PARAM_STR)
    {
        return $this->getPdo()->quote($string, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function rollback()
    {
        return $this->getPdo()->rollback();
    }

    /**
     * Connects the connection.
     *
     * @return TPDO
     */
    abstract protected function doConnect(): PDOInterface;

    /**
     * Disconnects the connection.
     *
     * @param TPDO $pdo
     */
    abstract protected function doDisconnect(PDOInterface $pdo): void;
}
