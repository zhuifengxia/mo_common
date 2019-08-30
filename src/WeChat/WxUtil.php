<?php
/**
 * Description: 微信工具类.
 * Author: momo
 * Date: 2019-04-24 13:36
 * Copyright: momo
 */


namespace MoCommon\WeChat;


use MoCommon\Payment\Wxpay\WxPayApi;

class WxUtil
{
    protected static $appid;
    protected static $secret;
    protected static $key;
    protected static $redis;
    protected static $mchid;
    protected static $sslcert_path;
    protected static $prefix;
    protected static $sslkey_path;

    public static function init($appid, $secret,$key,$mchid,$sslcert_path,$sslkey_path,$redis,$prefix="MO_webname")
    {
        self::$appid = $appid;
        self::$secret = $secret;
        self::$key = $key;
        self::$mchid = $mchid;
        self::$redis = $redis;
        self::$sslcert_path = $sslcert_path;
        self::$sslkey_path = $sslkey_path;
        self::$prefix = $prefix;
    }
    /**
     * @desc  获取access_token 并保存
     * @param $appid appid
     * @param $secret secret
     * @param $redis redis
     * @param $prefix redis名字前缀，缓存key值规则为  MO_{$webname}_access_token
     * @return mixed
     */
    public static function get_access_token()
    {
        $appid = self::$appid;
        $secret = self::$secret;
        $redis = self::$redis;
        $prefix = self::$prefix;
        Wx::init([$appid, $secret]);

        // 获取基础 access_token
        $access_token = $redis->get($prefix . '_access_token');
        if (empty($access_token)) {
            $access = Wx::access_token();
            $access_token = $access['access_token'];
            $redis->set($prefix . '_access_token', $access_token, $access['expires_in'] - 600);
        }
        return $access_token;
    }

    /**
     * @desc  更新 access_token 并保存
     * @param $appid appid
     * @param $secret secret
     * @param $redis redis
     * @param $prefix redis名字前缀，缓存key值规则为  MO_{$webname}_access_token
     * @return mixed
     */
    public static function update_access_token()
    {
        $appid = self::$appid;
        $secret = self::$secret;
        $redis = self::$redis;
        $prefix = self::$prefix;
        Wx::init([$appid, $secret]);
        $access = Wx::access_token();
        $access_token = $access['access_token'];
        $redis->set($prefix . '_access_token', $access_token, $access['expires_in'] - 300);
        return $access_token;
    }


    /**
     * 小程序发送模板消息
     * @param $send_members 发送的用户集合信息
     * @param $send_data 发送的data信息
     * @param $page 点击跳转地址
     * @param $tpl_id 发送模板id
     */
    public static function send_small_template($send_members,$send_data,$page,$tpl_id)
    {
        if ($send_members) {
            $access_token = self::get_access_token();
//            Log::init([
//                'type' => 'File',
//                'path' => Env::get("root_path") . 'public/logs/'
//            ]);
            foreach ($send_members as $item) {
                $tpl_ret = Wx::sp_send_template($access_token, $item['openid'], $tpl_id, $send_data, $page, $item['formid']);
//                Log::init([
//                    'type' => 'File',
//                    'path' => Env::get("root_path") . 'public/logs/'
//                ]);
//                Log::error("\r\n".date('Y-m-d H:i:s')."  ".json_encode($tpl_ret));

            }
        }
    }

    /**
     * 小程序生成带参数的二维码
     */
    public static function sp_qrcode_create($data)
    {
        $access_token = self::get_access_token();
        $imgdata = Wx::sp_qrcode_create($access_token, $data);
        //生成二维码
        $base64_image = "data:image/jpeg;base64," . base64_encode($imgdata);
        return $base64_image;
    }

    /**
     * 微信发起支付
     * @param $out_trade_no 商家系统订单号
     * @param $totalprice 付款金额 单位：分
     * @param $openid 用户openid
     * @param $notifyurl 回调地址
     * @param $attach 自定义参数数组jsonecode
     */
    public static function wxPayment($out_trade_no,$totalprice,$openid,$notifyurl,$body="支付",$trade_type='JSAPI',$attach=0)
    {
        $inputObj=array(
            "appid"=>self::$appid,
            "body"=>$body,
            "attach"=>$attach,
            "mch_id"=>self::$mchid,
            "nonce_str"=>md5(time()),
            "notify_url"=>$notifyurl,
            "out_trade_no"=>$out_trade_no,
            "sign"=>"",
            "spbill_create_ip"=>$_SERVER['REMOTE_ADDR'],
            "total_fee"=>$totalprice,
            "trade_type"=>$trade_type,
            'openid' => $openid,
        );
        WxPayApi::init(self::$appid,self::$secret,self::$key,self::$sslcert_path,self::$sslkey_path);
        //签名
        $sign=WxPayApi::MakeSign($inputObj);
        $inputObj['sign']=$sign;
        //转为xml格式
        $xml=WxPayApi::ToXml($inputObj);
        //请求预订单
        $data=WxPayApi::unifiedOrder($xml);
        return $data;
    }

    /**
     * 退款申请
     * @param $orderno 订单编码
     * @param $totalprice 订单总金额
     * @param $refundprice 退款金额
     * @param $out_refund_no 退款订单号
     * @return \MoCommon\WeChat\Wxpay\json
     */
    public static function wxRefund($orderno,$totalprice,$refundprice,$notify_url,$out_refund_no='')
    {
        $nonce_str = md5(time());
        $padata = array(
            "appid" => self::$appid,
            "mch_id" => self::$mchid,
            "nonce_str" => $nonce_str,
            "notify_url" => $notify_url,
            "out_trade_no" => $orderno,
            "sign" => "",
            "out_refund_no" => $out_refund_no,
            "total_fee" => $totalprice * 100,
            "refund_fee" => $refundprice * 100
        );
        WxPayApi::init(self::$appid, self::$secret, self::$key, self::$sslcert_path, self::$sslkey_path);
        //签名
        $sign = WxPayApi::MakeSign($padata);
        $padata['sign'] = $sign;
        //转为xml格式
        $xml = WxPayApi::ToXml($padata);
        //退款申请
        $data = WxPayApi::payrefund($xml);
        return $data;
    }
}
