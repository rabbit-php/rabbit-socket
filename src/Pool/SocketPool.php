<?php

declare(strict_types=1);

namespace Rabbit\Socket\Pool;

use Rabbit\Pool\ConnectionPool;
use Rabbit\Socket\SocketClient;

/**
 * Class SocketPool
 * @package Rabbit\Socket\Pool
 */
class SocketPool extends ConnectionPool
{
    protected string $client = SocketClient::class;

    /**
     * @return mixed
     */
    public function create()
    {
        $client = $this->client;
        return new $client($this->getPoolConfig()->getName());
    }
}
