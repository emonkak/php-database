# Emonkak\Database

[![CI Status](https://github.com/emonkak/php-database/actions/workflows/ci.yml/badge.svg)](https://travis-ci.org/emonkak/php-database)
[![Coverage Status](https://coveralls.io/repos/github/emonkak/php-database/badge.svg)](https://coveralls.io/github/emonkak/php-database)

This package provides a database abstraction as a subset of PDO.

## API

### Emonkak\Database\PDOInterface

```php
interface PDOInterface extends PDOTransactionInterface
{
    public function errorCode(): ?string;

    public function errorInfo(): array;

    public function exec(string $statement): int|false;

    public function lastInsertId(?string $name = null): string|false;

    public function prepare(string $query, array $options = []): PDOStatementInterface|false;

    public function query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs): PDOStatementInterface|false;

    public function quote(string $string, int $type = PDO::PARAM_STR): string|false;
}
```

### Emonkak\Database\PDOTransactionInterface

```php
interface PDOTransactionInterface
{
    public function beginTransaction(): bool;

    public function commit(): bool;

    public function inTransaction(): bool;

    public function rollback(): bool;
}
```

### Emonkak\Database\PDOStatementInterface

```php
interface PDOStatementInterface extends \Traversable
{
    public function bindValue(string|int $param, mixed $value, int $type = \PDO::PARAM_STR): bool;

    public function errorCode(): ?string;

    public function errorInfo(): array;

    public function execute(?array $params = null): bool;

    public function fetch(int $mode = \PDO::ATTR_DEFAULT_FETCH_MODE, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): mixed;

    public function fetchAll(int $mode = \PDO::ATTR_DEFAULT_FETCH_MODE, mixed ...$args): array;

    public function fetchColumn(int $column = 0): mixed;

    public function rowCount(): int;

    public function setFetchMode(int $mode, mixed ...$args): true;
}
```
