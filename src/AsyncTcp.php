<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/14
 * Time: 9:56
 */

namespace rabbit\socket;

use rabbit\App;
use rabbit\core\Exception;
use rabbit\pool\PoolProperties;
use rabbit\socket\pool\SocketConfig;
use Swoole\Client;

/**
 * Class AsyncTcp
 * @package rabbit\socket
 */
class AsyncTcp
{
    /** @var SocketConfig */
    private $config;
    /**
     * @var int
     */
    private $reconnectTimes = 3;
    /**
     * @var int
     */
    private $reconnectTicket = 1;
    /**
     * @var int
     */
    private $reconnectCount = 0;
    /** @var \Swoole\Client[] */
    private $connections = [];
    /** @var string */
    private $module = 'nsq';
    /**
     * @var array
     */
    private $on = [];

    /**
     * AsyncTcp constructor.
     * @param PoolProperties $config
     */
    public function __construct(PoolProperties $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $method
     * @param callable $callback
     * @return AsyncTcp
     */
    public function on(string $method, callable $callback): self
    {
        $this->on[$method] = $callback;
        return $this;
    }

    /**
     * @param string $key
     * @return AsyncTcp
     * @throws Exception
     */
    public function createConnection(string $key): \Swoole\Client
    {
        if (!isset($this->connections[$key]) || !$this->connections[$key]->isConnected()) {
            $connection = new Client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
            $address = $this->config->getUri();
            if (empty($address)) {
                $error = sprintf('Service does not configure uri name=%s', $key);
                throw new \InvalidArgumentException($error);
            }
            $timeout = $this->config->getTimeout();
            $setting = $this->config->getSetting();
            $setting && $connection->set($setting);

            $connection->on('connect', function (\Swoole\Client $cli) {
                $this->reconnectCount = 0;
                App::info('connected', $this->module);
                isset($this->on['connect']) && call_user_func($this->on['connect'], $cli);
            });

            $connection->on('receive', function (\Swoole\Client $cli, string $body) {
                isset($this->on['receive']) && call_user_func($this->on['receive'], $cli, $body);
            });

            $connection->on('error', function (\Swoole\Client $cli) use ($key) {
                App::error('Connect fail:' . socket_strerror($cli->errCode), $this->module);
                $this->reconnect($key);
                isset($this->on['error']) && call_user_func($this->on['error'], $cli);
            });

            $connection->on('close', function (\Swoole\Client $cli) use ($key) {
                App::info('Connect close.', $this->module);
                $this->reconnect($key);
                isset($this->on['close']) && call_user_func($this->on['close'], $cli);
            });

            list($host, $port) = explode(':', current($address));
            if (!$connection->connect($host, $port, $timeout)) {
                $error = sprintf('Service connect fail error=%s host=%s port=%s', socket_strerror($connection->errCode), $host, $port);
                throw new Exception($error);
            }
            if (isset($this->connections[$key])) {
                unset($this->connections[$key]);
            }
            $this->connections[$key] = $connection;
        }
        return $this->connections[$key];
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function disconnect(\Swoole\Client $connection): bool
    {
        App::info('Disconnect', $this->module);
        if ($connection->isConnected()) {
            return $connection->close();
        }
        return true;
    }

    /**
     * @throws \Exception
     */
    private function reconnect(string $key): void
    {
        $this->reconnectCount++;
        if ($this->reconnectTimes == 0 || $this->reconnectCount <= $this->reconnectTimes) {
            swoole_timer_after($this->reconnectTicket, [$this, 'createConnection'], $key);
        } else {
            $this->disconnect($this->connections[$key]);
        }
        unset($this->connections[$key]);
    }
}