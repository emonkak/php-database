<?php

declare(strict_types=1);

namespace Emonkak\Database;

/**
 * @implements \IteratorAggregate<mixed,mixed>
 */
class ListenableStatement implements \IteratorAggregate, PDOStatementInterface
{
    private PDOInterface $pdo;

    /**
     * @var PDOListenerInterface[]
     */
    private array $listeners;

    private PDOStatementInterface $delegate;

    private string $queryString;

    private array $bindings = [];

    /**
     * @param PDOListenerInterface[] $listeners
     */
    public function __construct(PDOInterface $pdo, array $listeners, PDOStatementInterface $delegate, string $queryString)
    {
        $this->pdo = $pdo;
        $this->listeners = $listeners;
        $this->delegate = $delegate;
        $this->queryString = $queryString;
    }

    public function getIterator(): \Traversable
    {
        return $this->delegate;
    }

    public function bindValue(string|int $param, mixed $value, int $type = \PDO::PARAM_STR): bool
    {
        $this->bindings[] = $value;

        return $this->delegate->bindValue($param, $value, $type);
    }

    public function errorCode(): ?string
    {
        return $this->delegate->errorCode();
    }

    public function errorInfo(): array
    {
        return $this->delegate->errorInfo();
    }

    public function execute(?array $params = null): bool
    {
        $start = microtime(true);

        try {
            return $this->delegate->execute($params);
        } finally {
            $elapsedTime = microtime(true) - $start;
            $bindings = $params !== null
                ? array_merge($this->bindings, $params)
                : $this->bindings;

            foreach ($this->listeners as $listener) {
                $listener->onQuery($this->pdo, $this->queryString, $bindings, $elapsedTime);
            }
        }
    }

    public function fetch(int $mode = \PDO::ATTR_DEFAULT_FETCH_MODE, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): mixed
    {
        return $this->delegate->Fetch($mode, $cursorOrientation, $cursorOffset);
    }

    public function fetchAll(int $mode = \PDO::ATTR_DEFAULT_FETCH_MODE, mixed ...$args): array
    {
        return $this->delegate->FetchAll($mode, ...$args);
    }

    public function fetchColumn(int $column = 0): mixed
    {
        return $this->delegate->fetchColumn($column);
    }

    public function rowCount(): int
    {
        return $this->delegate->rowCount();
    }

    public function setFetchMode(int $mode, mixed ...$args): bool
    {
        return $this->delegate->setFetchMode($mode, ...$args);
    }
}
