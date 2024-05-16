<?php

declare(strict_types=1);

namespace Emonkak\Database;

/**
 * A Connection that can be switched between databases depending on read/write.
 */
class MasterSlaveConnection implements PDOInterface
{
    private PDOInterface $masterPdo;

    private PDOInterface $slavePdo;

    private PDOInterface $activePdo;

    public function __construct(PDOInterface $masterPdo, PDOInterface $slavePdo)
    {
        $this->masterPdo = $masterPdo;
        $this->slavePdo = $slavePdo;
        $this->activePdo = $slavePdo;
    }

    public function getMasterPdo(): PDOInterface
    {
        return $this->masterPdo;
    }

    public function getSlavePdo(): PDOInterface
    {
        return $this->slavePdo;
    }

    public function beginTransaction(): bool
    {
        $result = $this->masterPdo->beginTransaction();
        $this->activePdo = $this->masterPdo;
        return $result;
    }

    public function commit(): bool
    {
        $this->activePdo = $this->slavePdo;
        return $this->masterPdo->commit();
    }

    public function errorCode(): ?string
    {
        return $this->activePdo->errorCode();
    }

    public function errorInfo(): array
    {
        return $this->activePdo->errorInfo();
    }

    public function exec(string $statement): int|false
    {
        return $this->activePdo->exec($statement);
    }

    public function inTransaction(): bool
    {
        return $this->masterPdo->inTransaction();
    }

    public function lastInsertId(?string $name = null): string|false
    {
        return $this->activePdo->lastInsertId();
    }

    public function prepare(string $query, array $options = []): PDOStatementInterface|false
    {
        return $this->activePdo->prepare($query);
    }

    public function query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs): PDOStatementInterface|false
    {
        return $this->activePdo->query($query, $fetchMode, ...$fetchModeArgs);
    }

    public function quote(string $string, int $type = \PDO::PARAM_STR): string|false
    {
        return $this->activePdo->quote($string, $type);
    }

    public function rollback(): bool
    {
        $this->activePdo = $this->slavePdo;
        return $this->masterPdo->rollback();
    }
}
