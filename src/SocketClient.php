<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/20
 * Time: 11:49
 */

namespace rabbit\socket;

use rabbit\core\Exception;
use rabbit\helper\CoroHelper;
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
        /** @var SocketConfig $config */
        $config = $this->pool->getPoolConfig();
        $client = new Socket($config->getDomin(), $config->getType(), $config->getProtocol());

        $address = $this->pool->getConnectionAddress();
        $timeout = $this->pool->getTimeout();

        list($host, $port) = explode(':', $address);

        $maxRetry = $this->pool->getPoolConfig()->getMaxReonnect();
        while (true) {
            if (!$client->connect($host, $port, $timeout)) {
                $this->reconnectCount++;
                if ($maxRetry > 0 && $this->reconnectCount >= $maxRetry) {
                    $this->reconnectCount = 0;
                    $error = sprintf('Service connect fail error=%s host=%s port=%s', socket_strerror($client->errCode), $host, $port);
                    throw new Exception($error);
                }
                CoroHelper::sleep($this->pool->getPoolConfig()->getMaxWaitTime() ?? 3);
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