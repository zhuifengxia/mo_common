<?php

namespace MoCommon\Services;

/**
 * http请求相关
 *
 * @farwish
 */
class HttpBase
{
    /**
     * 请求方法
     * @param $requesturl请求地址
     * @param $requestdata请求参数
     * @param int $requesttype请求类型0http;1https
     * @param $reqmethod请求方式;get、post、put、delete
     * @return mixed
     */
    public static function curlapi($requesturl,$requestdata,$requesttype=0,$reqmethod,$headers="")
    {
        $ch = curl_init();
        if ($requesttype == 1) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true); // 从证书中检查SSL加密算法是否存在
        }
        curl_setopt($ch, CURLOPT_URL, $requesturl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        } else {
            //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/json'));
        }
        switch ($reqmethod) {
            case 0 :
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case 1:
                curl_setopt($ch, CURLOPT_POST, 1);
                break;
            case 2 :
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                break;
            case 3:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
        }
        if ($requestdata&&$reqmethod!=0) {
            curl_setopt($ch, CURLOPT_POSTFIELDS,($requestdata));
        }
        $file_contents = curl_exec($ch);//获得返回值
        curl_close($ch);
        return $file_contents;
    }
}
