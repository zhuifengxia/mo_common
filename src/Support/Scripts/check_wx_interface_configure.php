<?php
/**
 * 微信公众号: 接口配置信息验证.
 *
 * (测试号配置地址)
 * http://mp.weixin.qq.com/debug/cgi-bin/sandboxinfo?action=showinfo&t=sandbox/index
 *
 * (Document)
 * https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421135319&token=&lang=zh_CN
 *
 * @farwish
 */

$get = $_GET;
$token = 123456;

$array = [$token, $get['timestamp'], $get['nonce']];

sort($array, SORT_STRING);

$str = sha1(implode($array));

if ($str == $get['signature']) {
    echo $get['echostr'];
}
