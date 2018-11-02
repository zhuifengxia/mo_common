<?php

namespace YkCommon\DataState;

/**
 * pic_log 表.
 *
 */
class PicLogStatus extends \YkCommon\STBase
{
    const OPEN = 1;
    const AUTHEN = 2;
    const HIMSELF = 3;

    protected static $val = [
        self::OPEN => '公开',
        self::AUTHEN => '认证医生才能查看',
        self::HIMSELF => '只有自己能看',
    ];
}
