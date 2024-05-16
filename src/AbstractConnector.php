<?php

declare(strict_types=1);

namespace Emonkak\Database;

/**
 * A connectable database abstraction.
 *
 * In this implementation, a connection to the database are deferred until
 * needed.
 *
 * @template TConnection of PDOInterface
 */
abstract class AbstractConnector implements PDOInterface
{
    /**
     * @var ?TConnection
     */
    private ?PDOInterface $pdo = null;

    /**
     * Returns the connection, and connect to the database if not connected yet.
     *
     * @return TConnection
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
     * Disconnects from the database.
     */
    public function disconnect(): void
    {
        if ($this->pdo !== null) {
            $this->doDisconnect($this->pdo);
            $this->pdo = null;
        }
    }

    public function beginTransaction(): bool
    {
        return $this->getPdo()->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->getPdo()->commit();
    }

    public function errorCode(): ?string
    {
        return $this->getPdo()->errorCode();
    }

    public function errorInfo(): array
    {
        return $this->getPdo()->errorInfo();
    }

    public function exec(string $statement): int|false
    {
        return $this->getPdo()->exec($statement);
    }

    public function inTransaction(): bool
    {
        return $this->getPdo()->inTransaction();
    }

    public function lastInsertId(?string $name = null): string|false
    {
        return $this->getPdo()->lastInsertId();
    }

    public function prepare(string $query, array $options = []): PDOStatementInterface|false
    {
        return $this->getPdo()->prepare($query, $options);
    }

    public function query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs): PDOStatementInterface|false
    {
        return $this->getPdo()->query($query, $fetchMode, ...$fetchModeArgs);
    }

    public function quote(string $string, int $type = PDO::PARAM_STR): string|false
    {
        return $this->getPdo()->quote($string, $type);
    }

    public function rollBack(): bool
    {
        return $this->getPdo()->rollback();
    }

    /**
     * Connects to the database.
     *
     * @return TConnection
     */
    abstract protected function doConnect(): PDOInterface;

    /**
     * Disconnects the connection to the database.
     *
     * @param TConnection $pdo
     */
    abstract protected function doDisconnect(PDOInterface $pdo): void;
}
