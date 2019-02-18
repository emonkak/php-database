<?php

namespace Emonkak\Database;

interface SavepointInterface
{
    /**
     * @param PDOInterface $pdo
     * @param string $name
     */
    public function create(PDOInterface $pdo, $name);

    /**
     * @param PDOInterface $pdo
     * @param string $name
     */
    public function release(PDOInterface $pdo, $name);

    /**
     * @param PDOInterface $pdo
     * @param string $name
     */
    public function rollbackTo(PDOInterface $pdo, $name);
}
