<?php

namespace YkCommon\Business;

/**
 * 业务类状态码.
 *
 * @farwish
 */
class CasualActivity extends \YkCommon\STBase
{
    const RATE_SOFT = 1;
    const ZUIMEI_DOCTOR = 2;
    const KEPU = 3;
    const ZILIAOQIANG = 4;
    const ROBOT_READ = 5;
    const WEIYUN_VOTE = 6;

    protected static $val = [
        self::RATE_SOFT  => '影像云峰会评测',
        self::ZUIMEI_DOCTOR =>'最美医生评选',
        self::KEPU =>'科普代言评选',
        self::ZILIAOQIANG =>'资料墙评选',
        self::ROBOT_READ =>'智能阅片',
        self::WEIYUN_VOTE=>'微云投票'
    ];
}
