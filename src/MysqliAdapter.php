<?php

namespace Emonkak\Database;

/**
 * PDOInterface adapter for mysqli.
 */
class MysqliAdapter implements PDOInterface
{
    /**
     * @var \mysqli
     */
    private $mysqli;

    /**
     * @var bool
     */
    private $in_transaction = false;

    public function __construct(\mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function getMysqli(): \mysqli
    {
        return $this->mysqli;
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction()
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

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        if (!$this->in_transaction) {
            throw new \RuntimeException('There is no active transaction');
        }
        $this->in_transaction = false;
        return $this->mysqli->commit();
    }

    /**
     * {@inheritdoc}
     */
    public function errorCode()
    {
        return $this->mysqli->sqlstate;
    }

    /**
     * {@inheritdoc}
     */
    public function errorInfo()
    {
        return [
            $this->mysqli->sqlstate,
            $this->mysqli->errno,
            $this->mysqli->error,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function exec(string $statement)
    {
        if (!$this->mysqli->real_query($statement)) {
            return false;
        }
        return max(0, $this->mysqli->affected_rows);
    }

    /**
     * {@inheritdoc}
     */
    public function inTransaction()
    {
        return $this->in_transaction;
    }

    /**
     * {@inheritdoc}
     */
    public function lastInsertId(?string $name = null)
    {
        return (string) $this->mysqli->insert_id;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare(string $statement, array $options = [])
    {
        $stmt = $this->mysqli->prepare($statement);
        return $stmt !== false ? new MysqliStmtAdapter($stmt) : false;
    }

    /**
     * {@inheritdoc}
     */
    public function query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs)
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

    /**
     * {@inheritdoc}
     */
    public function quote(string $string, int $type = \PDO::PARAM_STR)
    {
        return "'" . $this->mysqli->real_escape_string($string) . "'";
    }

    /**
     * {@inheritdoc}
     */
    public function rollback()
    {
        if (!$this->in_transaction) {
            throw new \RuntimeException('There is no active transaction');
        }
        $this->in_transaction = false;
        return $this->mysqli->rollback();
    }
}
