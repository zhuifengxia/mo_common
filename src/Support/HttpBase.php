<?php
/**
 * Description: HTTP请求.
 * Author: momo
 * Date: 2019-04-24 11:47
 * Copyright: momo
 */

namespace MoCommon\Support;


class HttpBase
{
    /**
     * 请求方法
     * @param $requesturl请求地址
     * @param $requestdata请求参数
     * @param int $requesttype请求类型0http;1https
     * @param $reqmethod请求方式;0get、1post、2put、3delete
     * @return mixed
     */
    public static function curlapi($requesturl,$requestdata,$requesttype=0,$reqmethod=0,$headers="")
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
        if ($requestdata && $reqmethod != 0) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, ($requestdata));
        }
        $file_contents = curl_exec($ch);//获得返回值
        curl_close($ch);
        return $file_contents;
    }


    /**
     * @param $url
     * @desc file_get_contents 替代函数
     * @author charlesyq
     * @return mixed|string
     */
    public static function get_url_content($url)
    {
        if (extension_loaded('curl')) {
            $curl = curl_init(); // 启动一个CURL会话
            curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
            curl_setopt($curl, CURLOPT_SSLVERSION, 1); //设定SSL版本
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
            curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)'); // 模拟用户使用的浏览器
            //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
            //curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
            curl_setopt($curl, CURLOPT_TIMEOUT, 5); // 设置超时限制防止死循环
            curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
            $content = curl_exec($curl); // 执行操作
            curl_close($curl);
        } else {
            $content = file_get_contents($url);
        }
        return $content;
    }
}
