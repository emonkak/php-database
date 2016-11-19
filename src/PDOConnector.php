<?php

namespace Emonkak\Database;

class PDOConnector extends AbstractConnector
{
    /**
     * @var string
     */
    private $dsn;

    /**
     * @var string|null
     */
    private $user;

    /**
     * @var string|null
     */
    private $password;

    /**
     * @var array|null
     */
    private $options;

    /**
     * @param string      $dsn
     * @param string|null $user
     * @param string|null $password
     * @param array|null  $options
     */
    public function __construct($dsn, $user = null, $password = null, array $options = null)
    {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $password;
        $this->options = $options;
    }

    public function __sleep()
    {
        return array('dsn', 'user', 'password', 'options');
    }

    /**
     * {@inheritDoc}
     */
    protected function doConnect()
    {
        return new PDO($this->dsn, $this->user, $this->password, $this->options);
    }

    /**
     * {@inheritDoc}
     */
    protected function doDisconnect(PDOInterface $pdo)
    {
    }
}
