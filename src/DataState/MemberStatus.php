<?php

namespace YkCommon\DataState;

/**
 * zhaoyisheng_m_members 表.
 *
 */
class MemberStatus extends \YkCommon\STBase
{
    const HAS_AUTHEN = 1;
    const NOT_AUTHEN = 0;

    protected static $val = [
        self::HAS_AUTHEN => '已认证',
        self::NOT_AUTHEN => '未认证',
    ];
}
