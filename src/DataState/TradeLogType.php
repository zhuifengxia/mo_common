<?php

namespace YkCommon\DataState;

/**
 * appname_zhaoyisheng_trade_log.
 *
 */
class TradeLogType extends \YkCommon\STBase
{
    const NORMAL            = 0;
    const ASK_QUESTION      = 1;
    const REWARD_QUESTION   = 2;
    const REWARD_REPLY      = 3;
    const ONE_TO_ONE        = 4;
    const MK_ENROLL         = 5;
    const MK_SEE_VIDEO      = 6;
    const EXCHANGE_GOODS    = 7;
    const DOWNLOAD_DOCUMENT = 8;
    const INVITE_RENZHENG_REWARD = 9;


    //做个检查小程序

    const ASK_DOCTOR =1;
    const PAY_VIDEO =2;
    const PAY_VIDEO_CATEGORY =3;
    const PAY_CHECK =4;
    const WITHDRAW =5;
    const PAY_VIP = 6;
    const REBATE = 7;
	const CREATE_PT = 8;
    const JOIN_PT = 9;

    protected static $val = [
        self::NORMAL            => '普通订单',
        self::ASK_QUESTION      => '咨询医生问题',
        self::REWARD_QUESTION   => '赞赏帖子',
        self::REWARD_REPLY      => '赞赏回答',
        self::ONE_TO_ONE        => '一对一付费问',
        self::MK_ENROLL        => '慕课报名',
        self::MK_SEE_VIDEO        => '慕课视频查看',
        self::EXCHANGE_GOODS        => '兑换商品',
        self::DOWNLOAD_DOCUMENT     =>'下载文档',
        self::INVITE_RENZHENG_REWARD     =>'邀请认证奖励',
        self::ASK_DOCTOR =>'咨询',
        self::PAY_VIDEO =>'健康课程视频',
        self::PAY_VIDEO_CATEGORY =>'健康课程专栏',
        self::PAY_CHECK =>'预约定金',
        self::WITHDRAW =>'提现',
        self::PAY_VIP =>'年费会员',
        self::REBATE =>'拼团返利',
        self::PAY_CHECK =>'预约定金',
        self::WITHDRAW =>'提现',
		self::CREATE_PT =>'发起拼团',
        self::JOIN_PT =>'加入拼团',
    ];
}
