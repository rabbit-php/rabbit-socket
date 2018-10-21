<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/21
 * Time: 23:50
 */

namespace rabbit\socket;


use rabbit\core\ObjectFactory;
use rabbit\parser\ParserInterface;

/**
 * Class TcpEofParser
 * @package rabbit\socket
 */
class TcpEofParser implements ParserInterface
{
    /**
     * @var string
     */
    private $eofCheck;

    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * TcpEofParser constructor.
     * @param ParserInterface $parser
     */
    public function __construct(ParserInterface $parser)
    {
        $this->eofCheck = ObjectFactory::get('rpc.setting')['package_eof'];
        $this->parser = $parser;
    }

    /**
     * @param mixed $data
     * @return string
     */
    public function encode($data): string
    {
        $data = $this->parser->encode($data);
        return $data . $this->eofCheck;
    }

    /**
     * @param string $data
     * @return mixed
     */
    public function decode(string $data)
    {
        $data = rtrim($data, $this->eofCheck);
        return $this->parser->decode($data);
    }
}