<?php

namespace Emonkak\Database;

class NullSavepoint implements SavepointInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(PDOInterface $pdo, string $name): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function release(PDOInterface $pdo, string $name): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function rollbackTo(PDOInterface $pdo, string $name): void
    {
    }
}
