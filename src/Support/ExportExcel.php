<?php
/**
 * Description: 导出excel.
 * Author: momo
 * Date: 2019-04-24 11:47
 * Copyright: momo
 */

namespace MoCommon\Support;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportExcel
{
    /**
     * 导出excel
     * @param $datas 数据集合 ps:array(array(1,'momo'), array(1,'xiaomo'));
     * @param $titlename 标题 <tr> <th style='width:70px;' >用户ID</th> <th style='width:70px;' >用户昵称</th> </tr>
     * @param $filename 文件名 ps: test.xls
     */
    public static function export($datas,$titlename,$filename)
    {
        $str = "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\"\r\nxmlns:x=\"urn:schemas-microsoft-com:office:excel\"\r\nxmlns=\"http://www.w3.org/TR/REC-html40\">\r\n<head>\r\n<meta http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n</head>\r\n<body>";

        $str .= "<table border=1><head>" . $titlename . "</head>";
        foreach ($datas as $key => $rt) {
            $str .= "<tr>";
            foreach ($rt as $k => $v) {
                $str .= "<td>{$v}</td>";
            }
            $str .= "</tr>\n";
        }
        $str .= "</table></body></html>";
        header("Content-Type: application/vnd.ms-excel; name='excel'");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        header("Expires: 0");
        exit($str);
    }

    /**
     * 导出excel表
     * $data：要导出excel表的数据，接受一个二维数组
     * $name：excel表的表名
     * $head：excel表的表头，接受一个一维数组
     * $key：$data中对应表头的键的数组，接受一个一维数组
     * $imgdomain：如果数据中有图片，图片地址不是带域名的情况传入图片地址域名
     * 备注：此函数缺点是，表头（对应列数）不能超过26；
     *循环不够灵活，一个单元格中不方便存放两个数据库字段的值
     */
    public static function data_export($name='测试表', $data=[], $head=[], $keys=[],$imgdomain="")
    {
        $count = count($head);  //计算表头数量
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        for ($i = 65; $i < $count + 65; $i++) {     //数字转字母从65开始，循环设置表头：
            $sheet->setCellValue(strtoupper(chr($i)) . '1', $head[$i - 65]);
        }

        /*--------------开始从数据库提取信息插入Excel表中------------------*/

        foreach ($data as $key => $item) {             //循环设置单元格：
            //$key+2,因为第一行是表头，所以写到表格时   从第二行开始写
            for ($i = 65; $i < $count + 65; $i++) {     //数字转字母从65开始：
                if(strstr($item[$keys[$i - 65]],".png")||strstr($item[$keys[$i - 65]],".jpg")){
                    $file_info = pathinfo($imgdomain.$item[$keys[$i - 65]]);
                    if (!empty($file_info['basename'])&&in_array($file_info["extension"],["jpg","png","jpeg","gif","bmp","webp"])) { //过滤非文件类型
                        $temp_file = tempnam(sys_get_temp_dir(), 'temp');
                        $img = HttpBase::curlapi($imgdomain.$item[$keys[$i - 65]],[]);
                        file_put_contents($temp_file, $img);
                        $drawing = new Drawing();
                        $drawing->setPath($temp_file);
                        $drawing->setWidth(50);
                        $drawing->setHeight(50);
                        $drawing->setOffsetX(12);
                        $drawing->setOffsetY(12);
                        $drawing->setCoordinates(strtoupper(chr($i)) . ($key + 2));
                        $drawing->setWorksheet($spreadsheet->getActiveSheet());
                        $spreadsheet->getActiveSheet()->getRowDimension(($key + 2))->setRowHeight(50);
                    }else{
                        //不是图片文件，或者其他格式文件，不做处理了
                        $sheet->setCellValue(strtoupper(chr($i)) . ($key + 2), $item[$keys[$i - 65]]);
                        $spreadsheet->getActiveSheet()->getColumnDimension(strtoupper(chr($i)))->setWidth(20); //固定列宽
                    }
                }else{
                    $sheet->setCellValue(strtoupper(chr($i)) . ($key + 2), $item[$keys[$i - 65]]);
                    $spreadsheet->getActiveSheet()->getColumnDimension(strtoupper(chr($i)))->setWidth(20); //固定列宽
                }
            }
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $name . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        //删除清空：
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
        exit;
    }
}
