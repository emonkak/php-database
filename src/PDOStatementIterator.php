<?php

namespace Emonkak\Database;

class PDOStatementIterator implements \Iterator
{
    /**
     * @var PDOStatementInterface
     */
    private $stmt;

    /**
     * @var mixed
     */
    private $current;

    /**
     * @var int
     */
    private $index = 0;

    public function __construct(PDOStatementInterface $stmt)
    {
        $this->stmt = $stmt;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->current = $this->stmt->fetch();
        $this->index++;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->current = $this->stmt->fetch();
        $this->index = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->current !== false;
    }
}
