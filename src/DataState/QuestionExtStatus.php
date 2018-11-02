<?php

namespace YkCommon\DataState;

/**
 * question_log 表.
 *
 */
class QuestionExtStatus extends \YkCommon\STBase
{
    const REWARD_STATUS_NOT = 0;
    const REWARD_STATUS_YES = 1;
    const REWARD_STATUS_PUNISH = 2;

    /**
     * QuestionLogStatus::get();
     *
     */
    protected static $val = [
        self::REWARD_STATUS_NOT => '无奖励',
        self::REWARD_STATUS_YES => '奖励',
        self::REWARD_STATUS_PUNISH => '奖励取消并处罚',
    ];
}
