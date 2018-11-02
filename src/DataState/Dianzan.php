<?php

namespace YkCommon\DataState;

/**
 * zhaoyisheng_dianzan.
 *
 *
 */
class Dianzan extends \YkCommon\STBase
{
    const HASZAN = 1;
    const NOTZAN = 2;

    protected static $val = [
        self::HASZAN => '已赞',
        self::NOTZAN => '取消赞',
    ];
}
