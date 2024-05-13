<?php

namespace Emonkak\Database;

/**
 * @extends AbstractConnector<MysqliAdapter>
 */
class MysqliConnector extends AbstractConnector
{
    private string $host;

    private string $username;

    private string $password;

    private string $dbname;

    private int $port;

    private string $socket;

    public function __construct(?string $host = null, ?string $username = null, ?string $password = null, string $dbname = '', ?int $port = null, ?string $socket = null)
    {
        $this->host = $host ?? ini_get('mysqli.default_host');
        $this->username = $username ?? ini_get('mysqli.default_user');
        $this->password = $password ?? ini_get('mysqli.pw');
        $this->dbname = $dbname;
        $this->port = $port ?? (int) ini_get('mysqli.default_port');
        $this->socket = $socket ?? ini_get('mysqli.default_socket');
    }

    /**
     * @return array<int,string>
     */
    public function __sleep(): array
    {
        return ['host', 'username', 'password', 'dbname', 'port', 'socket'];
    }

    protected function doConnect(): PDOInterface
    {
        $mysqli = new \mysqli($this->host, $this->username, $this->password, $this->dbname, $this->port, $this->socket);
        return new MysqliAdapter($mysqli);
    }

    protected function doDisconnect(PDOInterface $pdo): void
    {
        $mysqli = $pdo->getMysqli();
        $mysqli->close();
    }
}
