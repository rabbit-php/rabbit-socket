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
}