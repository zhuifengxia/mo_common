<?php
/**
 * Description: 发送短信类.
 * Author: momo
 * Date: 2019-04-24 11:47
 * Copyright: momo
 */

namespace MoCommon\Smscode;

use MoCommon\Support\Helper;
use MoCommon\Support\HttpBase;

class SmsCode
{
    /**
     * 请求不同的短信接口
     * $mobile  手机号
     * content  内容 取决于短信接口 , 有的不需要输入完整的
     * type     短信调用的接口 1中正云；2天瑞；3其他
     * whiteips     短信调用ip白名单数组
     * sign     短信签名id  有的短信接口需要签名id
     */
    public static function Smscode($mobile,$content,$type=0,$whiteips=[],$sign='【默认签名】',$accesskey='',$secret='')
    {
        $ip = Helper::getIp();
        if ($whiteips) {
            if (!in_array($ip, $whiteips)) {
                return ['code' => 0, 'msg' => '该请求ip不在白名单', 'ip' => $ip];//不在白名单
            }
        }

        $data['mobile'] = $mobile;
        switch ($type) {
            //中正云
            case '1':
                $smsapi = 'http://service.winic.org:8009/sys_port/gateway/index.asp?';
                $user = 'zxwefwiu';
                $pass = 'sief293';
                $data = "id=%s&pwd=%s&to=%s&Content=%s&time=";
                $id = urlencode(iconv("UTF-8", "GB2312", $user));
                //拼接短信
                $constr = $sign . $content;
                $msg = urlencode(iconv("UTF-8", "GB2312", $constr));
                $rdata = sprintf($data, $id, $pass, $mobile, $msg);
                $result = HttpBase::curlapi($smsapi, $rdata, 0, 1);
                if ($result) {
                    $result = substr($result, 0, 3);
                    if ($result['code'] == '000') {
                        return ['code' => 1, 'msg' => '短信发送成功', 'ip' => $ip];
                    } else {
                        //发送失败
                        return ['code' => 2, 'msg' => $data['msg'], 'ip' => $ip];
                    }
                }
                break;
            //天瑞
            case '2':
                $accesskey || $accesskey = 'N6pNSDBFD0r2wVMV';
                $secret || $secret = 'BaXlNckUnTxqiSiYbYE0NCNrpIzglDCB';
                $host = 'http://api.1cloudsp.com/api/v2/send';
                $data['accesskey'] = $accesskey;
                $data['secret'] = $secret;
                $data['sign'] = $sign;
                //$data['templateId'] = $scene;
                $data['content'] = $content;
                $res = HttpBase::curlapi($host, $data, 0, 1);
                if ($res) {
                    $data = json_decode($res, true);
                    if ($data['code'] == '0') {
                        return ['code' => 1, 'msg' => '短信发送成功', 'ip' => $ip];
                    } else {
                        return ['code' => 2, 'msg' => $data['msg'], 'ip' => $ip];
                    }
                }
                break;
            case '3':
                $url = "http://www.dksms.cn/sendSms.php?type=2";
                $result = file_get_contents($url . '&mobile=' . $mobile . '&content=' . $content . '&sign=' . $sign . '&accesskey=' . $accesskey . '&secret=' . $secret);
                $result = json_decode($result, true);
                if ($result['code']) {
                    return ['code' => 1, 'msg' => '短信发送成功', 'ip' => $ip];
                } else {
                    return ['code' => 2, 'msg' => $data['msg'], 'ip' => $ip];
                }
                break;
            default:
                return ['code' => 3, 'msg' => '当前没有默认的短信方案,请联系客服', 'ip' => $ip];
                break;
        }
        return ['code' => 4, 'msg' => '短信中心出现异常,请联系客服', 'ip' => $ip];
    }
}
