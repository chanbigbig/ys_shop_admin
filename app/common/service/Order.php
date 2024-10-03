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

namespace app\common\service;

use app\common\model\User as UserModel;
use app\common\model\Order as OrderModel;
use app\common\model\UserCoupon as UserCouponModel;
use app\common\enum\order\PayStatus as PayStatusEnum;
use app\common\enum\order\OrderStatus as OrderStatusEnum;
use app\common\service\goods\source\Factory as FactoryStock;

/**
 * 订单服务类
 * Class Order
 * @package app\common\service
 */
class Order extends BaseService
{
    /**
     * 生成订单号
     * @return string
     */
    public static function createOrderNo(): string
    {
        return date('Ymd') . substr(implode('', array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    /**
     * 事件：订单取消
     * @param OrderModel $order
     */
    public static function cancelEvent(OrderModel $order)
    {
        // 回退商品库存
        FactoryStock::getFactory($order['order_source'])->backGoodsStock($order['goods'], true);
        // 回退用户优惠券
        $order['coupon_id'] > 0 && UserCouponModel::setIsUse($order['coupon_id'], false);
        // 回退用户积分
        if ($order['points_num'] > 0) {
            $describe = "订单取消：{$order['order_no']}";
            UserModel::setIncPoints($order['user_id'], $order['points_num'], $describe, $order['store_id']);
        }
    }

    /**
     * 获取指定用户的有效订单数量
     * @param int $userId
     * @return int
     */
    public static function getValidCountByUser(int $userId): int
    {
        $model = new OrderModel;
        return $model->where('user_id', '=', $userId)
            ->where('pay_status', '=', PayStatusEnum::SUCCESS)
            ->where('order_status', '<>', OrderStatusEnum::CANCELLED)
            ->where('is_delete', '=', 0)
            ->count();
    }
}