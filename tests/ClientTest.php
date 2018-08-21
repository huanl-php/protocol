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
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase {

    public function testConnect() {
        require_once '../src/Client.php';
        $client = new Client();
        $client->connect('192.168.1.20', 6584);
        $client->send('test');
        $client->recv($buf, 1024);
        $this->assertEquals($buf, 'lswl');
    }
}
