<?php

declare(strict_types=1);

namespace Rabbit\Socket\Socket;

use Rabbit\Pool\AbstractConnection;

/**
 * Class AbstractSocketConnection
 * @package Rabbit\Socket\Socket
 */
abstract class AbstractSocketConnection extends AbstractConnection implements SocketClientInterface
{
    protected object $connection;

    public function send(string $data, float $timeout = -1): int
    {
        $ln = strlen($data);
        $this->setTimeout($timeout);
        while ($data && $ln > 0) {
            $result = fwrite($this->connection, $data);
            if ($result === false) {
                throw new \RuntimeException("Broken pipe or closed connection");
            }
            $data = substr($data, $result);
        }
        return $ln;
    }

    public function recv(int $length = 65535, float $timeout = -1): string
    {
        $this->setTimeout($timeout);
        while ($length) {
            $data = fread($this->connection, $length);
            if ($data === false) {
                throw new \RuntimeException("Error receiving data");
            }
            $length -= strlen($data);
        }
        return $data;
    }

    public function reconnect(): void
    {
        $this->createConnection();
    }

    public function close(): bool
    {
        return $this->connection && fclose($this->connection);
    }

    public function setTimeout(float $timeout): bool
    {
        if ($timeout > 0) {
            return stream_set_timeout($this->connection, (int)floor($timeout), (int)(fmod($timeout, 1) * 1000000));
        }
        return true;
    }
}
