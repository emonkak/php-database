<?php

declare(strict_types=1);

namespace Emonkak\Database;

/**
 * A PDOInterface adapter for PDO.
 */
class PDOAdapter implements PDOInterface
{
    private \PDO|PDOInterface $pdo;

    /**
     * @param \PDO|PDOInterface $pdo
     */
    public function __construct($pdo)
    {
        if ($pdo instanceof \PDO) {
            $pdo->setAttribute(
                \PDO::ATTR_STATEMENT_CLASS,
                [PDOStatement::class, []]
            );
        }

        $this->pdo = $pdo;
    }

    public function getPdo(): \PDO|PDOInterface
    {
        return $this->pdo;
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function errorCode(): ?string
    {
        return $this->pdo->errorCode();
    }

    public function errorInfo(): array
    {
        return $this->pdo->errorInfo();
    }

    public function exec(string $statement): int|false
    {
        return $this->pdo->exec($statement);
    }

    public function inTransaction(): bool
    {
        return $this->pdo->inTransaction();
    }

    public function lastInsertId(?string $name = null): string|false
    {
        return $this->pdo->lastInsertId();
    }

    public function prepare(string $query, array $options = []): PDOStatementInterface|false
    {
        /** @var PDOStatement|false */
        return $this->pdo->prepare($query, $options);
    }

    public function query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs): PDOStatementInterface|false
    {
        if (is_null($fetchMode)) {
            /** @var PDOStatement|false */
            return $this->pdo->query($query);
        }
        /**
         * @var PDOStatement|false
         * @psalm-suppress TooManyArguments
         */
        return $this->pdo->query($query, $fetchMode, ...$fetchModeArgs);
    }

    public function quote(string $string, int $type = \PDO::PARAM_STR): string|false
    {
        return $this->pdo->quote($string, $type);
    }

    public function rollback(): bool
    {
        return $this->pdo->rollback();
    }
}
