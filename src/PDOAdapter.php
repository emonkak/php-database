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
    public function exec(string $statement)
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
    public function lastInsertId(?string $name = null)
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * {@inheritdoc}
     */
    public function prepare(string $statement, array $options = [])
    {
        /** @psalm-var PDOStatement|false */
        return $this->pdo->prepare($statement, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs)
    {
        if (is_null($fetchMode)) {
            /** @psalm-var PDOStatement|false */
            return $this->pdo->query($query);
        }
        /** @psalm-var PDOStatement|false */
        return $this->pdo->query($query, $fetchMode, ...$fetchModeArgs);
    }

    /**
     * {@inheritdoc}
     */
    public function quote(string $string, int $type = \PDO::PARAM_STR)
    {
        return $this->pdo->quote($string, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function rollback()
    {
        return $this->pdo->rollback();
    }
}
