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
    protected $client = TcpClient::class;

    /**
     * @return mixed
     */
    public function create()
    {
        $client = $this->client;
        return new $client($this->getPoolConfig()->getName());
    }
}
