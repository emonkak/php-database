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
     * @return boolean
     */
    public function beginTransaction();

    /**
     * Commits a transaction.
     *
     * @return boolean
     */
    public function commit();

    /**
     * Checks if inside a transaction.
     *
     * @return boolean
     */
    public function inTransaction();

    /**
     * Rolls back a transaction.
     *
     * @return boolean
     */
    public function rollback();
}
