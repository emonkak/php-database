<?php

declare(strict_types=1);

namespace Emonkak\Database;

class PDO extends \PDO implements PDOInterface
{
    public function __construct(string $dsn, ?string $user = null, ?string $password = null, ?array $options = null)
    {
        parent::__construct($dsn, $user, $password, $options);

        $this->setAttribute(
            \PDO::ATTR_STATEMENT_CLASS,
            [PDOStatement::class, []]
        );
    }
}
