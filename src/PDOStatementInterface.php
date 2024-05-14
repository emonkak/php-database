<?php

namespace Emonkak\Database;

/**
 * The interface as a subset of PDOStatement.
 *
 * @extends \Traversable<mixed,mixed>
 */
interface PDOStatementInterface extends \Traversable
{
    /**
     * Binds a value to a parameter.
     */
    public function bindValue(string|int $param, mixed $value, int $type = \PDO::PARAM_STR): bool;

    /**
     * Fetch the SQLSTATE associated with the last operation on the statement
     * handle.
     */
    public function errorCode(): ?string;

    /**
     * Fetch extended error information associated with the last operation on
     * the database handle.
     */
    public function errorInfo(): array;

    /**
     * Executes a prepared statement.
     */
    public function execute(?array $params = null): bool;

    /**
     * Fetches the next row from a result set.
     */
    public function fetch(int $mode = \PDO::ATTR_DEFAULT_FETCH_MODE, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): mixed;

    /**
     * Returns an array containing all of the result set rows.
     */
    public function fetchAll(int $mode = \PDO::ATTR_DEFAULT_FETCH_MODE, mixed ...$args): array;

    /**
     * Returns a single column from the next row of a result set.
     */
    public function fetchColumn(int $column = 0): mixed;

    /**
     * Returns the number of rows affected by the last SQL statement.
     */
    public function rowCount(): int;

    /**
     * Returns the number of rows affected by the last SQL statement.
     *
     * @return true
     */
    public function setFetchMode(int $mode, mixed ...$args);
}
