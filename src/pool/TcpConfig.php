<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/14
 * Time: 10:24
 */

namespace rabbit\socket\pool;


use rabbit\pool\PoolProperties;

/**
 * Class SocketPool
 * @package rabbit\socket\pool
 */
class TcpConfig extends PoolProperties
{
    /**
     * @var array
     */
    protected $setting = [];

    /**
     * @return array
     */
    public function getSetting(): array
    {
        return $this->setting;
    }

    /**
     * @param array $setting
     */
    public function setSetting(array $setting): void
    {
        $this->setting = $setting;
    }
}