<?php

namespace Emonkak\Database;

/**
 * The adapter for PDO instance.
 */
class PDOAdapter implements PDOInterface
{
    /**
     * @var \PDO|PDOInterface
     */
    private $pdo;

    /**
     * @param \PDO|PDOInterface $pdo
     */
    public function __construct($pdo)
    {
        if ($pdo instanceof \PDO) {
            $pdo->setAttribute(
                \PDO::ATTR_STATEMENT_CLASS,
                [PDOStatement::class, []]
            );
        }

        $this->pdo = $pdo;
    }

    /**
     * @return \PDO|PDOInterface
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        return $this->pdo->commit();
    }

    /**
     * {@inheritdoc}
     */
    public function errorCode()
    {
        return $this->pdo->errorCode();
    }

    /**
     * {@inheritdoc}
     */
    public function errorInfo()
    {
        return $this->pdo->errorInfo();
    }

    /**
     * {@inheritdoc}
     */
    public function exec($statement)
    {
        return $this->pdo->exec($statement);
    }

    /**
     * {@inheritdoc}
     */
    public function inTransaction()
    {
        return $this->pdo->inTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public function lastInsertId($name = null)
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($statement)
    {
        /** @psalm-var PDOStatement|false */
        return $this->pdo->prepare($statement);
    }

    /**
     * {@inheritdoc}
     */
    public function query($statement, $param1 = null, $param2 = null, $param3 = null)
    {
        if ($param1 === null || $param2 === null) {
            /** @psalm-var PDOStatement|false */
            return $this->pdo->query($statement);
        }

        if ($param3 === null) {
            /** @psalm-var PDOStatement|false */
            return $this->pdo->query($statement, $param1, $param2);
        }

        /** @psalm-var PDOStatement|false */
        return $this->pdo->query($statement, $param1, $param2, $param3);
    }

    /**
     * {@inheritdoc}
     */
    public function quote($string, $parameter_type = \PDO::PARAM_STR)
    {
        return $this->pdo->quote($string, $parameter_type);
    }

    /**
     * {@inheritdoc}
     */
    public function rollback()
    {
        return $this->pdo->rollback();
    }
}
