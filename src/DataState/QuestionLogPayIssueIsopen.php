<?php

namespace YkCommon\DataState;

/**
 * question_log 表.
 *
 * 付费问 ：是否公开
 *
 */
class QuestionLogPayIssueIsopen extends \YkCommon\STBase
{
    const NOT = 0;
    const YES = 1;

    protected static $val = [
        self::NOT => '否',
        self::YES => '是',
    ];
}
