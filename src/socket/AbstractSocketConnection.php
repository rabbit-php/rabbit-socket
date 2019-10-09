<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/20
 * Time: 11:51
 */

namespace rabbit\socket\socket;

use rabbit\core\Exception;
use rabbit\pool\AbstractConnection;
use rabbit\pool\PoolManager;


/**
 * Class AbstracetSocketConnection
 * @package rabbit\socket\socket
 */
abstract class AbstractSocketConnection extends AbstractConnection implements SocketClientInterface
{
    protected $connection;

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
        $this->recv = false;
        return $ln;
    }

    /**
     * @param float $timeout
     * @return mixed|void
     */
    public function receive(float $timeout = -1)
    {
        return $this->recv(65535, $timeout);
    }

    /**
     * @param int $length
     * @param float $timeout
     * @return string
     * @throws Exception
     */
    public function recv(int $length = 65535, float $timeout = -1): string
    {
        $retry = 0;
        while (false === $data = $this->connection->recvAll($length, $timeout)) {
            $retry++;
            if ($retry >= PoolManager::getPool($this->poolKey)->getPoolConfig()->getMaxReonnect()) {
                throw new \RuntimeException("{$this->connection->fd} recv failed!error=" . socket_strerror($this->connection->errCode));
            }
        }
        $this->recv = true;
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
     * @param float $timeout
     * @return null|Coroutine\Socket
     */
    public function accept(float $timeout = -1): ?\Swoole\Coroutine\Socket
    {
        $this->connection->accept($timeout);
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

    /**
     * @return bool
     */
    public function check(): bool
    {
        return $this->connection->errCode === 0;
    }

    /**
     * @throws Exception
     */
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