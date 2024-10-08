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

namespace app\common\enum\setting\sms;

use app\common\enum\EnumBasics;

/**
 * 枚举类: 短信通知场景
 * Class Scene
 * @package app\common\enum\setting\sms
 */
class Scene extends EnumBasics
{
    // 短信验证码
    const CAPTCHA = 'captcha';

    // 新付款订单
    const ORDER_PAY = 'order_pay';

    /**
     * 获取类型值
     * @return array
     */
    public static function data(): array
    {
        return [
            self::CAPTCHA => [
                'name' => '短信验证码',
                'value' => self::CAPTCHA
            ],
            self::ORDER_PAY => [
                'name' => '新付款订单',
                'value' => self::ORDER_PAY
            ]
        ];
    }
}
