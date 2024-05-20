<?php

declare(strict_types=1);

namespace Emonkak\Database;

interface PDOListenerInterface
{
    /**
     * Fired when a query is executed.
     *
     * @param mixed[] $bindings
     */
    public function onQuery(PDOInterface $pdo, string $queryString, array $bindings, float $time): void;

    /**
     * Fired when a transaction is started.
     */
    public function onBeginTransaction(PDOInterface $pdo): void;

    /**
     * Fired when a transaction is rolled back.
     */
    public function onRollback(PDOInterface $pdo): void;

    /**
     * Fired when a transaction is committed.
     */
    public function onCommit(PDOInterface $pdo): void;
}
