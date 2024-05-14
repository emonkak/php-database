<?php

namespace Emonkak\Database;

class NullSavepoint implements SavepointInterface
{
    public function create(PDOInterface $pdo, string $name): void
    {
    }

    public function release(PDOInterface $pdo, string $name): void
    {
    }

    public function rollbackTo(PDOInterface $pdo, string $name): void
    {
    }
}
