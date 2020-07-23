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
    /** @var int */
    protected int $domain = AF_INET;
    /** @var int */
    protected int $type = SOCK_STREAM;
    /** @var int */
    protected int $protocol = 0;
    /** @var string */
    protected ?string $bind = null;

    /**
     * @return int
     */
    public function getDomin(): int
    {
        return $this->domain;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getProtocol(): int
    {
        return $this->protocol;
    }

    /**
     * @return string
     */
    public function getBind(): ?string
    {
        return $this->bind;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
