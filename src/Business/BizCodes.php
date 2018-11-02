<?php

namespace YkCommon\Business;

/**
 * 业务类状态码.
 *
 * @farwish
 */
class BizCodes extends \YkCommon\STBase
{
    const SIMPLE_WORDS   = 2001;
    const BAD_WORDS      = 2002;
    const MUST_SUBSCRIBE = 2003;
    const REPATE_VOTE    = 2004;
    const REPATE_LIKE    = 2005;
    const MUST_RENZHENG  = 2006;

    const OUT_CONTROL    = 3001;
    const YOU_ARE_BLACK  = 3002;

    const HAS_ACCEPTED          = 4001;
    const REPLY_CANT_ACCEPTED   = 4002;
    const QUESTION_OVERTIME     = 4003;
    const CANT_ACCEPT_SELF      = 4004;
    const REPLY_REPEAT          = 4005;
    const PAY_ISSUE_HAS_FINISH  = 4006;
    const PAY_ISSUE_NOT_OPEN    = 4007;
    const QUESTION_ASK_OVER     = 4008;
    const QUESTION_OPEN_OTHER_CANT_BE_FIRST = 4009;
    const QUESTION_CAN_ASK_SELF = 4010;
    const QUESTION_MUST_RENZHENG = 4011;

    const KB_CONVERT_TIP            = 5001;
    const KB_CONVERT_MUST_IN_WECHAT = 5002;
    const KB_MUST_SUBSCRIBE         = 5003;
    const KB_NOT_ENOUGH             = 5004;
    const KB_MUST_RENZHENG          = 5005;

    const WITHDRAW_MUST_IN_WECHAT   = 6001;
    const WITHDRAW_MUST_SUBSCRIBE   = 6002;
    const WITHDRAW_MONEY_NOT_ENOUGH = 6003;

    const TRADE_GEN_FAIL        = 7001;
    const TRADE_SENDPAY_FAIL    = 7002;

    const VIDOO_MUST_SHARE      =8001;

    protected static $val = [
        self::SIMPLE_WORDS  => '内容过于简单',
        self::BAD_WORDS     => '内容含有敏感词汇',
        self::MUST_SUBSCRIBE => '请您先关注医看(kankanyish)微信公众号',
        self::REPATE_VOTE => '您已经投过票了',
        self::REPATE_LIKE => '您已经为TA加油过了',
        self::MUST_RENZHENG => '您还不是认证用户',

        self::OUT_CONTROL   => '越权操作',
        self::YOU_ARE_BLACK => '您已被拉黑，请联系管理员',

        self::HAS_ACCEPTED          => '该问题已解决',
        self::REPLY_CANT_ACCEPTED   => '不能对回复进行采纳',
        self::QUESTION_OVERTIME     => '超过3天的问题不能回复',
        self::CANT_ACCEPT_SELF      => '不能采纳自己的回答',
        self::REPLY_REPEAT          => '已经有相似回答了',
        self::PAY_ISSUE_HAS_FINISH  => '该付费问题已完成',
        self::PAY_ISSUE_NOT_OPEN    => '该付费问题未公开',
        self::QUESTION_ASK_OVER     => '该付费问题已超过追问次数',
        self::QUESTION_OPEN_OTHER_CANT_BE_FIRST => '您不能第一个回复该付费问题',
        self::QUESTION_CAN_ASK_SELF => '不能向自己付费提问',
        self::QUESTION_MUST_RENZHENG     => '为了更好地规范［医看］平台专业度，从即日起仅对认证用户开发提问功能。请您通过认证后，再发布提问。',

        self::KB_CONVERT_TIP            => 'k币兑换时间为周三周四',
        self::KB_CONVERT_MUST_IN_WECHAT => '请在微信中兑换k币',
        self::KB_MUST_SUBSCRIBE         => '请您先关注医看(kankanyish)微信公众号',
        self::KB_NOT_ENOUGH             => 'k币不足',
        self::KB_MUST_RENZHENG          => '您不是认证用户，认证请联系小看（微信号：xiaokankan2016)',

        self::WITHDRAW_MUST_IN_WECHAT     => '请在微信中提现',
        self::WITHDRAW_MUST_SUBSCRIBE     => '请您先关注医看(kankanyish)微信公众号',
        self::WITHDRAW_MONEY_NOT_ENOUGH   => '余额不足',

        self::TRADE_GEN_FAIL        => 'YK订单生成失败',
        self::TRADE_SENDPAY_FAIL    => '发起支付失败',
        self::VIDOO_MUST_SHARE      =>'只有将视频分享到朋友圈以后才能观看'
    ];
}
