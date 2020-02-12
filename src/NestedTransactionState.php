<?php

namespace Emonkak\Database;

class NestedTransactionState
{
    /**
     * @var int
     */
    private $level;

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
