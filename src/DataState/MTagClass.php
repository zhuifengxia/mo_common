<?php

namespace YkCommon\DataState;

/**
 * m_tag_class.
 *
 *
 */
class MTagClass extends \YkCommon\STBase
{
    const NOTDEL = 0;
    const HASDEL = 1;

    protected static $val = [
        self::NOTDEL => '未删',
        self::HASDEL => '已删',
    ];
}
