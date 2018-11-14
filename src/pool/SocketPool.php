<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/14
 * Time: 11:19
 */

namespace rabbit\socket\pool;


use rabbit\pool\ConnectionInterface;
use rabbit\pool\ConnectionPool;
use rabbit\socket\TcpClient;

/**
 * Class SocketPool
 * @package rabbit\socket\pool
 */
class SocketPool extends ConnectionPool
{
    /**
     * @return ConnectionInterface
     */
    public function createConnection(): ConnectionInterface
    {
        return new TcpClient($this);
    }
}