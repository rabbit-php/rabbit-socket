<?php
declare(strict_types=1);

namespace Rabbit\Socket\pool;

use rabbit\pool\ConnectionPool;
use rabbit\socket\TcpClient;

/**
 * Class SocketPool
 * @package rabbit\socket\pool
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
