<?php

namespace YkCommon\DataState;

/**
 * zhaoyisheng_collect_log 关注表.
 *
 *
 */
class CollectLogStatus extends \YkCommon\STBase
{
    const YES = 1;
    const NOT = 2;

    protected static $val = [
        self::YES => '关注',
        self::NOT => '取消关注',
    ];
}
