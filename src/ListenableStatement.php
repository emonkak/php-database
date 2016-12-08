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
    private $bindings = array();

    /**
     * @param PDOInterface           $pdo
     * @param PDOListenerInterface[] $listeners
     * @param PDOStatementInterface  $delegate
     * @param string                 $queryString
     */
    public function __construct(PDOInterface $pdo, array $listeners, PDOStatementInterface $delegate, $queryString)
    {
        $this->pdo = $pdo;
        $this->listeners = $listeners;
        $this->delegate = $delegate;
        $this->queryString = $queryString;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return $this->delegate;
    }

    /**
     * {@inheritDoc}
     */
    public function bindValue($parameter, $value, $data_type = \PDO::PARAM_STR)
    {
        $this->bindings[] = $value;

        return $this->delegate->bindValue($parameter, $value, $data_type);
    }

    /**
     * {@inheritDoc}
     */
    public function errorCode()
    {
        return $this->delegate->errorCode();
    }

    /**
     * {@inheritDoc}
     */
    public function errorInfo()
    {
        return $this->delegate->errorInfo();
    }

    /**
     * {@inheritDoc}
     */
    public function execute($input_parameters = null)
    {
        $start = microtime(true);

        $result = $this->delegate->execute($input_parameters);

        $elapsedTime = microtime(true) - $start;
        $bindings = $input_parameters !== null ? array_merge($this->bindings, $input_parameters) : $this->bindings;

        foreach ($this->listeners as $listener) {
            $listener->onQuery($this->pdo, $this->queryString, $bindings, $elapsedTime);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function fetch($fetch_style = null, $cursor_orientation = null, $cursor_offset = null)
    {
        return $this->delegate->Fetch($fetch_style, $cursor_orientation, $cursor_offset);
    }

    /**
     * {@inheritDoc}
     */
    public function fetchAll($fetch_style = null, $fetch_argument = null, $ctor_args = null)
    {
        return $this->delegate->FetchAll($fetch_style, $fetch_argument, $ctor_args);
    }

    /**
     * {@inheritDoc}
     */
    public function fetchColumn($column_number = 0)
    {
        return $this->delegate->fetchColumn($column_number);
    }

    /**
     * {@inheritDoc}
     */
    public function rowCount()
    {
        return $this->delegate->rowCount();
    }

    /**
     * {@inheritDoc}
     */
    public function setFetchMode($mode, $param1 = null, $param2 = null)
    {
        return $this->delegate->setFetchMode($mode, $param1, $param2);
    }
}
