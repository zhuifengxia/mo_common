<?php
/**
 * Description: 文件上传类.
 * Author: momo
 * Date: 2019-04-24 11:47
 * Copyright: momo
 */

namespace MoCommon\Support;

class UploadFiles
{

    /**
     * 单个文件上传
     * @param $typelist 可以上传的文件类型;-1所有类型
     * @param $file 文件内容
     * @param $filepath 文件上传路径
     * @param $imgfile 1图片文件；0其他文件
     * @param $rename 1重命名，0文件原始名字
     * @param $viewpath 外部访问地址
     * @param $filesize 文件大小限制数字M为单位
     */
    public static function single_file_upload($typelist,$file,$filepath,$isimgfile=1,$rename=1,$viewpath="",$filesize=0)
    {
        $result_data = [
            'status' => -1,
            'msg' => '上传失败',
            'data' => ""
        ];
        if (empty($typelist)) {
            //定义可以上传的文件类型
            $typelist = array("image/gif", "image/jpg", "image/jpeg", "image/png");
        }
        if ($filesize) {
            //有值说明需要校验文件上传大小限制
            //判断上传的文件大小是否符合标准
            $tempsize = $file['size'] / 1024 / 1024;
            if ($tempsize >= $filesize)//超过指定文件大小就终止上传操作
            {
                $result_data['msg'] = "文件大小不能超过{$filesize}M";
                return $result_data;
            }
        }

        if (is_uploaded_file($file['tmp_name'])) {
            if ($typelist != -1 && !in_array($file['type'], $typelist)) {
                $result_data['msg'] = "文件格式错误";
            } else {
                if ($isimgfile) {
                    //读取图片内容
                    $txt = fopen($file['tmp_name'], "rb");
                    $bin = fread($txt, $file['size']);
                    fclose($txt);
                    $pos = strpos($bin, '<?php');
                } else {
                    $pos = false;
                }
                if (!$pos) {
                    //获取文件扩展名
                    $exten_name = pathinfo($file['name'], PATHINFO_EXTENSION);
                    if ($rename) {
                        //重新命名图片名称
                        $picname = Helper::custom_mt_uniqid() . "." . $exten_name;//重新命名文件名
                    } else {
                        $picname = $file['name'];//原始文件名
                    }
                    $fpath = $filepath;
                    //路径是否存在，不存在则创建
                    if (!is_dir($fpath)) mkdir($fpath, 0777);
                    //调用move_uploaded_file（）函数，进行文件转移
                    $path = $fpath . $picname;
                    if (move_uploaded_file($file['tmp_name'], $path)) {
                        $resultpath = $viewpath . $picname;
                        $result_data['status'] = 0;
                        $result_data['msg'] = '成功';
                        $result_data['data'] = $resultpath;
                    }
                } else {
                    $result_data['msg'] = "该文件不是图片文件";
                }
            }
        } else {
            $result_data['msg'] = "文件格式错误";
        }
        return $result_data;
    }

    /**
     * 多个文件上传
     * @param $typelist  可以上传的文件类型;-1所有类型
     * @param $files 文件内容
     * @param $imgfile 1图片文件；0其他文件
     * @param $rename 1重命名，0文件原始名字
     * @param $filepath 文件上传路径
     * @param $viewpath 外部访问地址
     * @param $filesize 文件大小限制数字M为单位
     */
    public static function multiple_file_upload($typelist,$files,$filepath,$isimgfile=1,$rename=1,$viewpath="",$filesize=0)
    {
        $result_data = [
            'status' => 0,
            'msg' => '上传成功',
            'data' => ""
        ];
        if (empty($typelist)) {
            //定义可以上传的文件类型
            $typelist = array("image/gif", "image/jpg", "image/jpeg", "image/png");
        }
        $uploadfailed = 0;//上传失败个数
        $filetypeillegal = 0;//非法文件类型
        $filedsize = 0;//文件大小超过指定标准
        $up_info = $files;//获取图片文件信息
        for ($i = 0; $i < count($up_info['name']); $i++) {
            //判断上传的文件类型是否合法
            if ($typelist != -1 && !in_array($up_info['type'][$i], $typelist)) {
                $filetypeillegal++;
                continue;
            }
            if ($filesize) {
                //判断上传的文件大小是否符合标准
                $siezs = $up_info['size'][$i] / 1024 / 1024;
                if ($siezs >= $filesize)//超过指定大小就跳出该循环
                {
                    $filedsize++;
                    continue;
                }
            }
            //判断是否是上传的文件，并执行上传
            if (is_uploaded_file($up_info['tmp_name'][$i])) {
                //获取文件扩展名
                $exten_name = pathinfo($up_info['name'][$i], PATHINFO_EXTENSION);

                if ($rename) {
                    //重新命名图片名称
                    $picname = Helper::custom_mt_uniqid() . "." . $exten_name;//重新命名文件名
                } else {
                    $picname = $up_info['name'][$i];//原始文件名
                }

                $fpath = $filepath;
                //路径是否存在，不存在则创建
                if (!is_dir($fpath)) mkdir($fpath, 0777);
                //调用move_uploaded_file（）函数，进行文件转移
                $path = $fpath . $picname;
                if (move_uploaded_file($up_info['tmp_name'][$i], $path)) {
                    $resultpath = $viewpath . $picname;
                    $fileurlarr[] = $resultpath;
                } else {
                    //上传失败
                    $uploadfailed++;
                    continue;
                }
            } else {
                $result_data['status'] = -1;
                $result_data['msg'] = '没有要上传的文件';
            }
        }
        $result_data['data'] = $fileurlarr;
        $result_data['uploadfailed'] = $uploadfailed;
        $result_data['filetypeillegal'] = $filetypeillegal;
        $result_data['filedsize'] = $filedsize;
        return $result_data;
    }


    /**
     * 压缩文件
     */
    /**
     * desription 压缩图片
     * @param sting $imgsrc 图片路径
     * @param string $imgdst 压缩后保存路径
     */
    public static function image_png_size_add($imgsrc,$imgdst)
    {
        list($width, $height, $type) = getimagesize($imgsrc);
        $new_width = $width > 600 ? $width * 0.5 : $width;
        $new_height = $height > 600 ? $height * 0.5 : $height;
        switch ($type) {
            case 1:
                $giftype = check_gifcartoon($imgsrc);
                if ($giftype) {
                    $image_wp = imagecreatetruecolor($new_width, $new_height);
                    $image = imagecreatefromgif($imgsrc);
                    imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                    imagejpeg($image_wp, $imgdst, 65);
                    imagedestroy($image_wp);
                }
                break;
            case 2:
                $image_wp = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefromjpeg($imgsrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagejpeg($image_wp, $imgdst, 65);
                imagedestroy($image_wp);
                break;
            case 3:
                $image_wp = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefrompng($imgsrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagejpeg($image_wp, $imgdst, 65);
                imagedestroy($image_wp);
                break;
        }
    }
}
