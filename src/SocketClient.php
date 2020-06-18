<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/20
 * Time: 11:49
 */

namespace rabbit\socket;

use Co\System;
use rabbit\core\Exception;
use rabbit\pool\PoolManager;
use rabbit\socket\pool\SocketConfig;
use rabbit\socket\socket\AbstractSocketConnection;
use Swoole\Coroutine\Socket;

/**
 * Class SocketClient
 * @package rabbit\socket
 */
class SocketClient extends AbstractSocketConnection
{
    public function createConnection(): void
    {
        $pool = PoolManager::getPool($this->poolKey);
        /** @var SocketConfig $config */
        $config = $pool->getPoolConfig();
        $client = new Socket($config->getDomin(), $config->getType(), $config->getProtocol());

        $address = $pool->getConnectionAddress();
        $timeout = $pool->getTimeout();

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
                System::sleep($sleep ? $sleep : 1);
            } else {
                break;
            }
        }
        $bind = $config->getBind();
        if ($bind) {
            list($host, $port) = explode(':', $bind);
            $client->bind($host, $port);
        }

        $this->connection = $client;
    }
}
