<?php

declare(strict_types=1);

namespace Emonkak\Database;

/**
 * @implements \Iterator<mixed,mixed>
 */
class PDOStatementIterator implements \Iterator
{
    private PDOStatementInterface $stmt;

    private mixed $current;

    private int $index = 0;

    public function __construct(PDOStatementInterface $stmt)
    {
        $this->stmt = $stmt;
    }

    public function current(): mixed
    {
        return $this->current;
    }

    public function key(): mixed
    {
        return $this->index;
    }

    public function next(): void
    {
        $this->current = $this->stmt->fetch();
        $this->index++;
    }

    public function rewind(): void
    {
        $this->current = $this->stmt->fetch();
        $this->index = 0;
    }

    public function valid(): bool
    {
        return $this->current !== false;
    }
}
