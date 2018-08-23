<?php
/**
 *============================
 * author:Farmer
 * time:2018/8/22 14:15
 * blog:blog.icodef.com
 * function:smtp发信协议
 *============================
 */


namespace HuanL\Protocol;

class SMTP extends Client {

    /**
     * 是否输入内容
     * @var bool
     */
    protected $is_input_data = false;

    /**
     * 发件者
     * @var string
     */
    protected $from = '';

    /**
     * 第一次发送内容
     * @var bool
     */
    protected $frist_content = true;

    /**
     * 收件者
     * @var array
     */
    protected $to = [];

    /**
     * SMTP constructor.
     * @param string $server
     * @param string $user
     * @param string $pwd
     * @param int $port
     * @throws SMTPException
     */
    public function __construct(string $server, string $user, string $pwd, int $port = 25) {
        parent::__construct($server, $port, 1);
        //两秒超时
        $this->timeout(2, 2);
        $read = $this->read(1024);
        if (($code = substr($read, 0, 3)) != '220') {
            //不是smtp服务器,没给我们打招呼,抛出异常
            throw new SMTPException('smtp server returns a reject code', $code);
        }
        $this->login($user, $pwd);
    }

    protected function login($user, $pwd) {
        $input = ['username:' => $user, 'password:' => $pwd];
        //登录用户
        $this->sendCommand('helo ' . $user);
        $read = $this->readCommand('250', 'login error');

        $this->sendCommand('auth login');
        $read = $this->readCommand('334', ' login error');

        $key = strtolower(base64_decode(substr($read, strpos($read, ' ') + 1)));
        $this->sendCommand(base64_encode($input[$key]));
        $read = $this->readCommand('334', $input[$key] . ' login error');

        $key = strtolower(base64_decode(substr($read, strpos($read, ' ') + 1)));
        $this->sendCommand(base64_encode($input[$key]));
        $this->readCommand('235', $input[$key] . ' login error');
    }

    /**
     * 读取命令行
     * @param $code
     * @param string $errmsg
     * @return string
     * @throws SMTPException
     */
    public function readCommand($code, $errmsg = '') {
        $read = $this->read(1024);
        if (($errcode = substr($read, 0, 3)) != $code) {
            throw new SMTPException($errmsg, $errcode);
        }
        return trim($read);
    }

    /**
     * 发送一条命令
     * @param string $data
     * @return int
     */
    public function sendCommand(string $data): int {
        return parent::send($data . "\r\n"); // TODO: Change the autogenerated stub
    }

    /**
     * 发件者格式例如:codfrm<love@xloli.top>
     * @param string $from
     * @return SMTP
     */
    public function mailFrom(string $from): SMTP {
        $this->from = $from;
        $this->sendCommand('mail from:<' . $from . '>');
        $this->readCommand('250', 'error mail from');
        return $this;
    }

    /**
     * 接收者,对方的邮箱
     * @param string $to
     * @return SMTP
     */
    public function mailTo(string $to): SMTP {
        $this->to[] = $to;
        $this->sendCommand('rcpt to:<' . $to . '>');
        $this->readCommand('250', 'error mail to');
        return $this;
    }

    /**
     * 邮箱头
     * @param array $headers
     * @return $this
     */
    public function mailHeaders(array $headers = []) {
        if (!$this->is_input_data) {
            $this->sendCommand('data');
            $this->readCommand('354', 'data input error');
            $this->is_input_data = true;
            if (!isset($headers['from'])) {
                $headers['from'] = '<' . $this->from . '>';
            }
            if (!isset($headers['to'])) {
                $headers['to'] = '';
                foreach ($this->to as $value) {
                    $headers['to'] .= '<' . $value . '>,';
                }
                $headers['to'] = substr($headers['to'], 0, strlen($headers['to']) - 1);
            }
        }
        foreach ($headers as $key => $value) {
            $this->sendCommand($key . ':' . $value);
        }
        return $this;
    }

    /**
     * 邮件标题
     * @param $title
     * @return $this
     */
    public function mailTitle($title) {
        $this->mailHeaders(['subject' => $title]);
        return $this;
    }

    /**
     * 邮件内容
     * @param $content
     * @return $this
     */
    public function mailContent($content) {
        if (!$this->is_input_data) {
            $this->mailHeaders();
        }
        if ($this->frist_content) {
            $content = "\r\n" . $content;
            $this->frist_content = false;
        }
        $this->sendCommand($content);
        return $this;
    }

    /**
     * 发送邮件
     */
    public function sendMail() {
        if (!$this->is_input_data) {
            throw new SMTPException('content is null', 0);
        }
        $this->sendCommand("\r\n.");
        $this->readCommand('250', 'end error');
        $this->sendCommand("quit");
        $this->readCommand('221', 'quit error');
        $this->close();
        return $this;
    }
}

