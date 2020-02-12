<?php

namespace Emonkak\Database;

class NullSavepoint implements SavepointInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(PDOInterface $pdo, string $name): void
    {
    }

    /**
     * {@inheritDoc}
     */
    public function release(PDOInterface $pdo, string $name): void
    {
    }

    /**
     * {@inheritDoc}
     */
    public function rollbackTo(PDOInterface $pdo, string $name): void
    {
    }
}
