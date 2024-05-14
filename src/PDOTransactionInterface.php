<?php

namespace Emonkak\Database;

/**
 * Provides the operations for database transaction.
 */
interface PDOTransactionInterface
{
    /**
     * Initiates a transaction.
     */
    public function beginTransaction(): bool;

    /**
     * Commits a transaction.
     */
    public function commit(): bool;

    /**
     * Checks if inside a transaction.
     */
    public function inTransaction(): bool;

    /**
     * Rolls back a transaction.
     */
    public function rollback(): bool;
}
