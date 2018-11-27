<?php
/**
 * 导出excel
 * User: liudanfeng
 * Date: 2018/11/23
 * Time: 上午10:22
 */

namespace MoCommon\Services;

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
}