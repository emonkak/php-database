<?php

namespace Emonkak\Database;

interface PDOListenerInterface
{
    /**
     * Query has been executed.
     *
     * @param mixed[] $bindings
     */
    public function onQuery(PDOInterface $pdo, string $queryString, array $bindings, float $time): void;

    /**
     * Transaction started.
     */
    public function onBeginTransaction(PDOInterface $pdo): void;

    /**
     * Transaction rolled back.
     */
    public function onRollback(PDOInterface $pdo): void;

    /**
     * Transaction committed.
     */
    public function onCommit(PDOInterface $pdo): void;
}
