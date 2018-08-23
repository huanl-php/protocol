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
require_once '../src/SMTP.php';
require_once '../src/SMTPException.php';

use HuanL\Protocol\Client;
use HuanL\Protocol\SMTP;
use PHPUnit\Framework\TestCase;

class SMTPTest extends TestCase {

    /**
     * @var Client
     */
    protected $smtp;

    public function __construct(string $name = null, array $data = [], string $dataName = '') {
        parent::__construct($name, $data, $dataName);
        try {
            $this->smtp = new SMTP('smtp.xloli.top', 'test@xloli.top', 'Qwer1234');
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function testSend() {
        try {
            $this->smtp->mailFrom('test@xloli.top')
                ->mailTo('code.farmer@qq.com')
                ->mailTitle('test123标题')
                ->mailContent('test2354235354中文呢');
            $this->smtp->sendMail();
            self::assertEquals(1, 1);
        } catch (\Exception $exception) {
            self::assertEquals(1, 0);
        }
    }

}