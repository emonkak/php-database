<?php

namespace Emonkak\Database;

interface SavepointInterface
{
    /**
     * @param PDOInterface $pdo
     */
    public function create(PDOInterface $pdo, string $name): void;

    /**
     * @param PDOInterface $pdo
     */
    public function release(PDOInterface $pdo, string $name): void;

    /**
     * @param PDOInterface $pdo
     */
    public function rollbackTo(PDOInterface $pdo, string $name): void;
}
