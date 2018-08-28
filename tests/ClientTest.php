<?php
/**
 *============================
 * author:Farmer
 * time:2018/8/21 12:14
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace HuanL\Protocol\Test;


use HuanL\Protocol\Client;
use HuanL\Protocol\SSLClient;
use PHPUnit\Framework\TestCase;

require_once '../src/Client.php';
require_once '../src/SSLClient.php';

class ClientTest extends TestCase {

    public function testConnect() {
        $client = new Client();
        $client->connect('192.168.1.20', 6584);
        $client->send('test');
        $client->recv($buf, 1024);
        $this->assertEquals($buf, 'lswl');
    }

    public function testSSLConnect() {
        $client = new SSLClient();
    }
}
