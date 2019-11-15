<?php
/**
 * Description: 加密类.
 * Author: momo
 * Date: 2019-04-24 11:47
 * Copyright: momo
 */

namespace MoCommon\Support;


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



    /*********************encrypt加密解密STSRT***********/

    /**
     * @param $string 待加密/解密字符串
     * @param $operation  E 加密  D 解密
     * @param $key  秘钥
     */
    public static function qian_encrypt($string,$operation,$key)
    {
        $key = md5($key);
        $key_length = strlen($key);
        $string = $operation == 'D' ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
        $string_length = strlen($string);
        $rndkey = $box = array();
        $result = '';
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'D') {
            if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
                return substr($result, 8);
            } else {
                return '';
            }
        } else {
            return str_replace('=', '', base64_encode($result));
        }
    }
    /*********************encrypt加密解密STSRT***********/


    /*********************SHA1withRSA加密解密STSRT***********/

    /**
     * pfx私钥加签
     * @param $string 待加密字符串
     * @param $cartfile 证书文件
     * @param string $password 证书密码
     * @return string 加密之后的字符串
     */
    public static function rsa_private_encode($string,$cartfile,$password="123456")
    {
        $public_key = file_get_contents($cartfile);
        $certs=[];
        openssl_pkcs12_read($public_key,$certs,$password);
        if(!$certs) {
           die('cart file error!');
        }
        $signature = '';
        openssl_sign($string, $signature, $certs['pkey']);
        $signature = base64_encode($signature);// base64传输
        return $signature;
    }

    /**
     * 私钥验签
     * @param $string 待签字符串
     * @param $signature 签名
     * @param $cartfile 证书文件
     * @param string $password 证书密码
     * @return int 验签结果1成功；0失败；
     */
    public static function rsa_private_decode($string,$signature,$cartfile,$password="123456")
    {
        $private_key = file_get_contents($cartfile);
        $certs = array();
        openssl_pkcs12_read($private_key, $certs, $password);
        if (!$certs) {
            die('cart file error!');
        }
        $result = openssl_verify($string, base64_decode($signature), $certs['cert']); // openssl_verify验签成功返回1，失败0，错误返回-1
        return $result;
    }

    /**
     * 公钥验签
     * @param $string 待签字符串
     * @param $signature 签名字符串
     * @param $cartfile 证书文件
     * @return int 验签结果1成功；0失败
     */
    public static function rsa_public_decode($string,$signature,$cartfile)
    {
        $cer_key = file_get_contents($cartfile); //获取证书内容
        $unsignMsg = base64_decode($signature);//base64解码加密信息
        $cer = openssl_x509_read($cer_key); //读取公钥
        $res = openssl_verify($string, $unsignMsg, $cer); //验证
        return $res; //输出验证结果，1：验证成功，0：验证失败
    }

    /*********************SHA1withRSA加密解密END***********/


}
