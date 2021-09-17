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
    public function send(string $data, float $timeout = -1): int;

    public function recv(int $length = 65535, float $timeout = -1): string;
}
