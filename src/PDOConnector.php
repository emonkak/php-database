<?php

declare(strict_types=1);

namespace Emonkak\Database;

/**
 * @extends AbstractConnector<PDO>
 */
class PDOConnector extends AbstractConnector
{
    private string $dsn;

    private ?string $user;

    private ?string $password;

    private ?array $options;

    public function __construct(string $dsn, ?string $user = null, ?string $password = null, ?array $options = null)
    {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $password;
        $this->options = $options;
    }

    /**
     * @return array<int,string>
     */
    public function __sleep(): array
    {
        return ['dsn', 'user', 'password', 'options'];
    }

    protected function doConnect(): PDOInterface
    {
        return new PDO($this->dsn, $this->user, $this->password, $this->options);
    }

    protected function doDisconnect(PDOInterface $pdo): void
    {
    }
}
