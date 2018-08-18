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
     * 获取上一个错误字符串
     * @param $erron
     * @return string
     */
    public function getstrerror($erron): string {
        return socket_strerror($erron);
    }


}