<?php

namespace Emonkak\Database;

/**
 * A PDOStatement adapter for mysqli_stmt.
 *
 * @implements \IteratorAggregate<mixed,mixed>
 */
class MysqliStmtAdapter implements \IteratorAggregate, PDOStatementInterface
{
    private \mysqli_stmt $stmt;

    private ?\mysqli_result $result = null;

    private int $fetch_style = \PDO::FETCH_BOTH;

    private mixed $fetch_argument = null;

    private mixed $ctor_args = null;

    private string $bind_types = '';

    private array $bind_values = [];

    public function __construct(\mysqli_stmt $stmt)
    {
        $this->stmt = $stmt;
    }

    public function __destruct()
    {
        if ($this->result !== null) {
            $this->result->free();
        }
    }

    public function getIterator(): \Traversable
    {
        return new PDOStatementIterator($this);
    }

    public function bindValue(string|int $param, mixed $value, int $type = \PDO::PARAM_STR): bool
    {
        switch ($type) {
            case \PDO::PARAM_BOOL:
                $this->bind_types .= 'i';
                $this->bind_values[] = $value ? 1 : 0;
                break;

            case \PDO::PARAM_NULL:
                $this->bind_types .= 'i';
                $this->bind_values[] = null;
                break;

            case \PDO::PARAM_INT:
                $this->bind_types .= 'i';
                $this->bind_values[] = $value;
                break;

                // case \PDO::PARAM_STR:
                // case \PDO::PARAM_LOB:
            default:
                $this->bind_types .= is_double($value) ? 'd' : 's';
                $this->bind_values[] = $value;
                break;
        }
        return true;
    }

    public function errorCode(): ?string
    {
        return $this->stmt->sqlstate;
    }

    public function errorInfo(): array
    {
        return [
            $this->stmt->sqlstate,
            $this->stmt->errno,
            $this->stmt->error,
        ];
    }

    public function execute(?array $params = null): bool
    {
        $bind_types = $this->bind_types;
        $bind_params = [&$bind_types];

        if ($params !== null) {
            foreach ($params as $i => $_) {
                $bind_types .= 's';
                /** @psalm-suppress UnsupportedReferenceUsage */
                $bind_params[] = &$params[$i];
            }
        }

        if ($bind_types !== '') {
            foreach ($this->bind_values as $i => $_) {
                /** @psalm-suppress UnsupportedReferenceUsage */
                $bind_params[] = &$this->bind_values[$i];
            }
            $this->stmt->bind_param(...$bind_params);
        }

        if (!$this->stmt->execute()) {
            return false;
        }

        $this->result = $this->stmt->get_result();

        return true;
    }

    public function fetch(int $mode = \PDO::ATTR_DEFAULT_FETCH_MODE, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): mixed
    {
        if ($this->result === null) {
            return false;
        }

        if ($mode === \PDO::ATTR_DEFAULT_FETCH_MODE) {
            $mode = $this->fetch_style;
        }

        switch ($mode) {
            case \PDO::FETCH_BOTH:
                return $this->result->fetch_array(MYSQLI_BOTH) ?? false;

            case \PDO::FETCH_ASSOC:
                return $this->result->fetch_array(MYSQLI_ASSOC) ?? false;

            case \PDO::FETCH_NUM:
                return $this->result->fetch_array(MYSQLI_NUM) ?? false;

            case \PDO::FETCH_CLASS:
                /** @var string */
                $class = $cursorOrientation ?: $this->fetch_argument ?: \stdClass::class;
                /** @var ?array */
                $params = $cursorOffset ?: $this->ctor_args;
                if ($params !== null) {
                    $result = $this->result->fetch_object($class, $params) ?: false;
                } else {
                    $result = $this->result->fetch_object($class) ?: false;
                }
                return $result;
            case \PDO::FETCH_COLUMN:
                $column_number = $cursorOrientation ?: $this->fetch_argument ?: 0;
                return $this->doFetchColumn($this->result, $column_number);
        }

        throw new \ValueError("Unsupported fetch style, got '$mode'");
    }

    public function fetchAll(int $mode = \PDO::ATTR_DEFAULT_FETCH_MODE, mixed ...$args): array
    {
        if ($this->result === null) {
            return [];
        }

        if ($mode === \PDO::ATTR_DEFAULT_FETCH_MODE) {
            $mode = $this->fetch_style;
        }

        switch ($mode) {
            case \PDO::FETCH_BOTH:
                return $this->result->fetch_all(MYSQLI_BOTH);

            case \PDO::FETCH_ASSOC:
                return $this->result->fetch_all(MYSQLI_ASSOC);

            case \PDO::FETCH_NUM:
                return $this->result->fetch_all(MYSQLI_NUM);

            case \PDO::FETCH_CLASS:
                $fetch_argument = $args[0] ?? null;
                $ctor_args = $args[1] ?? null;
                /** @var string */
                $class = $fetch_argument ?? $this->fetch_argument ?? \stdClass::class;
                $params = $ctor_args ?? $this->ctor_args;
                $rows = [];
                if ($params !== null) {
                    assert(is_array($params));
                    while (($row = $this->result->fetch_object($class, $params)) !== null) {
                        $rows[] = $row;
                    }
                } else {
                    while (($row = $this->result->fetch_object($class)) !== null) {
                        $rows[] = $row;
                    }
                }
                return $rows;

            case \PDO::FETCH_COLUMN:
                $fetch_argument = $args[0] ?? null;
                $columns = [];
                $column_number = $fetch_argument ?? $this->fetch_argument ?: 0;
                while (($row = $this->result->fetch_array(MYSQLI_NUM)) !== null) {
                    if (!isset($row[$column_number])) {
                        throw new \ValueError('Invalid column index');
                    }
                    $columns[] = $row[$column_number];
                }
                return $columns;
        }

        throw new \ValueError("Unsupported fetch style, got '$mode'");
    }

    public function fetchColumn(int $column = 0): mixed
    {
        if ($this->result === null) {
            return false;
        }
        return $this->doFetchColumn($this->result, $column);
    }

    public function rowCount(): int
    {
        return (int) $this->stmt->affected_rows;
    }

    public function setFetchMode(int $mode, mixed ...$args): bool
    {
        $this->fetch_style = $mode;
        $this->fetch_argument = $args[0] ?? null;
        $this->ctor_args = $args[1] ?? null;
        return true;
    }

    private function doFetchColumn(\mysqli_result $result, int $column_number): mixed
    {
        $row = $result->fetch_array(MYSQLI_NUM);
        if ($row === null) {
            return false;
        }
        if (!isset($row[$column_number])) {
            return false;
        }
        return $row[$column_number];
    }
}
