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
     * @return bool
     */
    public function close(): bool;
}