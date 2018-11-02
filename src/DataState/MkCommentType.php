<?php

namespace YkCommon\DataState;

/**
 * appname_logs.
 *
 */
class MkCommentType extends \YkCommon\STBase
{
    const NORMAL    = 1;
    const STUDENT_QUESTION    = 2;

    protected static $val = [
        self::NORMAL    => '普通评论',
        self::STUDENT_QUESTION    => '学生课程讨论',
    ];
}
