<?php
/**
 * Description: 状态基类.
 * Author: momo
 * Date: 2019-04-24 11:47
 * Copyright: momo
 */

namespace MoCommon;

abstract class STBase
{
	protected static $val = [];

    /**
     * 获取.
     *
     * @return string | array
     */
    public static function get($key = null)
    {
        return (isset($key) && isset(static::$val[$key])) ? static::$val[$key] : '';
    }
}
