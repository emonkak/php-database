<?php

namespace Emonkak\Database;

class NestedTransaction implements PDOTransactionInterface
{
    /**
     * @var PDOInterface
     */
    private $pdo;

    /**
     * @var integer
     */
    private $level = 0;

    /**
     * @param PDOInterface $pdo
     */
    public function __construct(PDOInterface $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * {@inheritDoc}
     */
    public function beginTransaction()
    {
        if ($this->level > 0) {
            $this->pdo->exec('SAVEPOINT ' . $this->getSavepoint());
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
            $this->pdo->exec('RELEASE SAVEPOINT ' . $this->getSavepoint());
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
            $this->pdo->exec('ROLLBACK TO SAVEPOINT ' . $this->getSavepoint());
            return true;
        } else {
            return $this->pdo->rollback();
        }
    }

    /**
     * @return integer
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
