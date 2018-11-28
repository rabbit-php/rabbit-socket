<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/21
 * Time: 0:58
 */

namespace rabbit\socket\socket;

use rabbit\socket\ClientInterface;

/**
 * Interface SocketClientInterface
 * @package rabbit\socket
 */
interface SocketClientInterface extends ClientInterface
{
    /**
     * @param float $timeout
     * @return int
     */
    public function sendByTimeout(string $data, float $timeout = -1): int;

    /**
     * @param int $length
     * @param float $timeout
     * @return string
     */
    public function receiveByLength(int $length = 65535, float $timeout = -1): string;

    /**
     * @param int $length
     * @param float $timeout
     * @return string
     */
    public function recvByLength(int $length = 65535, float $timeout = -1): string;

    /**
     * @param string $address
     * @param int $port
     * @return bool
     */
    public function bind(string $address, int $port = 0): bool;

    /**
     * @param int $backlog
     * @return bool
     */
    public function listen(int $backlog = 0): bool;

    /**
     * @param float $timeout
     * @return null|Coroutine\Socket
     */
    public function accept(float $timeout = -1): ?\Swoole\Coroutine\Socket;

    /**
     * @param string $address
     * @param int $port
     * @param string $data
     * @return int|null
     */
    public function sendto(string $address, int $port, string $data): ?int;

    /**
     * @param array $peer
     * @param float $timeout
     * @return null|string
     */
    public function recvfrom(array &$peer, float $timeout = -1): ?string;

    /**
     * @return array
     */
    public function getsockname(): array;

    /**
     * @return array
     */
    public function getpeername(): array;
}