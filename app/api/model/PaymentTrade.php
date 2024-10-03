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

namespace app\api\model;

use app\common\model\PaymentTrade as PaymentTradeModel;
use app\common\enum\payment\trade\TradeStatus as TradeStatusEnum;

/**
 * 模型类：第三方支付交易记录
 * Class PaymentTrade
 * @package app\api\model
 */
class PaymentTrade extends PaymentTradeModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'store_id',
        'create_time',
        'update_time'
    ];

    /**
     * 新增第三方交易信息
     * @param mixed $orderInfo 订单信息
     * @param string $method 支付方式
     * @param string $client 指定的客户端
     * @param int $orderType 订单类型
     * @param array $payment 第三方支付数据
     * @return bool
     * @throws \cores\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function record($orderInfo, string $method, string $client, int $orderType, array $payment): bool
    {
        // 实例化模型
        $model = new static;
        // 查询是否存在交易记录
        $record = $model->detailByOrderId($orderInfo['order_id'], $method, $client, $orderType);
        // 新增或者更新记录
        return ($record ?: $model)->save([
            'client' => $client,
            'pay_method' => $method,
            'order_type' => $orderType,
            'order_id' => $orderInfo['order_id'],
            'order_no' => $orderInfo['order_no'],
            'user_id' => $orderInfo['user_id'],
            'out_trade_no' => $payment['out_trade_no'] ?? '',
            'prepay_id' => $payment['prepay_id'] ?? '',
            'trade_status' => TradeStatusEnum::NOTPAY,
            'store_id' => self::$storeId,
        ]);
    }
}
