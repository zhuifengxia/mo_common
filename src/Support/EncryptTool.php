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


    /****************OPENSSL加密START*********************/

    /**
     * 私钥加密内容
     * @param $rootpath 证书所在根目录
     * @param $data 待加密字符串
     * @return string 加密之后字符串
     */
    public static function data_openssl_private_encode($rootpath,$data)
    {
        $private_key = file_get_contents(self::get_cart_path($rootpath, 0));
        $pi_key = openssl_pkey_get_private($private_key);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        openssl_private_encrypt($data, $encrypted, $pi_key);//私钥加密
        $encrypted = urlencode(base64_encode($encrypted));
        return $encrypted;
    }

    /**
     * 公钥解密
     * @param $rootpath 证书所在根目录
     * @param $encrypted 待解密字符串
     * @return mixed 解密之后的字符串
     */
    public static function data_openssl_public_decode($rootpath,$encrypted)
    {
        $public_key = file_get_contents(self::get_cart_path($rootpath, 1));
        $pu_key = openssl_pkey_get_public($public_key);//这个函数可用来判断公钥是否是可用的
        openssl_public_decrypt(base64_decode(urldecode($encrypted)), $decrypted, $pu_key);//公钥解密
        if (empty($decrypted)) {
            //可能是请求网络自动urldecode了;
            openssl_public_decrypt(base64_decode($encrypted), $decrypted, $pu_key);//公钥解密
        }
        return $decrypted;
    }

    /**
     * 公钥加密
     * @param $rootpath 证书所在根目录
     * @param $data 待加密字符串
     * @return string 加密之后的字符串
     */
    public static function data_openssl_public_encode($rootpath,$data)
    {
        $public_key = file_get_contents(self::get_cart_path($rootpath, 1));
        $pu_key = openssl_pkey_get_public($public_key);//这个函数可用来判断公钥是否是可用的
        openssl_public_encrypt($data, $encrypted, $pu_key);//公钥加密
        $encrypted = urlencode(base64_encode($encrypted));
        return $encrypted;
    }

    /**
     * 私钥解密
     * @param $rootpath 证书所在根目录
     * @param $encrypted 待解密字符串
     * @return mixed 解密之后的字符串
     */
    public static function data_openssl_private_decode($rootpath,$encrypted)
    {
        $private_key = file_get_contents(self::get_cart_path($rootpath, 0));
        $pi_key = openssl_pkey_get_private($private_key);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        openssl_private_decrypt(base64_decode(urldecode($encrypted)), $decrypted, $pi_key);//私钥解密
        if (empty($decrypted)) {
            //可能是请求网络自动urldecode了;
            openssl_private_decrypt(base64_decode($encrypted), $decrypted, $pi_key);//私钥解密
        }
        return $decrypted;
    }

    /**
     * 证书路径
     * @param $rootpath 根目录
     * @param int $datatpe 0私钥;1公钥
     * @return string
     */
   static function get_cart_path($rootpath,$datatpe=0)
   {
       $filepath = $rootpath . "mo/mo-common/src/Support/cart/app_" . ($datatpe ? "public" : "private") . "_key.pem";
       return $filepath;
   }
    /****************OPENSSL加密END*********************/

}
