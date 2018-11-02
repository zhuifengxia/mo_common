<?php

namespace YkCommon\DataState;

class TradeLogPayIssueStatus extends \YkCommon\STBase
{
    const FINISH = 0;
    const ASKING = 1;
    const REFUSE = 2;
    const SCORED = 3;
    const CANCEL = 4;


    protected static $val = [
        self::FINISH => '已完成',
        self::ASKING => '求助中',
        self::REFUSE => '已拒单',
        self::SCORED => '已评分',
        self::CANCEL => '自动取消',
    ];
}
