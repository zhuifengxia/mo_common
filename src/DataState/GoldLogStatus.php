<?php

namespace YkCommon\DataState;

/**
 * gold_log.
 *
 * kb变动记录的类型.
 *
 */
class GoldLogStatus extends \YkCommon\STBase
{
    const SHARE     = 1; //分享
    const ANSWER    = 2;  //回答
    const REPLY     = 3;
    const FIRST_REGIST      = 4;//注册
    const TODAY_FIRST_LOGIN = 5; //每日登陆
    const SEND              = 6; // 发问题, 提问奖
    const PLATFORM_AWARD    = 7;  //问题采纳，获得悬赏 以及别人的k币悬赏
    const SEND_QUESTION     = 8; // 发问题, 悬赏
    const EXCHANGE  = 9;  //兑换k币
    const ACCEPTED  = 10; //问题被采纳平台奖励
    const INVITE = 11;  //邀请
    const INVITE_RENZHENG = 12; //邀请认证
    const PUNISH = 13;  //处罚
    const DELETE_QUESTION = 14; //删除问题
    const JINGPING_QUESTION = 15;  //加精品
    const SPECIAL = 16; //特殊奖励
    const DELETE_REPLY =17; //删除评论
    const PAY_REWARD    = 18;  //k币赞赏别人
    const RECEIVE_PAY_REWARD =23;//收到别人赞赏
    const PAY_ANSWER    = 19; //查看公开付费病例答案
    const DAILY_GAME_REWARD    = 20;  //每日答题奖励
    const PAY_TO_SEE   = 21; //付费欣赏问题
    const PAY_TO_SEE_REWARD = 22; //付费问题被查看
    const RENZHENGWITHINVITE = 24; //邀请的认证
    const PAY_TO_SEE_RETURN = 25; //付费病例退还
    const APPEND = 26;//补充随访或者病例
    const APPEND_PUNISH = 27;//取消补充或病理结果奖励
    const ADD_DOCUMENT = 28;//上传资料奖励
    const DOWNLOAD_DOCUMENT = 29; //下载资料扣除K币
    const RECEIVE_DOWNLOAD_DOCUMENT = 32; //收到下载资料奖励K币
    //收到查看付费病例医生回答提成
    const RECEIVE_SEE_ONE_TO_ONE_ANSWER_REWARD = 30;
    //查看付费病例答案
    const SEE_ONE_TO_ONE_ANSWER = 31;
    const RENZHENG = 33; //认证
    const ADD_DICOM = 34; //添加DICOM影像
    const FIRST_SEND_QUESTION = 35;  //第一次提问
    const FIRST_REPLY = 36;  //第一次回答
    const DOCTOR_RENZHENG = 37; //签约医生奖励


    protected static $val = [
        self::SHARE     => '恭喜您！您分享了问题！',
        self::ANSWER    => '恭喜您！您回答问题！',
        self::REPLY     => '恭喜您！您回复了！',
        self::FIRST_REGIST      => '恭喜您！首次注册登陆！',
        self::TODAY_FIRST_LOGIN => '恭喜您！当日首次登陆！',
        self::SEND              => '恭喜您！发问题！',
        self::PLATFORM_AWARD    => '恭喜您！悬赏奖励！',
        self::SEND_QUESTION     => '恭喜您！发问题！',
        self::EXCHANGE  => '恭喜您！兑换！',
        self::ACCEPTED  => '恭喜您！您的回答已被采纳！',
        self::INVITE  => '恭喜您！成功邀请用户注册！',
        self::INVITE_RENZHENG  => '恭喜您！成功邀请用户注册并认证！',
        self::PUNISH => '管理员操作，有疑问请咨询！',
        self::DELETE_QUESTION => '帖子被删除！',
        self::JINGPING_QUESTION => '上榜精品！',
        self::SPECIAL => '特殊奖励！',
        self::DELETE_REPLY => '评论被删除！',
        self::PAY_REWARD =>'k币赞赏',
        self::RECEIVE_PAY_REWARD =>'收到k币赞赏',
        self::PAY_ANSWER => '查看答案',
        self::DAILY_GAME_REWARD =>'每日答题奖励',
        self::PAY_TO_SEE =>'付费阅读问题',
        self::PAY_TO_SEE_REWARD =>'付费阅读问题被查看',
        self::RENZHENG =>'恭喜您！成为认证用户',
        self::RENZHENGWITHINVITE =>'恭喜您！被邀请成为认证用户',
        self::PAY_TO_SEE_RETURN =>'付费病例活动K币退还',
        self::APPEND =>'补充确诊或者病理结果奖励',
        self::APPEND_PUNISH =>'补充确诊或者病理结果奖励取消并惩罚',
        self::ADD_DOCUMENT =>'上传资料奖励',
        self::DOWNLOAD_DOCUMENT =>'下载资料扣除K币',
        self::RECEIVE_DOWNLOAD_DOCUMENT=>'资料被下载奖励',
        self::RECEIVE_SEE_ONE_TO_ONE_ANSWER_REWARD =>'收到查看付费病例的医生回答提成',
        self::SEE_ONE_TO_ONE_ANSWER =>'查看一对一付费答案',
        self::ADD_DICOM =>'添加DICOM影像',
        self::FIRST_SEND_QUESTION =>'首次提问奖励',
        self::FIRST_REPLY =>'首次回答奖励',
        self::DOCTOR_RENZHENG =>'成为签约医生奖励'

	];
}
