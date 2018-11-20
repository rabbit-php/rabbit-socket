<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/23
 * Time: 10:11
 */

namespace rabbit\socket\tcp;

/**
 * Interface TcpParserInterface
 * @package rabbit\socket
 */
interface TcpParserInterface
{
    /**
     * @param array $data
     * @return string
     */
    public function encode(array $data): string;

    /**
     * @param string $data
     * @return mixed
     */
    public function decode(string $data);
}