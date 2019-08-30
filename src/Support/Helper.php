<?php
/**
 * Description: 系列公共函数.
 * Author: momo
 * Date: 2019-04-24 11:47
 * Copyright: momo
 */

namespace MoCommon\Support;


class Helper
{
	/**
	 * 先后调用 array_column 与 array_combine .
	 *
	 * @farwish
	 */
	public static function array_column_combine($array, $column)
	{
		$res = array_column($array, $column);

		return array_combine($res, $array);
	}

	/**
	 * date 封装默认格式.
	 *
	 * @farwish
	 */
	public static function format_date($time = '', $format = 'Y-m-d H:i:s')
	{
		$time = $time ?: time();

		return date($format, $time);
	}

    /**
     * 简化IN条件拼接.
     *
     * @param string | array $data
     * @param string
     * @param boolean in or not in, default is in.
     *
     * @farwish
     */
    public static function join_condition($data, $column, $in = true)
    {
        $join = is_array($data) ? (join(',', $data) ?: -1) : ($data ?: -1);
        $kw = $in ? "IN" : "NOT IN";
        return "$column $kw(" . $join . ")";
    }

	/**
	 * 唯一值生成规则.
	 *
	 * @farwish
	 */
	public static function custom_mt_uniqid($prefix = 'MO')
	{
		$value = mt_rand();

		$str = md5 ( uniqid($prefix, TRUE) . microtime() . $value . str_shuffle($value) );

		$new_str = '';

		for ($i = 0; $i < 10; $i++) {
			$rand[] = mt_rand(10, 31);
		}

		$rand = array_unique($rand);

		for ($i = 0; $i < 32; $i++) {

			if (in_array($i, $rand)) {
				$new_str .= strtoupper($str[$i]);
			} else {
				$new_str .= $str[$i];
			}
		}

		return date('mY') . $new_str;
	}

    /**
     * 批量insert语句.
     *
     * @param string $table
     * @param string $column Example: id,name
     * @param array $data
     *
     * @return string
     *
     * @farwish
     */
    public static function build_insert_sql($table, $column, array $data)
    {
        $sql = "INSERT INTO {$table} ({$column}) VALUES ";

        $value = '';

        foreach ($data as $val) {
            $value = ($value ? ',' : '') . '("' . join('","', $val) . '")';
            $sql .= $value;
        }

        return $sql;
    }

    /**
     * 构造 AND 条件语句.
     *
     * @param array $array.
     *
     * @farwish
     */
    public static function build_and_condition(array $array)
    {
        $str = '';
        foreach ($array as $key => $val) {
            $kv = "{$key}='{$val}'";
            $str .= $str ? ' AND ' . $kv : $kv;
        }
        return $str;
    }

    /**
     * @param $sql :新加的sql
     * @param $join_condition ：连接条件 and / or
     * @param $where：之前where的sql
     *
     * @desc   拼装查询语句
     *
     * @author charlesyq
     * @return string
     */
    public static function build_where_sql($sql,$join_condition,$where)
    {
        if(empty($where)){
            return "$where $sql"."  ";
        }else{
            return "$where $join_condition $sql"."   ";
        }

    }

    /**
     * @param $conditions:查询条件数组
     *
     * @desc   将查询条件数组转化成where的sql语句
     *
     * @author charlesyq
     * @return string
     */
    public static function where_condition($conditions)
    {
        $where = '';
        foreach($conditions as $key=>$value){
            if($key==0){
                $where = $value;
            }else{
                $where = $where." and ".$value;
            }
        }
        return $where;

    }


    /**
     * 使用的server_name.
     *
     * @farwish
     */
    public static function real_server()
    {
        $protocal = ($_SERVER['SERVER_PORT'] == '443') ? 'https://' : 'http://';

        if ( isset($_SERVER['HTTP_X_FORWARDED_SERVER']) ) {
            $host = $_SERVER['HTTP_X_FORWARDED_SERVER'];
        } else {
            $host = $_SERVER['SERVER_NAME'] ?: '';
        }

        return $protocal . $host;
    }

    /**
     * 当前页面完整Url.
     *
     * @farwish
     */
    public static function full_url()
    {
        $protocal = ($_SERVER['SERVER_PORT'] == '443') ? 'https://' : 'http://';

        if ( isset($_SERVER['HTTP_X_FORWARDED_SERVER']) ) {
            $host = $_SERVER['HTTP_X_FORWARDED_SERVER'];
        } else {
            $host = $_SERVER['SERVER_NAME'] ?: '';
        }

        $uri = $_SERVER['REQUEST_URI'] ?: '';

        return $protocal . $host . $uri;
    }

    /**
     * 来路地址url, 没有到首页.
     *
     * @return string
     *
     * @farwish
     */
    public static function referer()
    {
        $protocal = ($_SERVER['SERVER_PORT'] == '443') ? 'https://' : 'http://';

        if ( isset($_SERVER['HTTP_REFERER']) ) {
            // 来路
            $direct = $_SERVER['HTTP_REFERER'];
        } else if ( isset($_SERVER['HTTP_X_FORWARDED_SERVER']) ) {
            // 代理地址
            $direct = $protocal . $_SERVER['HTTP_X_FORWARDED_SERVER'];
        } else {
            // 默认
            $direct = $protocal . $_SERVER['SERVER_NAME'];
        }

        return $direct;
    }


    /**
     * 是否微信客户端打开.
     *
     * @return bool
     *
     * @farwish
     */
    public static function isInWechat()
    {
        return isset( $_SERVER['HTTP_USER_AGENT'] )
            ? (bool)(stristr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger'))
            : false;
    }

    /**
     * order_id 生成, 36+ 位数.
     *
     * @param string $prefix
     *
     * @return string
     *
     * @farwish
     */
    public static function get_orderid($prefix = 'MO')
    {
        $uniq = uniqid(TRUE);

        return $prefix . date('Ymd') . $uniq . str_shuffle($uniq);
    }

    /**
     * 新 order_id 生成，32位内.
     *
     * @param $prefix string
     *
     * @return string
     *
     * @farwish
     */
    public static function orderid($prefix = 'MO')
    {
        return $prefix.str_replace('.', '', microtime(true)) .
            date('Ymd') .mt_rand(100, 999);
    }


    /**
     * @param $string： 要截取的字符串
     * @param $length： 截取的宽度，不是字节数
     * @param string： $dot 截取的后缀
     * @param string： $charset 字符编码
     * @desc  截取字符串
     * @author charlesyq
     * @return mixed|string
     */
    public static function cut_str($string, $length, $dot = ' ...', $charset = 'utf-8')
    {
        if (strlen($string) <= $length) {
            return $string;
        }
        $str = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;', '&nbsp;'), array('&', '"', '<', '>', ' '), $string);
        $strcut = '';
        if (strtolower($charset) == 'utf-8') {
            $n = $tn = $noc = 0;
            while ($n < strlen($str)) {
                $t = ord($str[$n]);
                if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1;
                    $n++;
                    $noc++;
                } elseif (194 <= $t && $t <= 223) {
                    $tn = 2;
                    $n += 2;
                    $noc += 2;
                } elseif (224 <= $t && $t <= 239) {
                    $tn = 3;
                    $n += 3;
                    $noc += 2;
                } elseif (240 <= $t && $t <= 247) {
                    $tn = 4;
                    $n += 4;
                    $noc += 2;
                } elseif (248 <= $t && $t <= 251) {
                    $tn = 5;
                    $n += 5;
                    $noc += 2;
                } elseif ($t == 252 || $t == 253) {
                    $tn = 6;
                    $n += 6;
                    $noc += 2;
                } else {
                    $n++;
                }
                if ($noc >= $length) {
                    break;
                }
            }
            if ($noc > $length) {
                $n -= $tn;
            }
            $strcut = substr($str, 0, $n);
        } else {
            for ($i = 0; $i < $length; $i++) {
                $strcut .= ord($str[$i]) > 127 ? $str[$i] . $str[++$i] : $str[$i];
            }
        }
        $strcut = str_replace(array('"', '<', '>'), array('&quot;', '&lt;', '&gt;'), $strcut);
        $strcut != $string && $strcut .= $dot;
        return $strcut;
    }

    /**
     * @param $str
     *
     * @desc 将标点符号去掉
     *
     * @author charlesyq
     * @return mixed
     */
    public static function pure_content($str)
    {
        //中文标点
        $char = "。、！？：；﹑•＂…‘’“”〝〞∕¦‖—　〈〉﹞﹝「」‹›〖〗】【»«』『〕〔》《﹐¸﹕︰﹔！¡？¿﹖﹌﹏﹋＇´ˊˋ―﹫︳︴¯＿￣﹢﹦﹤‐­˜﹟﹩﹠﹪﹡﹨﹍﹉﹎﹊ˇ︵︶︷︸︹︿﹀︺︽︾ˉ﹁﹂﹃﹄︻︼（）";

        $pattern = array(
            "/[[:punct:]]/i", //英文标点符号
            '/[' . $char . ']/u', //中文标点符号
            '/[ ]{2,}/'
        );
        $str = preg_replace($pattern, ' ', $str);

        return $str;
    }

    /**
     * 计算两点之间的距离
     * @param $lat1 纬度1
     * @param $lng1 经度1
     * @param $lat2 纬度2
     * @param $lng2 经度2
     * @param int $len_type 1m；2km;
     * @param int $decimal
     * @return float
     */
    public static function get_distance($lat1, $lng1, $lat2, $lng2, $len_type = 1, $decimal = 2)
    {
        $radLat1 = $lat1 * PI() / 180.0;   //PI()圆周率
        $radLat2 = $lat2 * PI() / 180.0;
        $a = $radLat1 - $radLat2;
        $b = ($lng1 * PI() / 180.0) - ($lng2 * PI() / 180.0);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s = $s * 6378.137;
        $s = round($s * 1000);
        if ($len_type-- > 1) {
            $s /= 1000;
        }
        return round($s, $decimal);
    }


    /**
     *生成密码
     */
    public static function make_password($str, $prefix='momo')
    {
        return md5(substr(md5($str), 8, 10) . $prefix);
    }

    /**
     * 接口响应格式
     */
    public static function api_respond($data=[],$status='',$msg='')
    {
        $status = $status ?: Codes::ACTION_SUC;
        $msg = $msg ?: Codes::get(Codes::ACTION_SUC);
        $arr = [
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ];
        return json($arr);
    }

    /**
     * 生成token
     */
    public static function get_token($uid = '')
    {
        $str = microtime() . (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknow') .
            $uid . '7200';

        $md5 = md5($str);

        $crypt = crypt($md5, 'CRYPT_SHA256');

        $md5 = md5($crypt);

        return substr(strrev($md5), 1);
    }

    /**
     * 验证手机号
     * @param $number 手机号
     */
    public static function check_phone($number)
    {
        if (preg_match("/^1\d{10}$/", $number)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 验证银行卡
     * @param $card 银行卡号
    */
    public static function check_bankcard($card)
    {
        $n = 0;
        for ($i = strlen($card); $i >= 1; $i--) {
            $index = $i - 1;
            //偶数位
            if ($i % 2 == 0) {
                $n += $card{$index};
            } else {//奇数位
                $t = $card{$index} * 2;
                if ($t > 9) {
                    $t = (int)($t / 10) + $t % 10;
                }
                $n += $t;
            }
        }
        return ($n % 10) == 0;
    }

    /**
     * 生成随机字符串
     * @param $lenth 验证码长度
     * @param $codetype 0字符串；1数字
     */
    public static function verify_code($codetype=0,$lenth=4)
    {
        if (empty($codetype)) {
            $strs = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890";
            $code = substr(str_shuffle($strs), mt_rand(0, strlen($strs) - 11), $lenth);
        } else {
            $randStr = str_shuffle('1234567890');
            $code = substr($randStr, 0, 4);
        }
        return $code;
    }

    /**
     * 获取客户端ip地址
     * @param $type 0正常ip地址；1 经过ip2long之后的数据
     */
   public static function getIp($type = 0)
   {
       $type = $type ? 1 : 0;
       static $ip = NULL;
       if ($ip !== NULL) return $ip[$type];
       if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
           $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
           $pos = array_search('unknown', $arr);
           if (false !== $pos) unset($arr[$pos]);
           $ip = trim($arr[0]);
       } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
           $ip = $_SERVER['HTTP_CLIENT_IP'];
       } elseif (isset($_SERVER['REMOTE_ADDR'])) {
           $ip = $_SERVER['REMOTE_ADDR'];
       }
       // IP地址合法验证
       $long = sprintf("%u", ip2long($ip));
       $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
       return $ip[$type];
   }

    /**
     * 删除文件夹下的文件以及文件夹
     * @param $path 文件目录
     */
   public static function delDir($path)
   {
        //如果是目录则继续
       if (is_dir($path)) {
           //扫描一个文件夹内的所有文件夹和文件并返回数组
           $p = scandir($path);
           foreach ($p as $val) {
               //排除目录中的.和..
               if ($val != "." && $val != "..") {
                   //如果是目录则递归子目录，继续操作
                   if (is_dir($path . $val)) {
                       //子目录中操作删除文件夹和文件
                       self::delDir($path . $val . '/');
                       //目录清空后删除空文件夹
                       @rmdir($path . $val . '/');
                   } else {
                       //如果是文件直接删除
                       unlink($path . $val);
                   }
               }
           }
       }
   }
}
