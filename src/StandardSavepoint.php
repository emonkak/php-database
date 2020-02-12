<?php

namespace Emonkak\Database;

class StandardSavepoint implements SavepointInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(PDOInterface $pdo, string $name): void
    {
        $pdo->exec('SAVEPOINT ' . $name);
    }

    /**
     * {@inheritDoc}
     */
    public function release(PDOInterface $pdo, string $name): void
    {
        $pdo->exec('RELEASE SAVEPOINT ' . $name);
    }

    /**
     * {@inheritDoc}
     */
    public function rollbackTo(PDOInterface $pdo, string $name): void
    {
        $pdo->exec('ROLLBACK TO SAVEPOINT ' . $name);
    }
}
