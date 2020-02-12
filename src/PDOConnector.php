<?php

namespace Emonkak\Database;

/**
 * @extends AbstractConnector<PDO>
 */
class PDOConnector extends AbstractConnector
{
    /**
     * @var string
     */
    private $dsn;

    /**
     * @var ?string
     */
    private $user;

    /**
     * @var ?string
     */
    private $password;

    /**
     * @var ?array
     */
    private $options;

    public function __construct(string $dsn, ?string $user = null, ?string $password = null, array $options = null)
    {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $password;
        $this->options = $options;
    }

    public function __sleep()
    {
        return ['dsn', 'user', 'password', 'options'];
    }

    /**
     * {@inheritDoc}
     */
    protected function doConnect(): PDOInterface
    {
        return new PDO($this->dsn, $this->user, $this->password, $this->options);
    }

    /**
     * {@inheritDoc}
     */
    protected function doDisconnect(PDOInterface $pdo): void
    {
    }
}
