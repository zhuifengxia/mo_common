<?php

namespace YkCommon\DataState;

/**
 * appname_zhaoyisheng_trade_log.
 *
 */
class MoneyFlowType extends \YkCommon\STBase
{
    const NORMAL                  = 0;

    //收到赞赏帖子收入
    const RECEIVE_REWARD_QUESTION = 1;

    //收到赞赏回答收入
    const RECEIVE_REWARD_REPLY    = 2;

    //收到一对一咨询费
    const RECEIVE_ONE_TO_ONE      = 3;

    //一对一咨询拒单退款
    const REFUSE_ONE_TO_ONE       = 4;

    //回答老师收到查看付费提成
    const TEACHER_RECEIVE_SEE_ONE_TO_ONE_REWARD = 5;

    //提问者收到查看付费病例提成
    const STUDENT_RECEIVE_SEE_ONE_TO_ONE_REWARD = 6;

    //收到慕课报名费用
    const RECEIVE_MK_ENROLL       = 9;

    //收到查看慕课视频费用
    const RECEIVE_MK_SEE_VIDEO    = 8;

    //提现扣钱
    const WITHDRAW                = 7;

    //认证奖励
    const RENZHENG                = 10;

    //认证邀请奖励
    const INVITE_RENZHENG         = 11;

    //认证邀请奖励
    const RENZHENG_WITH_INVITE         = 12;


    //做个检查

    const RECEIVE_QUESTION_REWARD  = 1;
    const REBATE =2;
    const BYJ_WITHDRAW                = 3;

    const  SP_WITHDRAW = 2;



    protected static $val = [
        self::NORMAL            => '余额金额变动',

        self::RECEIVE_REWARD_QUESTION   => '帖子被赞赏收入',

        self::RECEIVE_REWARD_REPLY      => '回答被赞赏收入',

        self::RECEIVE_ONE_TO_ONE       => '一对一付费回答收入',

        self::REFUSE_ONE_TO_ONE        =>'一对一咨询拒单退款',

        self::TEACHER_RECEIVE_SEE_ONE_TO_ONE_REWARD => '回答老师收到查看付费提成',

        self::STUDENT_RECEIVE_SEE_ONE_TO_ONE_REWARD => '提问者收到查看付费病例提成',

        self::RECEIVE_MK_ENROLL        => '慕课学员报名收入',

        self::RECEIVE_MK_SEE_VIDEO        => '慕课视频被查看收入',

        self::WITHDRAW                 =>'提现',

        self::RENZHENG                 =>'普通认证奖励',

        self::INVITE_RENZHENG                 =>'邀请用户认证奖励',

        self::RENZHENG_WITH_INVITE                 =>'被邀请认证奖励',

        self::RECEIVE_QUESTION_REWARD         =>'回答付费问题收入',

        self::REBATE                          =>'拼团提成',

        self::SP_WITHDRAW                 =>'提现',

    ];
}
