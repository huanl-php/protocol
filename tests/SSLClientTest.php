<?php
/**
 *============================
 * author:Farmer
 * time:2018/8/29 12:40
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace HuanL\Protocol\Test;


use HuanL\Protocol\Client;
use HuanL\Protocol\SSL;
use PHPUnit\Framework\TestCase;

require_once '../src/Client.php';
require_once '../src/SSL.php';


class SSLClientTest extends TestCase {

    /**
     * @var Client
     */
    public static $client = null;

    public function __construct(?string $name = null, array $data = [], string $dataName = '') {
        parent::__construct($name, $data, $dataName);
        if (static::$client == null) {
            static::$client = new Client('67.218.158.80', 443);
        }
    }

    public function testSSLHello() {
        $str = SSL::pac_ssl_handshake(22, SSL::TLSv3,
            SSL::pack_ssl_hello(1, SSL::TLSv3, SSL::pack_ssl_random(),
                hex2bin('00') . SSL::pack_ciphersuites(['009c', 'c02f', '003c']) .
                hex2bin('0100005800000014001200000f626c6f672e69636f6465662e636f6d000500050100000000000a00080006001d00170018000b00020100000d001400120401050102010403050302030202060106030023000000170000ff01000100')
            )
        );
        static::$client->send($str);
        static::$client->recv($buf, 2048);
    }


}