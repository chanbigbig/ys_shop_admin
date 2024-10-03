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

namespace app\common\enum;

/**
 * 枚举类：商品的优惠方式
 * Class OrderType
 * @package app\common\enum
 */
class DiscountType extends EnumBasics
{
    // 优惠券
    const COUPON = 'coupon';

    // 积分抵扣
    const POINTS = 'points';

    // 会员等级折扣
    const GRADE = 'grade';

    /**
     * 获取全部类型
     * @return array
     */
    public static function data(): array
    {
        return [
            self::COUPON => [
                'name' => '优惠券',
                'value' => self::COUPON,
            ],
            self::POINTS => [
                'name' => '积分抵扣',
                'value' => self::POINTS,
            ],
            self::GRADE => [
                'name' => '会员等级折扣',
                'value' => self::GRADE,
            ],
        ];
    }
}