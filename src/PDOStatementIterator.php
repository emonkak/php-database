<?php

namespace Emonkak\Database;

class PDOStatementIterator implements \Iterator
{
    /**
     * @var PDOStatementIterator
     */
    private $stmt;

    /**
     * @var mixed
     */
    private $current;

    /**
     * @var integer
     */
    private $index;

    /**
     * @param PDOStatementInterface $stmt
     */
    public function __construct(PDOStatementInterface $stmt)
    {
        $this->stmt = $stmt;
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        $this->current = $this->stmt->fetch();
        $this->index++;
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        $this->current = $this->stmt->fetch();
        $this->index = 0;
    }

    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return $this->current !== false;
    }
}
