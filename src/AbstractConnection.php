<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/20
 * Time: 12:20
 */

namespace rabbit\socket;

use rabbit\core\Exception;

/**
 * Class AbstractConnection
 * @package rabbit\socket
 */
abstract class AbstractConnection extends \rabbit\pool\AbstractConnection implements ClientInterface
{
    protected $connection;

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
    public function receive(float $timeout = -1)
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
    public function recv(float $timeout = -1): string
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