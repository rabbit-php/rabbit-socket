<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/22
 * Time: 18:13
 */

namespace rabbit\socket;

use rabbit\core\Exception;
use Swoole\Coroutine\Client;

/**
 * Class TcpClient
 * @package rabbit\socket
 */
class TcpClient extends AbstracetSocketConnection
{
    /**
     * @throws Exception
     */
    public function createConnection(): void
    {
        $client = new Client(SWOOLE_SOCK_TCP | SWOOLE_KEEP);

        $address = $this->pool->getConnectionAddress();
        $timeout = $this->pool->getTimeout();
        $setting = $this->pool->getPoolConfig()->getSetting();
        $setting && $client->set($setting);

        list($host, $port) = explode(':', $address);
        if (!$client->connect($host, $port, $timeout)) {
            $error = sprintf('Service connect fail error=%s host=%s port=%s', socket_strerror($client->errCode), $host, $port);
            throw new Exception($error);
        }
        $this->connection = $client;
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
    public function check(): bool
    {
        return $this->connection->connected;
    }

    /**
     * @param float|null $timeout
     * @return mixed|string
     * @throws Exception
     */
    public function receive(float $timeout = null)
    {
        $result = $this->recv($timeout);
        $this->recv = true;
        return $result;
    }

    /**
     * @param string $data
     * @return bool
     */
    public function send(string $data): bool
    {
        $result = $this->connection->send($data);
        $this->recv = false;
        return $result;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function recv(float $timeout = null): string
    {
        if ($timeout !== null) {
            $data = $this->connection->recv($timeout);
        } else {
            $data = $this->connection->recv();
        }

        if (empty($data)) {
            throw new Exception('ServiceConnection::recv error, errno=' . socket_strerror($this->connection->errCode));
        }
        return $data;
    }

    /**
     * @return bool
     */
    public function close(): bool
    {
        return $this->connection->close();
    }

}