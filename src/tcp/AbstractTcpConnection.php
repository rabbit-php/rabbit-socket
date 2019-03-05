<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/21
 * Time: 1:03
 */

namespace rabbit\socket\tcp;

use rabbit\core\Exception;
use rabbit\pool\AbstractConnection;

/**
 * Class AbstracetSocketConnection
 * @package rabbit\socket
 */
abstract class AbstractTcpConnection extends AbstractConnection implements TcpClientInterface
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
     * @return string
     * @throws Exception
     */
    public function recv(float $timeout = -1): string
    {
        $data = $this->connection->recv($timeout);
        return $data;
    }

    /**
     * @param string $data
     * @return bool
     */
    public function send(string $data): int
    {
        $result = $this->connection->send($data);
        $this->recv = false;
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