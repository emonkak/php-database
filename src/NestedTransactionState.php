<?php

declare(strict_types=1);

namespace Emonkak\Database;

class NestedTransactionState
{
    private int $level;

    public function __construct(int $level = 0)
    {
        $this->level = $level;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function incrementLevel(): void
    {
        $this->level++;
    }

    public function decrementLevel(): void
    {
        $this->level--;
    }
}
