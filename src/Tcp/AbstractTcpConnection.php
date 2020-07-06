<?php
declare(strict_types=1);

namespace Rabbit\Socket\Tcp;

use Co\Client;
use Rabbit\Pool\AbstractConnection;

/**
 * Class AbstractTcpConnection
 * @package Rabbit\Socket\Tcp
 */
abstract class AbstractTcpConnection extends AbstractConnection implements TcpClientInterface
{
    /** @var Client */
    protected Client $connection;

    public function reconnect(): void
    {
        $this->createConnection();
    }

    /**
     * @param float $timeout
     * @return string
     */
    public function recv(float $timeout = -1): string
    {
        if (false === $data = $this->connection->recv($timeout)) {
            return '';
        }
        return $data;
    }

    /**
     * @param string $data
     * @return int
     */
    public function send(string $data): int
    {
        if (false === $result = $this->connection->send($data)) {
            return 0;
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function close(): bool
    {
        return $this->connection->close();
    }

    /**
     * @param int $length
     * @return string
     */
    public function peek(int $length = 65535): ?string
    {
        $result = $this->connection->peek($length);
        return $result ?? null;
    }
}
