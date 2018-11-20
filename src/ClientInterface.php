<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/20
 * Time: 12:04
 */

namespace rabbit\socket;

/**
 * Interface ClientInterface
 * @package rabbit\socket
 */
interface ClientInterface
{
    /**
     * @param string $data
     */
    public function send(string $data): bool;

    /**
     * @return string
     */
    public function recv(float $timeout = null): string;

    /**
     * @return bool
     */
    public function close(): bool;
}