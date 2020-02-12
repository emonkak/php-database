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
     * @var NestedTransactionState
     */
    private $state;

    public function __construct(PDOInterface $pdo, SavepointInterface $savepoint, ?NestedTransactionState $state = null)
    {
        $this->pdo = $pdo;
        $this->savepoint = $savepoint;
        $this->state = $state ?: new NestedTransactionState();
    }

    /**
     * {@inheritDoc}
     */
    public function beginTransaction()
    {
        if ($this->state->getLevel() > 0) {
            $this->savepoint->create($this->pdo, $this->getSavepointName());
            $result = true;
        } else {
            $result = $this->pdo->beginTransaction();
        }

        $this->state->incrementLevel();

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function commit()
    {
        if ($this->state->getLevel() > 0) {
            $this->state->decrementLevel();
        }

        if ($this->state->getLevel() > 0) {
            $this->savepoint->release($this->pdo, $this->getSavepointName());
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
        return $this->state->getLevel() > 0;
    }

    /**
     * {@inheritDoc}
     */
    public function rollback()
    {
        if ($this->state->getLevel() > 0) {
            $this->state->decrementLevel();
        }

        if ($this->state->getLevel() > 0) {
            $this->savepoint->rollbackTo($this->pdo, $this->getSavepointName());
            return true;
        } else {
            return $this->pdo->rollback();
        }
    }

    public function getTransactionLevel(): int
    {
        return $this->state->getLevel();
    }

    private function getSavepointName(): string
    {
        return 'level_' . $this->state->getLevel();
    }
}
