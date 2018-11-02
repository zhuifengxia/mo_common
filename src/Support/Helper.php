<?php

namespace MoCommon\Support;

/**
 * 静态调用系列函数.
 *
 *
 */
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
	public static function custom_mt_uniqid($prefix = 'YK')
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
     * 测试专用.
     *
     * 本机更新其余测试平台使用的 access_token.
     *
     * @farwish
     */
    public static function update_access_token($access_token, $expires_in)
    {   
        $url = 'http://test.kankanyisheng.com/index.php?c=index&a=change_access_token&';

        $str = http_build_query([
            'access_token' => $access_token,
            'expires_at' => time() + $expires_in,
        ]); 

        $url = $url . $str;

        $ret = json_decode(file_get_contents($url), TRUE);

        if ($ret['status'] == 0) {
            return true;
        }   

        return false;
    }

    /**
     * TOKEN 生成.
     *
     * @param $uid
     *
     * @return string
     *
     * @farwish
     */
    public static function gentoken($uid = '')
    {
        $str = microtime() . (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknow') . 
            $uid . '7200';

        $md5 = md5($str);

        $crypt = crypt($md5, 'CRYPT_SHA256');

        $md5 = md5($crypt);

        return substr(strrev($md5), 1);
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
    public static function gen_orderid($prefix = 'YK')
    {
        $uniq = uniqid(TRUE);

        return $prefix . date('Ymd') . $uniq . str_shuffle($uniq);
    }

    /**
     * 新 order_id 生成，32位内.
     *
     * @param $salt integer
     *
     * @return string
     *
     * @farwish
     */
    public static function orderid($salt = '')
    {
        // 14 + 8 + 5 + 3 = 30
        return str_replace('.', '', microtime(true)) . 
            date('Ymd') .
            str_pad(substr($salt, 0, 3), 5, 0) . 
            mt_rand(100, 999);
    }

    /**
     * 红包金额生成.
     *
     * 默认 1 ~ 1.5
     *
     * @param int $min
     * @param int $max
     *
     * @return float.
     *
     * @farwish
     */
    public static function redbag($min = 1, $max = 1)
    {
        $yuan = ($min === $max) ? $min : mt_rand($min, $max);

        return $yuan + (mt_rand(0, 50) / 100);
    }

    /**
     * @param $url
     * @desc file_get_contents 替代函数
     * @author charlesyq
     * @return mixed|string
     */
    public static function get_url_content($url) {
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
    public static function pure_content($str){
        //中文标点
        $char = "。、！？：；﹑•＂…‘’“”〝〞∕¦‖—　〈〉﹞﹝「」‹›〖〗】【»«』『〕〔》《﹐¸﹕︰﹔！¡？¿﹖﹌﹏﹋＇´ˊˋ―﹫︳︴¯＿￣﹢﹦﹤‐­˜﹟﹩﹠﹪﹡﹨﹍﹉﹎﹊ˇ︵︶︷︸︹︿﹀︺︽︾ˉ﹁﹂﹃﹄︻︼（）";

        $pattern = array(
            "/[[:punct:]]/i", //英文标点符号
            '/['.$char.']/u', //中文标点符号
            '/[ ]{2,}/'
        );
        $str = preg_replace($pattern, ' ', $str);

        return $str;
    }




}
