<?php
/**
 *============================
 * author:Farmer
 * time:2018/8/22 14:28
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace HuanL\Protocol\Test;

require_once '../src/Client.php';
require_once '../src/SSLClient.php';
require_once '../src/SMTP.php';
require_once '../src/SMTPException.php';

use HuanL\Protocol\Client;
use HuanL\Protocol\SMTP;
use HuanL\Protocol\SSLClient;
use PHPUnit\Framework\TestCase;

class SMTPTest extends TestCase {

    /**
     * @var SMTP
     */
    protected static $smtp = null;

    public function __construct(string $name = null, array $data = [], string $dataName = '') {
        parent::__construct($name, $data, $dataName);
        try {
            if (static::$smtp == null) {
                $client = new SSLClient('smtp.mxhichina.com', 465);
                $client->timeout(2, 2);
                static::$smtp = new SMTP($client, 'test@xloli.top', 'Qwer1234');
            }
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function testConnect() {
        static::$smtp->mailFrom('test@xloli.top', '测试')
            ->mailTo('code.farmer@qq.com');
        self::assertEquals(1, 1);
    }

    /**
     * @depends testConnect
     * @throws \HuanL\Protocol\SMTPException
     */
    public function testSendText() {
        static::$smtp->mailTitle('text测试')
            ->mailContent('test2354235354中文呢')
            ->sendMail();
        self::assertEquals(1, 1);
    }

    /**
     * @depends testConnect
     * @throws \HuanL\Protocol\SMTPException
     */
    public function testSendHtml() {
        static::$smtp->mailTitle('html测试1')
            ->mailContent('<h1>html测试</h1>')
            ->sendMail();
        self::assertEquals(1, 1);
    }


}