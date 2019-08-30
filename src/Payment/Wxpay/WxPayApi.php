<?php

namespace MoCommon\Payment\Wxpay;

/**
 *
 * 接口访问类，包含所有微信支付API列表的封装，类中方法为static方法，
 * 每个接口有默认超时时间（除提交被扫支付为10s，上报超时时间为1s外，其他均为6s）
 * @author widyhu
 *
 */
class WxPayApi
{
    /**
     * @var 微信APPID
     */
    protected static $appid;

    /**
     * @var 微信APPSECRET
     */
    protected static $secret;

    //微信KEY
    protected static $key;

    //证书路径
    protected static $sslcert_path;
    protected static $sslkey_path;


    public static function init($appid,$secret,$key,$sslcert_path,$sslkey_path)
    {
        static::$appid = $appid;
        static::$secret = $secret;
        static::$key = $key;
        static::$sslcert_path = $sslcert_path;
        static::$sslkey_path = $sslkey_path;
    }
	/**
	 *
	 * 统一下单，WxPayUnifiedOrder中out_trade_no、body、total_fee、trade_type必填
	 * appid、mchid、spbill_create_ip、nonce_str不需要填入
	 * @param WxPayUnifiedOrder $inputObj
	 * @param int $timeOut
	 * @throws WxPayException
	 * @return 成功时返回，其他抛异常
	 */
	public static function unifiedOrder($xml, $timeOut = 30)
	{
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);
        curl_setopt($ch,CURLOPT_URL, "https://api.mch.weixin.qq.com/pay/unifiedorder");
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        curl_close($ch);
        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $data;
    }

    /**
     * 退款申请
     * @param $xml 请求参数
     * @param int $timeOut 超时时间
     * @return json 数据集合
     */
    public static function payrefund($xml, $timeOut = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);
        curl_setopt($ch,CURLOPT_URL, "https://api.mch.weixin.qq.com/secapi/pay/refund");
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        //设置证书
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT, self::$sslcert_path);
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY, self::$sslkey_path);

        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        curl_close($ch);
        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $data;
    }


    /**
     * 生成签名
     * @param $postarr 待签数组集合
     * @return string  返回签名
     */
    public static function MakeSign($postarr)
    {
        //签名步骤一：按字典序排序参数
        ksort($postarr);
        $stringA = self::ToUrlParams($postarr);
        //签名步骤二：在string后加入KEY
        $string = $stringA . "&key=".self::$key;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $sign = strtoupper($string);
        return $sign;
    }


    public static function ToXml($postarr)
    {
        //转为xml格式
        $xml = "<xml>";
        foreach ($postarr as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key.">".$val."</".$key.">";
//                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * 格式化参数格式化成url参数
     */
    public static function ToUrlParams($postarr)
    {
        $stringA = "";
        foreach ($postarr as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $stringA .= $k . "=" . $v . "&";
            }
        }
        $stringA = trim($stringA, "&");
        return $stringA;
    }
}

