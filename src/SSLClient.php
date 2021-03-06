<?php
/**
 *============================
 * author:Farmer
 * time:2018/8/28 9:55
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace HuanL\Protocol;


class SSLClient extends Client {

    protected $errno = 0;

    protected $errstr = '';

    /**
     * SSL连接,第三个参数type无效,只支持tcp
     * @param string $ip
     * @param int $port
     * @param int $type
     * @return bool
     */
    public function connect(string $ip, int $port, int $type = 1): bool {
        if ($socket = stream_socket_client("tcp://$ip:$port",
            $this->errno, $this->errstr, 5)
        ) {
            //设置ssl
            stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_SSLv23_CLIENT);
            $this->socket = $socket;
            return true;
        }
        return false;
    }

    /**
     * @return int
     */
    public function geterror(): int {
        return $this->errno;
    }

    /**
     * @param int $erron
     * @return string
     */
    public function getstrerror(int $erron): string {
        return $this->errstr;
    }

    /**
     * SSL请用write和read
     * @param string $buf
     * @return int
     */
    public function write(string $buf): int {
        return fwrite($this->socket, $buf);
    }

    /**
     * SSL请用write和read
     * @param int $len
     * @return string
     */
    public function read(int $len): string {
        return fread($this->socket, $len);
    }

    public function send(string $data): int {
        return self::write($data);
    }

    public function recv(&$buf, int $len): int {
        $buf = self::read($len);
        return strlen($buf);
    }

    public function set_option($wrapper, $optname, $value) {
        return stream_context_set_option($this->socket, $wrapper, $optname, $value); // TODO: Change the autogenerated stub
    }

    public function timeout(int $seconds, int $microseconds = 0) {
        stream_set_timeout($this->socket, $seconds, $microseconds);
    }

}