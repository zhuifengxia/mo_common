<?php

namespace YkCommon\DataState;

/**
* question_log 表type.
*
*/
class QuestionLogType extends \YkCommon\STBase
{
    const NORMAL  = 0;
    const DEVICE  = 1;
    const EXAMINE = 2;
    const QUALITY = 3;
    const MATCH   = 4;
    const TOPIC   = 5;
    const PAYMENT = 6;
    const PAY_TO_SEE = 7;
    const MUKE = 8;

    /**
     * QuestionLogType::get();
     *
     */
    protected static $val = [
        self::NORMAL  => '普通帖子',
        self::DEVICE  => '设备维修',
        self::EXAMINE => '资格考试',
        self::QUALITY => '精品榜',
        self::MATCH   => '阅片大赛',
        self::TOPIC   => '话题',
        self::PAYMENT => '付费问题',
        self::PAY_TO_SEE => '付费查看的问题',
        self::MUKE => '慕课问题'

    ];
}
