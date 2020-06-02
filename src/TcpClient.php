<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/22
 * Time: 18:13
 */

namespace rabbit\socket;

use Co\System;
use rabbit\core\Exception;
use rabbit\pool\PoolManager;
use rabbit\socket\tcp\AbstractTcpConnection;
use Swoole\Coroutine\Client;

/**
 * Class TcpClient
 * @package rabbit\socket
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
        $setting = $pool->getPoolConfig()->getSetting();
        $setting && $client->set($setting);

        list($host, $port) = explode(':', $address);
        $maxRetry = $pool->getPoolConfig()->getMaxReonnect();
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
        $this->connection = $client;
    }

    /**
     * @return bool
     */
    public function check(): bool
    {
        return $this->connection->connected;
    }
}
