<?php

namespace Emonkak\Database;

class NestedTransaction implements PDOTransactionInterface
{
    private PDOInterface $pdo;

    private SavepointInterface $savepoint;

    private NestedTransactionState $state;

    public function __construct(PDOInterface $pdo, SavepointInterface $savepoint, ?NestedTransactionState $state = null)
    {
        $this->pdo = $pdo;
        $this->savepoint = $savepoint;
        $this->state = $state ?: new NestedTransactionState();
    }

    public function beginTransaction(): bool
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

    public function commit(): bool
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

    public function inTransaction(): bool
    {
        return $this->state->getLevel() > 0;
    }

    public function rollback(): bool
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
