<?php

namespace Emonkak\Database;

class ListenableConnection implements PDOInterface
{
    /**
     * @var PDOInterface
     */
    private $delegate;

    /**
     * @var PDOListenerInterface[]
     */
    private $listeners = array();

    /**
     * @param PDOInterface $delegate
     */
    public function __construct(PDOInterface $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * @param PDOListenerInterface $listener
     */
    public function addListaner(PDOListenerInterface $listener)
    {
        $this->listeners[] = $listener;
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction()
    {
        foreach ($this->listeners as $listener) {
            $listener->onBeginTransaction($this->delegate);
        }

        return $this->delegate->beginTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        foreach ($this->listeners as $listener) {
            $listener->onCommit($this->delegate);
        }

        return $this->delegate->commit();
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
    public function exec($statement)
    {
        $start = microtime(true);

        $result = $this->delegate->exec($statement);

        $elapsedTime = microtime(true) - $start;

        foreach ($this->listeners as $listener) {
            $listener->onQuery($this->delegate, $statement, array(), $elapsedTime);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function inTransaction()
    {
        return $this->delegate->inTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public function lastInsertId($name = null)
    {
        return $this->delegate->lastInsertId();
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($statement)
    {
        $stmt = $this->delegate->prepare($statement);
        return new ListenableStatement($this->delegate, $this->listeners, $stmt, $statement);
    }

    /**
     * {@inheritdoc}
     */
    public function query($statement, $param1 = null, $param2 = null, $param3 = null)
    {
        $start = microtime(true);

        $stmt = $this->delegate->query($statement, $param1, $param2, $param3);

        $elapsedTime = microtime(true) - $start;

        foreach ($this->listeners as $listener) {
            $listener->onQuery($this->delegate, $statement, array(), $elapsedTime);
        }

        return new ListenableStatement($this->delegate, $this->listeners, $stmt, $statement);
    }

    /**
     * {@inheritdoc}
     */
    public function quote($string, $parameter_type = null)
    {
        return $this->delegate->quote($string, $parameter_type);
    }

    /**
     * {@inheritdoc}
     */
    public function rollback()
    {
        foreach ($this->listeners as $listener) {
            $listener->onRollback($this->delegate);
        }

        return $this->delegate->rollback();
    }
}
