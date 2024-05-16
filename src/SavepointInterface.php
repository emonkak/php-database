<?php

declare(strict_types=1);

namespace Emonkak\Database;

interface SavepointInterface
{
    public function create(PDOInterface $pdo, string $name): void;

    public function release(PDOInterface $pdo, string $name): void;

    public function rollbackTo(PDOInterface $pdo, string $name): void;
}
