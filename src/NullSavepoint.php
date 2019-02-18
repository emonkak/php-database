<?php

namespace Emonkak\Database;

class NullSavepoint implements SavepointInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(PDOInterface $pdo, $name)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function release(PDOInterface $pdo, $name)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function rollbackTo(PDOInterface $pdo, $name)
    {
    }
}
