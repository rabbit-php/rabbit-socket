<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/22
 * Time: 18:13
 */

namespace rabbit\socket;

use rabbit\core\Exception;
use rabbit\core\ObjectFactory;
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
        $setting = $this->getTcpClientSetting();
        $setting && $client->set($setting);

        list($host, $port) = explode(':', $address);
        if (!$client->connect($host, $port, $timeout)) {
            $error = sprintf('Service connect fail errorCode=%s host=%s port=%s', $client->errCode, $host, $port);
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
     * @return mixed|string
     * @throws Exception
     */
    public function receive()
    {
        $result = $this->recv();
        $this->recv = true;
        return $result;
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function getTcpClientSetting(): array
    {
        return ObjectFactory::get('tcpclient.setting', []);
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
    public function recv(): string
    {
        $data = $this->connection->recv();
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