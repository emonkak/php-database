<?php

namespace Emonkak\Database;

/**
 * Provides the switching between master DB and slave DB according to a transaction state.
 */
class MasterSlaveConnection implements PDOInterface
{
    /**
     * @var PDOInterface
     */
    private $masterPdo;

    /**
     * @var PDOInterface
     */
    private $slavePdo;

    /**
     * @var PDOInterface
     */
    private $activePdo;

    public function __construct(PDOInterface $masterPdo, PDOInterface $slavePdo)
    {
        $this->masterPdo = $masterPdo;
        $this->slavePdo = $slavePdo;
        $this->activePdo = $slavePdo;
    }

    public function getMasterPdo(): PDOInterface
    {
        return $this->masterPdo;
    }

    public function getSlavePdo(): PDOInterface
    {
        return $this->slavePdo;
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction()
    {
        $result = $this->masterPdo->beginTransaction();
        $this->activePdo = $this->masterPdo;
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        $this->activePdo = $this->slavePdo;
        return $this->masterPdo->commit();
    }

    /**
     * {@inheritdoc}
     */
    public function errorCode()
    {
        return $this->activePdo->errorCode();
    }

    /**
     * {@inheritdoc}
     */
    public function errorInfo()
    {
        return $this->activePdo->errorInfo();
    }

    /**
     * {@inheritdoc}
     */
    public function exec(string $statement)
    {
        return $this->activePdo->exec($statement);
    }

    /**
     * {@inheritdoc}
     */
    public function inTransaction()
    {
        return $this->masterPdo->inTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public function lastInsertId(?string $name = null)
    {
        return $this->activePdo->lastInsertId();
    }

    /**
     * {@inheritdoc}
     */
    public function prepare(string $statement, array $options = [])
    {
        return $this->activePdo->prepare($statement);
    }

    /**
     * {@inheritdoc}
     */
    public function query(string $query, ?int $fetchMode = null, ...$fetchModeArgs)
    {
        return $this->activePdo->query($query, $fetchMode, ...$fetchModeArgs);
    }

    /**
     * {@inheritdoc}
     */
    public function quote(string $string, int $type = \PDO::PARAM_STR)
    {
        return $this->activePdo->quote($string, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function rollback()
    {
        $this->activePdo = $this->slavePdo;
        return $this->masterPdo->rollback();
    }
}
