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
class TcpPool extends ConnectionPool
{
    private $client = TcpClient::class;

    /**
     * @return ConnectionInterface
     */
    public function createConnection(): ConnectionInterface
    {
        $client = $this->client;
        return new $client($this);
    }
}