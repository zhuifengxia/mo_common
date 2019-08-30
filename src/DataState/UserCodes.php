<?php
/**
 * Description: 用户相关状态.
 * Author: momo
 * Date: 2019-04-24 11:47
 * Copyright: momo
 */

namespace MoCommon\DataState;

/**
 * 用户相关状态.
 * @momo
 */
class UserCodes extends \MoCommon\STBase
{
    const DATA_EXIST = 2001;

    protected static $val = [
        self::DATA_EXIST => '用户已经存在',
    ];
}
