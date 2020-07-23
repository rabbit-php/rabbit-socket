<?php
declare(strict_types=1);

namespace Rabbit\Socket\Socket;

use Rabbit\Socket\ClientInterface;
use Swoole\Coroutine\Socket;

/**
 * Interface SocketClientInterface
 * @package Rabbit\Socket\Socket
 */
interface SocketClientInterface extends ClientInterface
{
    /**
     * @param string $data
     * @param float $timeout
     * @return int
     */
    public function send(string $data, float $timeout = -1): int;

    /**
     * @param int $length
     * @param float $timeout
     * @return string
     */
    public function recv(int $length = 65535, float $timeout = -1): string;

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
     * @param float|int $timeout
     * @return Socket|null
     */
    public function accept(float $timeout = -1): ?Socket;

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
