<?php

namespace Emonkak\Database;

interface PDOListenerInterface
{
    /**
     * Query has been executed.
     *
     * @param PDOInterface $pdo
     * @param string $queryString
     * @param mixed[] $bindings
     * @param int $time
     */
    public function onQuery(PDOInterface $pdo, $queryString, array $bindings, $time);

    /**
     * Transaction started.
     *
     * @param PDOInterface $pdo
     */
    public function onBeginTransaction(PDOInterface $pdo);

    /**
     * Transaction rolled back.
     *
     * @param PDOInterface $pdo
     */
    public function onRollback(PDOInterface $pdo);

    /**
     * Transaction committed.
     *
     * @param PDOInterface $pdo
     */
    public function onCommit(PDOInterface $pdo);
}
