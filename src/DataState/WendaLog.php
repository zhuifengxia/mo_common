<?php

namespace YkCommon\DataState;

/**
 * Zhaoyisheng_wenda_log.
 *
 *
 */
class WendaLog extends \YkCommon\STBase
{
    const NOTDEL = 0;
    const HASDEL = 1;

    protected static $val = [
        self::NOTDEL => '未删除',
        self::HASDEL => '已删除',
    ];
}
