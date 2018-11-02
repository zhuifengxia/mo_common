<?php

namespace YkCommon\DataState;

/**
 * appname_logs.
 *
 */
class LogsType extends \YkCommon\STBase
{
    const SIMPLE    = 0;
    const WECHAT    = 1;
    const PAYMENT   = 2;
    const TRADE     = 3;

    protected static $val = [
        self::SIMPLE    => '普通业务信息',
        self::WECHAT    => '微信返回信息',
        self::PAYMENT   => '金额/支付信息',
        self::TRADE     => '订单相关信息',
    ];
}
