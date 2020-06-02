<?php

namespace Emonkak\Database;

class StandardSavepoint implements SavepointInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(PDOInterface $pdo, string $name): void
    {
        $pdo->exec('SAVEPOINT ' . $name);
    }

    /**
     * {@inheritdoc}
     */
    public function release(PDOInterface $pdo, string $name): void
    {
        $pdo->exec('RELEASE SAVEPOINT ' . $name);
    }

    /**
     * {@inheritdoc}
     */
    public function rollbackTo(PDOInterface $pdo, string $name): void
    {
        $pdo->exec('ROLLBACK TO SAVEPOINT ' . $name);
    }
}
