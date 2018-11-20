<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/20
 * Time: 11:51
 */

namespace rabbit\socket\socket;

use rabbit\socket\AbstractConnection;


/**
 * Class AbstracetSocketConnection
 * @package rabbit\socket\socket
 */
abstract class AbstractSocketConnection extends AbstractConnection implements SocketClientInterface
{
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
}