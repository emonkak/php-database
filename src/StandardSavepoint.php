<?php

namespace Emonkak\Database;

class StandardSavepoint implements SavepointInterface
{
    public function create(PDOInterface $pdo, string $name): void
    {
        $pdo->exec('SAVEPOINT ' . $name);
    }

    public function release(PDOInterface $pdo, string $name): void
    {
        $pdo->exec('RELEASE SAVEPOINT ' . $name);
    }

    public function rollbackTo(PDOInterface $pdo, string $name): void
    {
        $pdo->exec('ROLLBACK TO SAVEPOINT ' . $name);
    }
}
