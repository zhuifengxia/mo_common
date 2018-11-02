<?php

namespace YkCommon\DataState;

/**
 * appname_zhaoyisheng_trade_log.
 *
 */
class TradeLogPayType extends \YkCommon\STBase
{
    const WXPAY     = 1;
    const KBPAY     = 2;

    protected static $val = [
        self::WXPAY     => '微信支付',
        self::KBPAY     => 'KB支付',
    ];
}
