<?php
declare(strict_types=1);

namespace Rabbit\Socket\Tcp;

use Rabbit\Socket\ClientInterface;

/**
 * Interface TcpClientInterface
 * @package Rabbit\Socket\Tcp
 */
interface TcpClientInterface extends ClientInterface
{
    /**
     * @param string $data
     * @return int
     */
    public function send(string $data): int;

    /**
     * @param float $timeout
     * @return string
     */
    public function recv(float $timeout = -1): string;

    /**
     * @param int $length
     * @return null|string
     */
    public function peek(int $length = 65535): ?string;
}
