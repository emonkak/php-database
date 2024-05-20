<?php

declare(strict_types=1);

namespace Emonkak\Database;

/**
 * An interface as a subset of PDO.
 */
interface PDOInterface extends PDOTransactionInterface
{
    /**
     * Fetch the SQLSTATE associated with the last operation on the database
     * handle.
     */
    public function errorCode(): ?string;

    /**
     * Fetch extended error information associated with the last operation on
     * the statement handle.
     */
    public function errorInfo(): array;

    /**
     * Execute an SQL statement and return the number of affected rows.
     */
    public function exec(string $statement): int|false;

    /**
     * Returns the ID of the last inserted row or sequence value.
     */
    public function lastInsertId(?string $name = null): string|false;

    /**
     * Prepares a statement for execution and returns a statement object.
     *
     * NOTE: The return type is different from PDO's \PDOStatement|false, so
     * there is no type hint.
     *
     * @return PDOStatementInterface|false
     */
    public function prepare(string $query, array $options = []);

    /**
     * Executes an SQL statement, returning a result set as a statement object.
     *
     * NOTE: The return type is different from PDO's \PDOStatement|false, so
     * there is no type hint.
     *
     * @return PDOStatementInterface|false
     */
    public function query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs);

    /**
     * Quotes a string for use in a query.
     */
    public function quote(string $string, int $type = PDO::PARAM_STR): string|false;
}
