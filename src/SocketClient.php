<?php

declare(strict_types=1);

namespace Rabbit\Socket;

use Rabbit\Pool\PoolManager;
use Rabbit\Base\Core\Exception;
use Rabbit\Socket\Socket\AbstractSocketConnection;

/**
 * Class SocketClient
 * @package Rabbit\Socket
 */
class SocketClient extends AbstractSocketConnection
{
    /**
     * @throws Exception
     */
    public function createConnection(): void
    {
        $pool = PoolManager::getPool($this->poolKey);
        $address = $pool->getConnectionAddress();
        $timeout = $pool->getTimeout();

        list($host, $port) = explode(':', $address);
        $maxRetry = $pool->getPoolConfig()->getMaxRetry();
        $reconnectCount = 0;
        while (true) {
            if (false === $client = @stream_socket_client(
                "tcp://$host:$port",
                $errorNumber,
                $errorDescription,
                $timeout ?? ini_get('default_socket_timeout')
            )) {
                $reconnectCount++;
                if ($maxRetry > 0 && $reconnectCount >= $maxRetry) {
                    $error = sprintf(
                        'Service connect fail error=%s host=%s port=%s',
                        $errorDescription,
                        $host,
                        $port
                    );
                    throw new Exception($error);
                }
                $sleep = $pool->getPoolConfig()->getMaxWait();
                sleep($sleep);
            } else {
                break;
            }
        }
        $timeout && stream_set_timeout($client, $t = (int)$timeout, (int)(($timeout - $t) * 1000000));
        stream_context_set_option($client, 'socket', 'tcp_nodelay', true);
        $this->connection = $client;
    }
}
