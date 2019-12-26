<?php

namespace Emonkak\Database;

class NestedTransactionState
{
    /**
     * @var int
     */
    private $level;

    /**
     * @param int $level
     */
    public function __construct($level = 0)
    {
        $this->level = $level;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return void
     */
    public function incrementLevel()
    {
        $this->level++;
    }

    /**
     * @return void
     */
    public function decrementLevel()
    {
        $this->level--;
    }
}
