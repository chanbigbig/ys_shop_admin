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

namespace app\common\enum\goods;

use app\common\enum\EnumBasics;

/**
 * 枚举类：商品规格
 * Class SpecType
 * @package app\common\enum\goods
 */
class SpecType extends EnumBasics
{
    // 单规格
    const SINGLE = 10;

    // 多规格
    const MULTI = 20;

    /**
     * 获取枚举类型值
     * @return array
     */
    public static function data(): array
    {
        return [
            self::SINGLE => [
                'name' => '单规格',
                'value' => self::SINGLE,
            ],
            self::MULTI => [
                'name' => '多规格',
                'value' => self::MULTI,
            ]
        ];
    }
}
