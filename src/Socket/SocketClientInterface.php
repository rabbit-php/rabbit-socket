<?php

declare(strict_types=1);

namespace Rabbit\Socket\Socket;

use Rabbit\Socket\ClientInterface;

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
}
