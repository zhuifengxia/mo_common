<?php
/**
 * Description: 通用状态类.
 * Author: momo
 * Date: 2019-04-24 11:47
 * Copyright: momo
 */

namespace MoCommon\Support;


class Codes extends \MoCommon\STBase
{
    const ACTION_SUC = 1000;
    const ACTION_FAL = 1001;
    const ACTION_ILL = 1002;
    const NO_SIGNIN  = 1003;

    const PARAM_ERR  = 1004;
    const INSERT_ERR = 1005;
    const UPDATE_ERR = 1006;
    const DELETE_ERR = 1007;

    protected static $val = [
        self::ACTION_SUC => '操作成功',
        self::ACTION_FAL => '操作失败',
        self::ACTION_ILL => '非法操作',
        self::NO_SIGNIN  => '未登陆',

        self::INSERT_ERR => '添加失败',
        self::UPDATE_ERR => '更新失败',
        self::DELETE_ERR => '删除失败',
        self::PARAM_ERR  => '缺少必选参数或非法的参数',
    ];
}
