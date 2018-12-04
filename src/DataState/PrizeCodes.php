<?php

namespace MoCommon\DataState;

/**
 * 奖品相关状态.
 *
 * @farwish
 */
class PrizeCodes extends \MoCommon\STBase
{
    const DATA_EXIST = 3001;
    const DATA_FINISH = 3002;
    const DATA_FULL = 3003;
    const DATA_NOT_OWN = 3004;
    const DATA_NOT_OPEN = 3005;
    const DATA_NO_PRIZE = 3006;
    const DATA_ACCEPT_PRIZE = 3007;

    protected static $val = [
        self::DATA_EXIST => '你已经参与过该抽奖了',
        self::DATA_FINISH => '该抽奖已经结束了',
        self::DATA_FULL => '该队满员了,再重新组队吧',
        self::DATA_NOT_OWN => '该抽奖不是你发起的,不能看中奖者地址信息',
        self::DATA_NOT_OPEN => '还未开奖',
        self::DATA_NO_PRIZE => '你没中奖,不能领取',
        self::DATA_ACCEPT_PRIZE => '你已经领过了',
    ];
}
