<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/21
 * Time: 0:58
 */

namespace rabbit\socket;

/**
 * Interface SocketClientInterface
 * @package rabbit\socket
 */
interface SocketClientInterface
{
    /**
     *
     */
    public function reconnect(): void;

    /**
     * @param string $data
     */
    public function send(string $data): bool;

    /**
     * @return string
     */
    public function recv(): string;

    /**
     *
     */
    public function close(): bool;
}