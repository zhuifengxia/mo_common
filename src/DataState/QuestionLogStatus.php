<?php

namespace YkCommon\DataState;

/**
 * question_log 表.
 *
 */
class QuestionLogStatus extends \YkCommon\STBase
{
    const HIDDEN = 0;
    const ASKING = 1;
    const FINISH = 2;
    const REFUND = 3;

    /**
     * QuestionLogStatus::get();
     *
     */
    protected static $val = [
        self::HIDDEN => '不显示',
        self::ASKING => '求助中',
        self::FINISH => '已完成',
        self::REFUND => '已退单',
    ];
}
