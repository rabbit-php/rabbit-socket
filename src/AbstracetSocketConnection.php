<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/21
 * Time: 1:03
 */

namespace rabbit\socket;


use rabbit\pool\AbstractConnection;

abstract class AbstracetSocketConnection extends AbstractConnection implements SocketClientInterface
{
    public function close(): bool
    {
        return true;
    }
}