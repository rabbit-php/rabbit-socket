<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/21
 * Time: 1:03
 */

namespace rabbit\socket;


use rabbit\pool\AbstractConnection;

/**
 * Class AbstracetSocketConnection
 * @package rabbit\socket
 */
abstract class AbstracetSocketConnection extends AbstractConnection implements SocketClientInterface
{
    /**
     * @var \Swoole\Coroutine\Client
     */
    protected $connection;

    /**
     * @return bool
     */
    public function close(): bool
    {
        return true;
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

    /**
     * @return \Swoole\Coroutine\Client
     */
    public function getConnection()
    {
        return $this->connection;
    }
}