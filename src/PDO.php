<?php

namespace Emonkak\Database;

/**
 * The implementation of PDOInterface by PDO.
 */
class PDO extends \PDO implements PDOInterface
{
    public function __construct(string $dsn, ?string $user = null, ?string $password = null, array $options = null)
    {
        parent::__construct($dsn, $user, $password, $options);

        $this->setAttribute(
            \PDO::ATTR_STATEMENT_CLASS,
            [PDOStatement::class, []]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function query($statement, $param1 = null, $param2 = null, $param3 = null)
    {
        if ($param1 === null || $param2 === null) {
            /** @psalm-var PDOStatement|false */
            return parent::query($statement);
        }

        if ($param3 === null) {
            /** @psalm-var PDOStatement|false */
            return parent::query($statement, $param1, $param2);
        }

        /** @psalm-var PDOStatement|false */
        return parent::query($statement, $param1, $param2, $param3);
    }
}
