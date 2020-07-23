<?php
declare(strict_types=1);

namespace Rabbit\Socket\Tcp;

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
