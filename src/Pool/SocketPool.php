<?php
declare(strict_types=1);

namespace Rabbit\Socket\Pool;

use Rabbit\Pool\ConnectionPool;
use Rabbit\Socket\TcpClient;

/**
 * Class SocketPool
 * @package Rabbit\Socket\Pool
 */
class SocketPool extends ConnectionPool
{
    protected string $client = TcpClient::class;

    /**
     * @return mixed
     */
    public function create()
    {
        $client = $this->client;
        return new $client($this->getPoolConfig()->getName());
    }
}
