<?php

namespace Emonkak\Database;

class NestedTransaction implements PDOTransactionInterface
{
    /**
     * @var PDOInterface
     */
    private $pdo;

    /**
     * @var SavepointInterface
     */
    private $savepoint;

    /**
     * @var integer
     */
    private $level = 0;

    /**
     * @param PDOInterface $pdo
     * @param SavepointInterface $savepoint
     */
    public function __construct(PDOInterface $pdo, SavepointInterface $savepoint)
    {
        $this->pdo = $pdo;
        $this->savepoint = $savepoint;
    }

    /**
     * {@inheritDoc}
     */
    public function beginTransaction()
    {
        if ($this->level > 0) {
            $this->savepoint->create($this->pdo, $this->getSavepoint());
            $result = true;
        } else {
            $result = $this->pdo->beginTransaction();
        }

        $this->level++;

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function commit()
    {
        if ($this->level > 0) {
            $this->level--;
        }

        if ($this->level > 0) {
            $this->savepoint->release($this->pdo, $this->getSavepoint());
            return true;
        } else {
            return $this->pdo->commit();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function inTransaction()
    {
        return $this->level > 0;
    }

    /**
     * {@inheritDoc}
     */
    public function rollback()
    {
        if ($this->level > 0) {
            $this->level--;
        }

        if ($this->level > 0) {
            $this->savepoint->rollbackTo($this->pdo, $this->getSavepoint());
            return true;
        } else {
            return $this->pdo->rollback();
        }
    }

    /**
     * @return int
     */
    public function getTransactionLevel()
    {
        return $this->level;
    }

    /**
     * @return string
     */
    private function getSavepoint()
    {
        return 'level_' . $this->level;
    }
}
