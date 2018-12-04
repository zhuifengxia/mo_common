<?php

namespace MoCommon\DataState;

/**
 * 地址相关状态.
 *
 * @farwish
 */
class AddressCodes extends \MoCommon\STBase
{
    const DATA_EXIST = 2001;

    protected static $val = [
        self::DATA_EXIST => '收货地址已存在',
    ];
}
