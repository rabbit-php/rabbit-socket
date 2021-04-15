<?php

declare(strict_types=1);

namespace Rabbit\Socket\Pool;

use Rabbit\Pool\PoolProperties;

/**
 * Class SocketConfig
 * @package Rabbit\Socket\Pool
 */
class SocketConfig extends PoolProperties
{
    protected string $protocol = 'tcp';

    public function getProtocol(): string
    {
        return $this->protocol;
    }
}
