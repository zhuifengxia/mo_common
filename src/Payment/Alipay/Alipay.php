<?php
/**
 * Description: 支付宝支付类.
 * Author: momo
 * Date: 2019-04-24 15:50
 * Copyright: momo
 */
namespace MoCommon\Payment\Alipay;


use MoCommon\Support\HttpBase;

class Alipay
{
    private $pid = '';
    //key
    private $key = '';
    //APPID
    private $APPID = '';
    //应用私钥
    private $privateKey = '';
    //支付宝公钥
    private $publicKey = '';
    //验证网关
    private $checkHost = 'https://mapi.alipay.com/gateway.do';


    public function __construct($privateKey, $publicKey, $pid = '', $APPID='',$key = '')
    {
        $this->APPID = $APPID;
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->pid = $pid;
        $this->key = $key;
    }

    /**
     * 数组排序拼接
     * @param $data array
     * @param $type bool  RSA2验签貌似要剔除sign_type
     * return string
     */
    public function makeStr($data, $type = true)
    {
        if (empty($data)) {
            return false;
        }
        if (isset($data['sign'])) {
            unset($data['sign']);
        }
        if ($type) {
            if (isset($data['sign_type']) && $data['sign_type'] != 'RSA2') {
                unset($data['sign_type']);
            }
        } else {
            unset($data['sign_type']);
        }

        //排序
        ksort($data);
        return $this->makeUrl($data, false);
    }

    /**
     * 将数组转换为url格式的字符串
     * @param $arr
     * @param bool $encode
     * @return string
     */
    public function makeUrl($arr, $encode = true)
    {
        if ($encode) {
            return http_build_query($arr);//json编码
        } else {
            return urldecode(http_build_query($arr));//汉字
        }
    }

    /**
     * 记录测试日志
     * @param $filename
     * @param $data
     */
    public function alipayLog($filename, $data)
    {
        file_put_contents('./' . $filename, $data . "\r\n", FILE_APPEND);
    }


    /**
     * 使用私钥生成签名
     * @params string
     * return string
     */
    public function makeSign($data, $sign_type = true)
    {
        $str = $this->makeStr($data);
        if ($sign_type) {
            return $this->rsaSign($str, $this->privateKey, 'RSA2');
        } else {
            return MD5($str . $this->Key);
        }
    }


    /**
     * 验证MD5签名
     * @param $arr
     * @return bool
     */
    public function checkSign($arr)
    {
        $sign = $this->makeSign($arr);
        if ($sign == $arr['sign']) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 验证是否来之支付宝的通知  仅限正常商户, 沙箱非法合伙人
     * @param $arr
     * @return bool
     */
    public function isAlipay($arr)
    {
        $str = file_get_contents($this->checkHost . '?service=notify_verify&partner=' . $this->pid . '&notify_id=' . $arr['notify_id']);
        if ($str == 'true') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 验证交易状态
     * @param $arr
     * @return bool
     */
    public function checkOrderStatus($arr)
    {
        if ($arr['trade_status'] == 'TRADE_SUCCESS' || $arr['trade_status'] == 'TRADE_FINISHED') {
            return true;
        } else {
            return false;
        }
    }


    /**
     * RSA或RSA2签名
     * @param $data 待签名数据
     * @param $private_key 私钥字符串
     * return 签名结果
     */
    public function rsaSign($data, $private_key, $sign_type = 'RSA')
    {

        $last = $sign_type == 'RSA' ? 'OPENSSL_ALGO_SHA1' : 'SHA256'; //如果openssl_sign()报错了, 原因和PHP版本有关, 将OPENSSL_ALGO_SHA256改为SHA256
        $search = array(
            "-----BEGIN RSA PRIVATE KEY-----",
            "-----END RSA PRIVATE KEY-----",
            "\n",
            "\r",
            "\r\n"
        );

        $private_key = str_replace($search, "", $private_key);
        $private_key = $search[0] . PHP_EOL . wordwrap($private_key, 64, "\n", true) . PHP_EOL . $search[1];
        $res = openssl_get_privatekey($private_key);

        if ($res) {
            openssl_sign($data, $sign, $res, $last);
            openssl_free_key($res);
        } else {
            exit("私钥格式有误");
        }
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * RSA或RSA2验签
     * @param $data 待签名数据
     * @param $public_key 公钥字符串
     * @param $sign 要校对的的签名结果
     * return 验证结果
     */
    public function rsaCheck($data, $public_key, $sign, $sign_type = 'RSA')
    {

        $last = $sign_type == 'RSA' ? 'OPENSSL_ALGO_SHA1' : 'SHA256';//如果openssl_sign()报错了, 原因和PHP版本有关, 将OPENSSL_ALGO_SHA256改为SHA256
        $search = array(
            "-----BEGIN PUBLIC KEY-----",
            "-----END PUBLIC KEY-----",
            "\n",
            "\r",
            "\r\n"
        );
        $public_key = str_replace($search, "", $public_key);
        $public_key = $search[0] . PHP_EOL . wordwrap($public_key, 64, "\n", true) . PHP_EOL . $search[1];
        $res = openssl_get_publickey($public_key);
        if ($res) {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res, $last);
            openssl_free_key($res);
        } else {
            exit("公钥格式有误!");
        }
        return $result;
    }


    //同步接口公共部分
    public function aliApis($host,$method,$subject)
    {
        $data = array(
            'app_id' => $this->APPID,
            'method' => $method,
            'charset' => 'utf-8',
            'sign_type' => 'RSA2',
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0',
            'biz_content' => json_encode($subject, JSON_UNESCAPED_UNICODE),
        );
        $data['sign'] = $this->makeSign($data);
        $params = $this->makeUrl($data);
        $url = $host . '?' . $params;
        $resultJson = HttpBase::curlapi($url);
        $result = json_decode($resultJson, true);
        return $result;
    }
}
