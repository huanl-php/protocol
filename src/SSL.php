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
    public static function pack_ssl_handshake($type, $version, $content): string {
        return pack('cnn', $type, $version, strlen($content)) . $content;
    }

    /**
     * 解码握手包
     * @param $data
     * @return array
     */
    public static function unpack_ssl_handshake($data): array {
        $ret = unpack('ccode/nversion/nlength', $data);
        $ret['content'] = substr($data, 5, $ret['length']);
        return $ret;
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
     * 解码hello包
     * @param $data
     * @return array
     */
    public static function unpack_ssl_hello($data): array {
        $ret = unpack(
            'ctype/a3length/nversion/a32random/asession_length/ncipher_suite',
            $data
        );
        $ret['length'] = unpack('N', "\x0" . $ret['length'])[1];
        $ret['random'] = unpack('Ntimestamp/a28random', $ret['random']);
        $ret['random']['random'] = bin2hex($ret['random']['random']);
        $ret['content'] = bin2hex(substr($data, 41));
        return $ret;
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
    public static function pack_ssl_random(&$random = ''): string {
        for ($i = 0; $i < 28; $i++) {
            $random .= chr(rand(0, 255));
        }
        return pack('Na28', time(), $random);
    }

}