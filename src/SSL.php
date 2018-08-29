<?php
/**
 *============================
 * author:Farmer
 * time:2018/8/29 12:41
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace HuanL\Protocol;


class SSL {

    const TLSv3 = 0x0303;

    public static function client_hello() {

    }

    /**
     * 构造握手包
     * @param $type
     * @param $version
     * @param $content
     * @return string
     */
    public static function pac_ssl_handshake($type, $version, $content): string {

        return pack('cnn', $type, $version, strlen($content)) . $content;
    }

    /**
     * hello
     * @param $type
     * @param $version
     * @param $random
     * @param $content
     * @return string
     */
    public static function pack_ssl_hello($type, $version, $random, $content): string {
        $len = strlen($content) + 34;
        return pack('ca3n', $type,
                chr($len >> 16 & 0xff) . chr($len >> 8 & 0xff) . chr($len & 0xff)
                , $version) . $random . $content;
    }

    /**
     * 支持的秘钥
     * @param $array
     * @return string
     */
    public static function pack_ciphersuites($array): string {
        $ret = '';
        foreach ($array as $value) {
            $ret .= hex2bin($value);
        }
        $len = strlen($ret);
        return chr($len >> 8 & 0xff) . chr($len & 0xff) . $ret;
    }

    /**
     * 随机数
     * @return string
     */
    public static function pack_ssl_random(): string {
        $random = '';
        for ($i = 0; $i < 28; $i++) {
            $random .= chr(rand(0, 255));
        }
        return pack('Na28', time(), $random);
    }

}