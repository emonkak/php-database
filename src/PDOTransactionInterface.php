<?php

namespace Emonkak\Database;

/**
 * Represents the database transaction handling.
 */
interface PDOTransactionInterface
{
    /**
     * Initiates a transaction.
     *
     * @return bool
     */
    public function beginTransaction();

    /**
     * Commits a transaction.
     *
     * @return bool
     */
    public function commit();

    /**
     * Checks if inside a transaction.
     *
     * @return bool
     */
    public function inTransaction();

    /**
     * Rolls back a transaction.
     *
     * @return bool
     */
    public function rollback();
}
