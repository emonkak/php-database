<?php

namespace Emonkak\Database;

class MysqliConnector extends AbstractConnector
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $dbname;

    /**
     * @var integer
     */
    private $port;

    /**
     * @var string
     */
    private $socket;

    /**
     * @param string|null  $host
     * @param string|null  $username
     * @param string|null  $password
     * @param string|null  $dbname
     * @param integer|null $port
     * @param string|null  $socket
     */
    public function __construct($host = null, $username = null, $password = null, $dbname = '', $port = null, $socket = null)
    {
        $this->host = $host ?: ini_get('mysqli.default_host');
        $this->username = $username ?: ini_get('mysqli.default_user');
        $this->password = $password ?: ini_get('mysqli.pw');
        $this->dbname = $dbname;
        $this->port = $port ?: (int) ini_get('mysqli.default_port');
        $this->socket = $socket ?: ini_get('mysqli.default_socket');
    }

    public function __sleep()
    {
        return ['host', 'username', 'password', 'dbname', 'port', 'socket'];
    }

    /**
     * {@inheritDoc}
     */
    protected function doConnect()
    {
        $mysqli = new \mysqli($this->host, $this->username, $this->password, $this->dbname, $this->port, $this->socket);
        return new MysqliAdapter($mysqli);
    }

    /**
     * {@inheritDoc}
     *
     * @suppress PhanUndeclaredMethod
     */
    protected function doDisconnect(PDOInterface $pdo)
    {
        $mysqli = $pdo->getMysqli();
        $mysqli->close();
    }
}
