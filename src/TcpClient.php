<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/22
 * Time: 18:13
 */

namespace rabbit\socket;

use rabbit\core\Exception;
use rabbit\helper\CoroHelper;
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
        $client = new Client(SWOOLE_SOCK_TCP);

        $address = $this->pool->getConnectionAddress();
        $timeout = $this->pool->getTimeout();
        $setting = $this->pool->getPoolConfig()->getSetting();
        $setting && $client->set($setting);

        list($host, $port) = explode(':', $address);
        $maxRetry = $this->pool->getPoolConfig()->getMaxReonnect();
        while (true) {
            if (!$client->connect($host, $port, $timeout)) {
                $this->reconnectCount++;
                if ($maxRetry > 0 && $this->reconnectCount >= $maxRetry) {
                    $error = sprintf('Service connect fail error=%s host=%s port=%s', socket_strerror($client->errCode),
                        $host, $port);
                    throw new Exception($error);
                }
                CoroHelper::sleep($this->pool->getPoolConfig()->getMaxWaitTime() ?? 3);
            } else {
                break;
            }
        }
        $this->reconnectCount = 0;
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