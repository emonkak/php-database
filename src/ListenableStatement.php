<?php

namespace Emonkak\Database;

class ListenableStatement implements \IteratorAggregate, PDOStatementInterface
{
    /**
     * @var PDOInterface
     */
    private $pdo;

    /**
     * @var PDOListenerInterface[]
     */
    private $listeners;

    /**
     * @var PDOStatementInterface
     */
    private $delegate;

    /**
     * @var string
     */
    private $queryString;

    /**
     * @var mixed[]
     */
    private $bindings = [];

    /**
     * @param PDOListenerInterface[] $listeners
     */
    public function __construct(PDOInterface $pdo, array $listeners, PDOStatementInterface $delegate, string $queryString)
    {
        $this->pdo = $pdo;
        $this->listeners = $listeners;
        $this->delegate = $delegate;
        $this->queryString = $queryString;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->delegate;
    }

    /**
     * {@inheritdoc}
     */
    public function bindValue(string $param, $value, int $type = \PDO::PARAM_STR)
    {
        $this->bindings[] = $value;

        return $this->delegate->bindValue($param, $value, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function errorCode()
    {
        return $this->delegate->errorCode();
    }

    /**
     * {@inheritdoc}
     */
    public function errorInfo()
    {
        return $this->delegate->errorInfo();
    }

    /**
     * {@inheritdoc}
     */
    public function execute(?array $params = null)
    {
        $start = microtime(true);

        try {
            return $this->delegate->execute($params);
        } finally {
            $elapsedTime = microtime(true) - $start;
            $bindings = $params !== null
                ? array_merge($this->bindings, $params)
                : $this->bindings;

            foreach ($this->listeners as $listener) {
                $listener->onQuery($this->pdo, $this->queryString, $bindings, $elapsedTime);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(int $mode = \PDO::ATTR_DEFAULT_FETCH_MODE, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0)
    {
        return $this->delegate->Fetch($mode, $cursorOrientation, $cursorOffset);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAll(int $mode = \PDO::ATTR_DEFAULT_FETCH_MODE, ...$args)
    {
        return $this->delegate->FetchAll($mode, ...$args);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchColumn(int $column = 0)
    {
        return $this->delegate->fetchColumn($column);
    }

    /**
     * {@inheritdoc}
     */
    public function rowCount()
    {
        return $this->delegate->rowCount();
    }

    /**
     * {@inheritdoc}
     */
    public function setFetchMode(int $mode, ...$args)
    {
        return $this->delegate->setFetchMode($mode, ...$args);
    }
}
