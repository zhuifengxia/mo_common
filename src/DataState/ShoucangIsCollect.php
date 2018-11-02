<?php

namespace YkCommon\DataState;

/**
 * zhaoyisheng_shoucang 收藏表.
 *
 *
 */
class ShoucangIsCollect extends \YkCommon\STBase
{
    const YES = 1;
    const NOT = 2;

    protected static $val = [
        self::YES => '收藏',
        self::NOT => '取消收藏',
    ];
}
