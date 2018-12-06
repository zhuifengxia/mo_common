<?php

namespace MoCommon\Support;

/**
 * 加密类.
 *
 * @farwish
 */
class EncryptTool
{
    /****************DES加密START(PHP7以下使用)*********************/
    /**
     * 内容数据加密
     * @param $str  待加密字符串
     * @param $key 秘钥
     * @return string 加密后字符串
     */
    public static function data_des_encode($str, $key)
    {
        $size = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC);
        $str = self::pkcs5Pad($str, $size);
        $aaa = mcrypt_encrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_CBC, $key);
        $ret = base64_encode($aaa);
        return $ret;
    }

    function pkcs5Pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    /**
     * 内容数据解密
     * @param string $str 要处理的字符串
     * @param string $key 解密Key，为8个字节长度
     * @return string  解密后数据
     */
    public static function data_des_decode($str, $key)
    {
        $strBin = base64_decode($str);
        $str = mcrypt_decrypt(MCRYPT_DES, $key, $strBin, MCRYPT_MODE_CBC, $key);
        $str = self::pkcs5Unpad($str);
        return $str;
    }
    function pkcs5Unpad($text)
    {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text))
            return false;

        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
            return false;

        return substr($text, 0, -1 * $pad);
    }

    /****************DES加密END*********************/
}
