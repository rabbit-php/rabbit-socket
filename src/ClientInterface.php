<?php
declare(strict_types=1);

namespace Rabbit\Socket;

/**
 * Interface ClientInterface
 * @package Rabbit\Socket
 */
interface ClientInterface
{
    public function close(): bool;
}
