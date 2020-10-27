<?php

declare(strict_types=1);

namespace Rabbit\Socket;

use Co\Client;
use Rabbit\Pool\PoolManager;
use Rabbit\Base\Core\Exception;
use Rabbit\Socket\Tcp\AbstractTcpConnection;

/**
 * Class TcpClient
 * @package Rabbit\Socket
 */
class TcpClient extends AbstractTcpConnection
{
    /**
     * @throws Exception
     */
    public function createConnection(): void
    {
        $pool = PoolManager::getPool($this->poolKey);
        $client = new Client(SWOOLE_SOCK_TCP);

        $address = $pool->getConnectionAddress();
        $timeout = $pool->getTimeout();
        $setting = $pool->getPoolConfig()->getConfig();
        $setting && $client->set($setting);

        list($host, $port) = explode(':', $address);
        $maxRetry = $pool->getPoolConfig()->getMaxRetry();
        $reconnectCount = 0;
        while (true) {
            if (!$client->connect($host, $port, $timeout)) {
                $reconnectCount++;
                if ($maxRetry > 0 && $reconnectCount >= $maxRetry) {
                    $error = sprintf(
                        'Service connect fail error=%s host=%s port=%s',
                        socket_strerror($client->errCode),
                        $host,
                        $port
                    );
                    throw new Exception($error);
                }
                $sleep = $pool->getPoolConfig()->getMaxWait();
                usleep(($sleep ? $sleep : 1) * 1000);
            } else {
                break;
            }
        }
        $this->connection = $client;
    }
}
