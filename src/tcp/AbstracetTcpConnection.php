<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/21
 * Time: 1:03
 */

namespace rabbit\socket\tcp;

use rabbit\socket\AbstractConnection;

/**
 * Class AbstracetSocketConnection
 * @package rabbit\socket
 */
abstract class AbstracetTcpConnection extends AbstractConnection implements TcpClientInterface
{
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