<?php

namespace Emonkak\Database;

class StandardSavepoint implements SavepointInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(PDOInterface $pdo, $name)
    {
        $pdo->exec('SAVEPOINT ' . $name);
    }

    /**
     * {@inheritDoc}
     */
    public function release(PDOInterface $pdo, $name)
    {
        $pdo->exec('RELEASE SAVEPOINT ' . $name);
    }

    /**
     * {@inheritDoc}
     */
    public function rollbackTo(PDOInterface $pdo, $name)
    {
        $pdo->exec('ROLLBACK TO SAVEPOINT ' . $name);
    }
}
