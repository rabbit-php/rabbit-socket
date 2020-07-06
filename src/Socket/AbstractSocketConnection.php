<?php
declare(strict_types=1);

namespace Rabbit\Socket\Socket;

use Rabbit\Pool\AbstractConnection;
use Rabbit\Pool\PoolManager;
use Swoole\Coroutine\Socket;

/**
 * Class AbstractSocketConnection
 * @package Rabbit\Socket\Socket
 */
abstract class AbstractSocketConnection extends AbstractConnection implements SocketClientInterface
{
    /** @var Socket  */
    protected Socket $connection;

    /**
     * @param string $data
     * @param float $timeout
     * @return int
     */
    public function send(string $data, float $timeout = -1): int
    {
        $ln = strlen($data);
        while ($data && $ln > 0) {
            $result = $this->connection->sendAll($data, $timeout);
            if ($result === false) {
                throw new \RuntimeException("{$this->connection->fd} send failed!error=" . socket_strerror($this->connection->errCode));
            }
            $data = substr($data, $result);
        }
        return $ln;
    }

    /**
     * @param int $length
     * @param float $timeout
     * @return string
     */
    public function recv(int $length = 65535, float $timeout = -1): string
    {
        $retry = 0;
        while (false === $data = $this->connection->recv($length, $timeout)) {
            $retry++;
            if ($retry >= PoolManager::getPool($this->poolKey)->getPoolConfig()->getMaxRetry()) {
                throw new \RuntimeException("{$this->connection->fd} recv failed!error=" . socket_strerror($this->connection->errCode));
            }
        }
        return $data;
    }

    /**
     * @param string $address
     * @param int $port
     * @return bool
     */
    public function bind(string $address, int $port = 0): bool
    {
        return $this->connection->bind($address, $port);
    }

    /**
     * @param int $backlog
     * @return bool
     */
    public function listen(int $backlog = 0): bool
    {
        return $this->connection->listen($backlog);
    }

    /**
     * @param float|int $timeout
     * @return Socket|null
     */
    public function accept(float $timeout = -1): ?Socket
    {
        if (false === $socket = $this->connection->accept($timeout)) {
            return null;
        }
        return $socket;
    }

    /**
     * @param string $address
     * @param int $port
     * @param string $data
     * @return int|null
     */
    public function sendto(string $address, int $port, string $data): ?int
    {
        $client = $this->connection->sendto($address, $port, $data);
        if ($client === false) {
            return null;
        }
        return $client;
    }

    /**
     * @param array $peer
     * @param float $timeout
     * @return null|string
     */
    public function recvfrom(array &$peer, float $timeout = -1): ?string
    {
        $data = $this->connection->recvfrom($peer, $timeout);
        if ($data === false) {
            return null;
        }
        return $data;
    }

    /**
     * @return array
     */
    public function getsockname(): array
    {
        return $this->connection->getsockname();
    }

    /**
     * @return array
     */
    public function getpeername(): array
    {
        return $this->connection->getpeername();
    }

    public function reconnect(): void
    {
        $this->createConnection();
    }

    /**
     * @return bool
     */
    public function close(): bool
    {
        return $this->connection->close();
    }
}
