<?php

namespace Emonkak\Database;

/**
 * The adapter for mysqli.
 */
class MysqliAdapter implements PDOInterface
{
    private \mysqli $mysqli;

    private bool $in_transaction = false;

    public function __construct(\mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function getMysqli(): \mysqli
    {
        return $this->mysqli;
    }

    public function beginTransaction(): bool
    {
        if ($this->in_transaction) {
            throw new \RuntimeException('There is already an active transaction');
        }
        if (!$this->mysqli->real_query('START TRANSACTION')) {
            return false;
        }
        $this->in_transaction = true;
        return true;
    }

    public function commit(): bool
    {
        if (!$this->in_transaction) {
            throw new \RuntimeException('There is no active transaction');
        }
        $this->in_transaction = false;
        return $this->mysqli->commit();
    }

    public function errorCode(): ?string
    {
        return $this->mysqli->sqlstate;
    }

    public function errorInfo(): array
    {
        return [
            $this->mysqli->sqlstate,
            $this->mysqli->errno,
            $this->mysqli->error,
        ];
    }

    public function exec(string $statement): int|false
    {
        if (!$this->mysqli->real_query($statement)) {
            return false;
        }
        return max(0, (int) $this->mysqli->affected_rows);
    }

    public function inTransaction(): bool
    {
        return $this->in_transaction;
    }

    public function lastInsertId(?string $name = null): string|false
    {
        return (string) $this->mysqli->insert_id;
    }

    public function prepare(string $query, array $options = []): PDOStatementInterface|false
    {
        $stmt = $this->mysqli->prepare($query);
        return $stmt !== false ? new MysqliStmtAdapter($stmt) : false;
    }

    public function query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs): PDOStatementInterface|false
    {
        $stmt = $this->prepare($query);

        if ($stmt !== false) {
            if (!is_null($fetchMode)) {
                $stmt->setFetchMode($fetchMode, ...$fetchModeArgs);
            }

            $stmt->execute();
        }

        return $stmt;
    }

    public function quote(string $string, int $type = \PDO::PARAM_STR): string|false
    {
        return "'" . $this->mysqli->real_escape_string($string) . "'";
    }

    public function rollback(): bool
    {
        if (!$this->in_transaction) {
            throw new \RuntimeException('There is no active transaction');
        }
        $this->in_transaction = false;
        return $this->mysqli->rollback();
    }
}
