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
     * @var boolean
     */
    private $in_transaction = false;

    /**
     * @param \mysqli $mysqli
     */
    public function __construct(\mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    /**
     * @return \mysqli
     */
    public function getMysqli()
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
        return array(
            $this->mysqli->sqlstate,
            $this->mysqli->errno,
            $this->mysqli->error,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function exec($statement)
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
    public function lastInsertId($name = null)
    {
        return $this->mysqli->insert_id;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($statement)
    {
        $stmt = $this->mysqli->prepare($statement);
        return new MysqliStmtAdapter($stmt);
    }

    /**
     * {@inheritdoc}
     */
    public function query($statement, $param1 = null, $param2 = null, $param3 = null)
    {
        $stmt = $this->prepare($statement);

        if ($param3 !== null) {
            $stmt->setFetchMode($param1, $param2, $param3);
        } elseif ($param2 !== null) {
            $stmt->setFetchMode($param1, $param2);
        } elseif ($param1 !== null) {
            $stmt->setFetchMode($param1);
        }

        $stmt->execute();

        return $stmt;
    }

    /**
     * {@inheritdoc}
     */
    public function quote($string, $parameter_type = null)
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
