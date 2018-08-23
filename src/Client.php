<?php
/**
 *============================
 * author:Farmer
 * time:2018/8/18 10:44
 * blog:blog.icodef.com
 * function:客户端
 *============================
 */


namespace HuanL\Protocol;


class Client {

    /**
     * socket连接资源
     * @var resource
     */
    protected $socket;

    const TCP = 1;
    const UDP = 2;

    /**
     * Client constructor.
     * @param string $ip
     * @param int $port
     * @param int $type
     */
    public function __construct(string $ip = '', int $port = 0, int $type = 1) {
        if (func_num_args() >= 2) {
            call_user_func_array([$this, 'connect'], func_get_args());
        }
    }

    /**
     * 连接服务器,返回true表示成功
     * @param string $ip
     * @param int $port
     * @param int $type
     * @return bool
     */
    public function connect(string $ip, int $port, int $type = 1): bool {
        //TODO: 暂时只写udp和tcp
        if ($type === 1) {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        } else {
            $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        }
        if ($socket) {
            if (socket_connect($socket, $ip, $port)) {
                $this->socket = $socket;
                return true;
            }
        }
        return false;
    }

    /**
     * 获取上一个错误代码
     * @return int
     */
    public function geterror(): int {
        return socket_last_error();
    }

    /**
     * 获取错误字符串
     * @param int $erron
     * @return string
     */
    public function getstrerror(int $erron): string {
        return socket_strerror($erron);
    }

    /**
     * 发送数据
     * @param string $data
     * @return int
     */
    public function send(string $data): int {
        echo $data;
        return socket_send($this->socket, $data, strlen($data), 0);
    }

    /**
     * 接收信息
     * @param string $buf
     * @param int $len
     * @return int
     */
    public function recv(&$buf, int $len): int {
        return socket_recv($this->socket, $buf, $len, 0);
    }

    /**
     * 数据写入缓存
     * @param string $buf
     * @return string
     */
    public function write(string $buf): string {
        return socket_write($this->socket, $buf, strlen($buf));
    }

    /**
     * 读取缓存中的数据
     * @param int $len
     * @return string
     */
    public function read(int $len): string {
        return socket_read($this->socket, $len);
    }


    /**
     * 设置超时
     * @param int $send
     * @param int $recv
     */
    public function timeout(int $send, int $recv) {
        $this->set_option(SOL_SOCKET, SO_SNDTIMEO, array('sec' => $send, 'usec' => 0));
        $this->set_option(SOL_SOCKET, SO_RCVTIMEO, array('sec' => $recv, 'usec' => 0));
    }

    /**
     * 设置选项
     * @param $level
     * @param $optname
     * @param $optval
     * @return bool
     */
    public function set_option($level, $optname, $optval) {
        return socket_set_option($this->socket, $level, $optname, $optval);
    }

    /**
     * 获取socket资源
     * @return resource
     */
    public function socket() {
        return $this->socket;
    }


    public function close() {
        socket_close($this->socket);
    }
}