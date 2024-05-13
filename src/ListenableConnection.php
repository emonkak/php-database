<?php

namespace Emonkak\Database;

class ListenableConnection implements PDOInterface
{
    private PDOInterface $delegate;

    /**
     * @var PDOListenerInterface[]
     */
    private array $listeners = [];

    public function __construct(PDOInterface $delegate)
    {
        $this->delegate = $delegate;
    }

    public function addListaner(PDOListenerInterface $listener): void
    {
        $this->listeners[] = $listener;
    }

    public function beginTransaction(): bool
    {
        foreach ($this->listeners as $listener) {
            $listener->onBeginTransaction($this->delegate);
        }

        return $this->delegate->beginTransaction();
    }

    public function commit(): bool
    {
        foreach ($this->listeners as $listener) {
            $listener->onCommit($this->delegate);
        }

        return $this->delegate->commit();
    }

    public function errorCode(): ?string
    {
        return $this->delegate->errorCode();
    }

    public function errorInfo(): array
    {
        return $this->delegate->errorInfo();
    }

    public function exec(string $statement): int|false
    {
        $start = microtime(true);

        $result = $this->delegate->exec($statement);

        $elapsedTime = microtime(true) - $start;

        foreach ($this->listeners as $listener) {
            $listener->onQuery($this->delegate, $statement, [], $elapsedTime);
        }

        return $result;
    }

    public function inTransaction(): bool
    {
        return $this->delegate->inTransaction();
    }

    public function lastInsertId(?string $name = null): string|false
    {
        return $this->delegate->lastInsertId();
    }

    public function prepare(string $query, array $options = []): PDOStatementInterface|false
    {
        $stmt = $this->delegate->prepare($query, $options);
        if ($stmt !== false) {
            $stmt = new ListenableStatement($this->delegate, $this->listeners, $stmt, $query);
        }
        return $stmt;
    }

    public function query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs): PDOStatementInterface|false
    {
        $start = microtime(true);

        $stmt = $this->delegate->query($query, $fetchMode, ...$fetchModeArgs);

        if ($stmt !== false) {
            $elapsedTime = microtime(true) - $start;

            foreach ($this->listeners as $listener) {
                $listener->onQuery($this->delegate, $query, [], $elapsedTime);
            }

            $stmt = new ListenableStatement($this->delegate, $this->listeners, $stmt, $query);
        }

        return $stmt;
    }

    public function quote(string $string, int $type = \PDO::PARAM_STR): string|false
    {
        return $this->delegate->quote($string, $type);
    }

    public function rollback(): bool
    {
        foreach ($this->listeners as $listener) {
            $listener->onRollback($this->delegate);
        }

        return $this->delegate->rollback();
    }
}
