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
    public function bindValue($parameter, $value, $data_type = \PDO::PARAM_STR)
    {
        $this->bindings[] = $value;

        return $this->delegate->bindValue($parameter, $value, $data_type);
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
    public function execute($input_parameters = null)
    {
        $start = microtime(true);

        try {
            return $this->delegate->execute($input_parameters);
        } finally {
            $elapsedTime = microtime(true) - $start;
            $bindings = $input_parameters !== null
                ? array_merge($this->bindings, $input_parameters)
                : $this->bindings;

            foreach ($this->listeners as $listener) {
                $listener->onQuery($this->pdo, $this->queryString, $bindings, $elapsedTime);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($fetch_style = null, $cursor_orientation = null, $cursor_offset = null)
    {
        return $this->delegate->Fetch($fetch_style, $cursor_orientation, $cursor_offset);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAll($fetch_style = null, $fetch_argument = null, $ctor_args = null)
    {
        return $this->delegate->FetchAll($fetch_style, $fetch_argument, $ctor_args);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchColumn($column_number = 0)
    {
        return $this->delegate->fetchColumn($column_number);
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
    public function setFetchMode($mode, $param1 = null, $param2 = null)
    {
        return $this->delegate->setFetchMode($mode, $param1, $param2);
    }
}
