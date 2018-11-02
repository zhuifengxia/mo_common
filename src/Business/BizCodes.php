<?php

namespace MoCommon\Business;

/**
 * 业务类状态码.
 *
 * @farwish
 */
class BizCodes extends \MoCommon\STBase
{
    const SIMPLE_WORDS   = 2001;
    const BAD_WORDS      = 2002;

    protected static $val = [
        self::SIMPLE_WORDS  => '内容过于简单',
        self::BAD_WORDS     => '内容含有敏感词汇',
    ];
}
