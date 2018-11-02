<?php

namespace YkCommon\DataState;

/**
 * appname_zhaoyisheng_trade_log.
 *
 */
class TradeLogStatus extends \YkCommon\STBase
{
    const WAIT_TO_PAY  = 0;
    const HAS_PAID     = 1;
    const FAIL     = 2;
    const REFUNDING     = 3;
    const REFUNDED     = 4;

    protected static $val = [
        self::WAIT_TO_PAY  => '未支付',
        self::HAS_PAID     => '已支付',
        self::FAIL         => '支付失败',
        self::REFUNDING    => '退款中',
        self::REFUNDED     => '退款成功',
    ];
}
