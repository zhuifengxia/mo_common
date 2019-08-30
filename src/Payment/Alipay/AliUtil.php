<?php
/**
 * Description: 支付宝工具类.
 * Author: momo
 * Date: 2019-04-24 15:58
 * Copyright: momo
 */


namespace MoCommon\Payment\Alipay;


class AliUtil
{
    //网关
    private $host = 'https://openapi.alipaydev.com/gateway.do';
    //支付接口
    private $method = 'alipay.trade.wap.pay';
    //支付查询接口
    private $method1 = 'alipay.trade.query';
    //付款接口
    private $method2 = 'alipay.fund.trans.toaccount.transfer';
    //付款查询接口
    private $method3 = 'alipay.fund.trans.order.query';

    private $privateKey;
    private $publicKey;
    private $appid;


    public function __construct($privateKey, $publicKey, $pid = '', $APPID='',$key = '')
    {
        $this->appid = $APPID;
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->pid = $pid;
        $this->key = $key;
    }

    //支付宝发起支付
    public function aliPayment($out_trade_no,$body,$total_amount,$notify_url,$return_url)
    {
        $subject = array(
            'subject' => $body,
            'out_trade_no' => $out_trade_no,//商户唯一订单号
            'total_amount' => $total_amount,//金额
            'product_code' => 'QUICK_WAP_WAY',//销售产品码
        );
        if($this->privateKey && $this->publicKey){
            //RSA2
            $data = array(
                'app_id'       => $this->appid,
                'method'       => $this->method,
                'charset'      => 'utf-8',
                'sign_type'    => 'RSA2',
                'timestamp'    => date('Y-m-d H:i:s'),
                'version'      => '1.0',
                'notify_url'   => $notify_url,
                'return_url'   => $return_url,
                'biz_content'  => json_encode($subject,JSON_UNESCAPED_UNICODE),
            );
            $alipay = new Alipay($this->privateKey,$this->publicKey,'',$this->appid);
        }
        //请求参数
        $data['sign'] = $alipay->makeSign($data);//MD5 - key
        $params = $alipay->makeUrl($data);
        $result = array(
            'status' => 1,
            'url' => $this->host.'?'.$params,
        );
       return $result;
    }

    //支付查询
    public function queryOrder($subject)
    {
        $alipay = new Alipay($this->privateKey, $this->publicKey, '', $this->appid);
        $result = $alipay->aliApis($this->host, $this->method1, $subject);
        $response = $result['alipay_trade_query_response'];
        if ($response['code'] == '10000') {
            //同步验签
            if (!$alipay->rsaCheck(json_encode($response), $this->publicKey, $result['sign'], 'RSA2')) {
                return json_encode(array('code' => 0, 'msg' => '支付宝签名验证失败'));
            }
            //交易状态
            if (!$alipay->checkOrderStatus($response)) {
                return json_encode(array('code' => 0, 'msg' => '交易失败,原因:订单未完成支付'));
            }
        }
        return json_encode($response);
    }

    //支付宝通知校验
    public function aliCheck($postData)
    {
        $alipay = new Alipay($this->privateKey,$this->publicKey,'',$this->appid);
        //异步验签
        if(!$alipay->rsaCheck($alipay->makeStr($postData,false), $this->publicKey, $postData['sign'],'RSA2') ){
//            $alipay->alipayLog('alilog.txt', 'RSA2签名失败!');//失败不做处理
            exit();
        }
        //验证是否来自支付宝的请求
        if(!$alipay->isAlipay($postData)){
//            $alipay->alipayLog('alilog.txt', '不是来之支付宝的通知!');//失败不做处理
            exit();
        }
        //验证交易状态
        if(!$alipay->checkOrderStatus($postData)){
            exit();
        }
    }

    //付款到个人支付宝账号
    public function sellerToUser($out_biz_no,$payee_account,$amount,$payer_show_name,$payee_real_name,$remark)
    {
        //获取id
        $subject = array(
            'out_biz_no' => $out_biz_no,//唯一订单号
            'payee_type' => 'ALIPAY_LOGONID',//PID方式
            'payee_account' => $payee_account,//收款账号
            'amount' => $amount,//金额
            'payer_show_name' => $payer_show_name, //付款方姓名
            'payee_real_name' => $payee_real_name,//收款方支付宝真实姓名
            'remark' => $remark,//转账备注
        );
        $alipay = new Alipay($this->privateKey, $this->publicKey, '', $this->appid);
        $result = $alipay->aliApis($this->host, $this->method2, $subject);
        $response = $result['alipay_fund_trans_toaccount_transfer_response'];
        if ($response['code'] == '10000') {
            //同步验签
            if (!$alipay->rsaCheck(json_encode($response), $this->publicKey, $result['sign'], 'RSA2')) {
                return json_encode(array('code' => 0, 'msg' => '支付宝签名验证失败'));
            }
        } else {
            return json_encode(array('code' => 0, 'msg' => $response['sub_msg']));
        }
        return json_encode($response);
    }

    //单笔转账查询
    public function queryMoney($out_biz_no)
    {
        //获取id
        $subject = array(
            'out_biz_no' => $out_biz_no,//唯一订单号
        );
        $alipay = new Alipay($this->privateKey, $this->publicKey, '', $this->appid);
        $result = $alipay->aliApis($this->host, $this->method3, $subject);
        $response = $result['alipay_fund_trans_order_query_response'];
        //结果逻辑
        if ($response['code'] == '10000') {
            //同步验签
            if (!$alipay->rsaCheck(json_encode($response), $this->publicKey, $result['sign'], 'RSA2')) {
                return json_encode(array('code' => 0, 'msg' => '支付宝签名验证失败'));
            }
            return json_encode(array('code' => 1, 'msg' => 'OK', 'data' => $response));
        } else {
            return json_encode(array('code' => 0, 'msg' => $response['sub_msg']));
        }
    }

}
