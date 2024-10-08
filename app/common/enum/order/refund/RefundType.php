<?php
// +----------------------------------------------------------------------
// | 萤火商城系统 [ 致力于通过产品和服务，帮助商家高效化开拓市场 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2024 https://www.yiovo.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed 这不是一个自由软件，不允许对程序代码以任何形式任何目的的再发行
// +----------------------------------------------------------------------
// | Author: 萤火科技 <admin@yiovo.com>
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace app\common\enum\order\refund;

use app\common\enum\EnumBasics;

/**
 * 枚举类：售后类型
 * Class RefundType
 * @package app\common\enum\order\refund
 */
class RefundType extends EnumBasics
{
    // 退货退款
    const RETURN = 10;

    // 换货
    const EXCHANGE = 20;

    /**
     * 获取枚举数据
     * @return array
     */
    public static function data(): array
    {
        return [
            self::RETURN => [
                'name' => '退货退款',
                'value' => self::RETURN
            ],
            self::EXCHANGE => [
                'name' => '换货',
                'value' => self::EXCHANGE
            ]
        ];
    }
}
