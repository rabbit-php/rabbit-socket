<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/21
 * Time: 0:58
 */

namespace rabbit\socket\tcp;

use rabbit\socket\ClientInterface;

/**
 * Interface SocketClientInterface
 * @package rabbit\socket
 */
interface TcpClientInterface extends ClientInterface
{
    /**
     * @param string $data
     */
    public function send(string $data): int;

    /**
     * @return string
     */
    public function recv(float $timeout = -1): string;

    /**
     * @param int $length
     * @return null|string
     */
    public function peek(int $length = 65535): ?string;
}
