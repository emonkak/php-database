<?php

namespace Emonkak\Database;

/**
 * Provides the switching between master DB and slave DB according to a
 * transaction state.
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

    /**
     * @param PDOInterface $masterPdo
     * @param PDOInterface $slavePdo
     */
    public function __construct(PDOInterface $masterPdo, PDOInterface $slavePdo)
    {
        $this->masterPdo = $masterPdo;
        $this->slavePdo = $slavePdo;
        $this->activePdo = $slavePdo;
    }

    /**
     * @return PDOInterface
     */
    public function getMasterPdo()
    {
        return $this->masterPdo;
    }

    /**
     * @return PDOInterface
     */
    public function getSlavePdo()
    {
        return $this->slavePdo;
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction()
    {
        $this->activePdo = $this->masterPdo;
        try {
            return $this->masterPdo->beginTransaction();
        } catch (\Exception $e) {
            $this->activePdo = $this->slavePdo;
            throw $e;
        }
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
    public function exec($statement)
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
    public function lastInsertId($name = null)
    {
        return $this->activePdo->lastInsertId();
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($statement)
    {
        return $this->activePdo->prepare($statement);
    }

    /**
     * {@inheritdoc}
     */
    public function query($statement, $param1 = null, $param2 = null, $param3 = null)
    {
        return $this->activePdo->query($statement, $param1, $param2, $param3);
    }

    /**
     * {@inheritdoc}
     */
    public function quote($string, $parameter_type = null)
    {
        return $this->activePdo->quote($string, $parameter_type);
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
