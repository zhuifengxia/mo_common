<?php
/**
 * Description: 创建二维码和图片合并.
 * Author: momo
 * Date: 2019-04-24 15:05
 * Copyright: momo
 */


namespace MoCommon\Support;


class CreateQRCode
{

    /**
     * 根据地址链接生成二维码
     * @param $url 待生成二维码的链接地址
     * @param $backgroundimg  完整背景图地址（二维码放置到此图上）
     * @param $codepath  二维码存放位置
     */
    public static function qrcode_url($url = 'http://www.baidu.com',$bgimgpath,$codepath,$codex=0,$codey=0, $size = 4)
    {
        $errorCorrectionLevel = 'H';//容错级别
        $matrixPointSize = $size;//图片大小慢慢自己调整，只要是int就行

        $QR = $codepath . rand(10000, 99999) . time() . ".png";
        $object = new QRcode();
        $object->png($url, $QR, $errorCorrectionLevel, $matrixPointSize, 2);

        $dst_path = $bgimgpath;//背景图片路径
        if ($bgimgpath) {
            $src_path = $QR;//覆盖图
            //创建图片的实例
            $dst = imagecreatefromstring(file_get_contents($dst_path));
            $src = imagecreatefromstring(file_get_contents($src_path));
            //获取覆盖图图片的宽高
            list($src_w, $src_h) = getimagesize($src_path);
            //将覆盖图复制到目标图片上，最后个参数100是设置透明度（100是不透明），这里实现不透明效果
            imagecopymerge($dst, $src, $codex, $codey, 0, 0, $src_w, $src_h, 100);
//            @unlink($QR);  //删除服务器上二维码图片
            imagepng($dst, $QR);//根据需要生成相应的图片
            imagedestroy($dst);
            imagedestroy($src);
        }
        $data = array('status' => 1, 'img' => $QR);
        return json_encode($data);
    }
}
