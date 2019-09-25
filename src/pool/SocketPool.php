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
     * @return int
     */
    public function getCurrentCount(): int
    {
        return $this->currentCount;
    }

    /**
     * @param int $count
     */
    public function setCurrentCount(int $count = 0): void
    {
        $this->currentCount = $count;
    }

    /**
     * @return ConnectionInterface
     */
    public function createConnection(): ConnectionInterface
    {
        $client = $this->client;
        return new $client($this);
    }
}