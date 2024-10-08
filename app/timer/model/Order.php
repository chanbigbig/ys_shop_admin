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

namespace app\timer\model;

use app\common\model\Order as OrderModel;
use app\common\enum\order\PayStatus as PayStatusEnum;
use app\common\enum\order\OrderStatus as OrderStatusEnum;
use app\common\enum\order\OrderSource as OrderSourceEnum;
use app\common\enum\order\ReceiptStatus as ReceiptStatusEnum;
use app\common\enum\order\DeliveryStatus as DeliveryStatusEnum;

/**
 * 订单模型
 * Class Order
 * @package app\common\model
 */
class Order extends OrderModel
{
    /**
     * 获取订单列表
     * @param int $storeId 商城ID
     * @param array $filter
     * @param array $with
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(int $storeId, array $filter = [], array $with = []): \think\Collection
    {
        return $this->with($with)
            ->where($filter)
            ->where('store_id', '=', $storeId)
            ->where('is_delete', '=', 0)
            ->select();
    }

    /**
     * 获取订单ID集
     * @param int $storeId 商城ID
     * @param array $filter
     * @return array
     */
    public function getOrderIds(int $storeId, array $filter = []): array
    {
        return $this->where($filter)
            ->where('store_id', '=', $storeId)
            ->where('is_delete', '=', 0)
            ->column('order_id');
    }

    /**
     * 查询截止时间未支付的订单列表
     * @param int $storeId 商城ID
     * @param int $deadlineTime 截止日期的时间戳
     * @param int|null $orderSource 订单来源(默认为全部)
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getListByClose(int $storeId, int $deadlineTime, int $orderSource = null): \think\Collection
    {
        // 查询条件
        $filter = [
            ['pay_status', '=', PayStatusEnum::PENDING],
            ['order_status', '=', OrderStatusEnum::NORMAL],
            ['create_time', '<=', $deadlineTime],
        ];
        // 订单来源
        !empty($orderSource) && $filter[] = ['order_source', '=', $orderSource];
        // 查询列表记录
        return $this->getList($storeId, $filter, ['goods', 'user']);
    }

    /**
     * 查询截止时间已完成的订单列表
     * @param int $storeId 商城ID
     * @param array $orderIds 订单ID集
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getListByOrderIds(int $storeId, array $orderIds): \think\Collection
    {
        // 查询条件
        $filter = [['order_id', 'in', $orderIds]];
        // 查询列表记录
        return $this->getList($storeId, $filter, ['goods' => ['refund']]);
    }

    /**
     * 查询截止时间未确认收货的订单ID集
     * @param int $storeId 商城ID
     * @param int $deadlineTime 截止时间
     * @return array
     */
    public function getOrderIdsByReceive(int $storeId, int $deadlineTime): array
    {
        // 查询条件
        $filter = [
            ['pay_status', '=', PayStatusEnum::SUCCESS],
            ['delivery_status', '=', DeliveryStatusEnum::DELIVERED],
            ['receipt_status', '=', ReceiptStatusEnum::NOT_RECEIVED],
            ['delivery_time', '<=', $deadlineTime]
        ];
        // 查询列表记录
        return $this->getOrderIds($storeId, $filter);
    }

    /**
     * 查询截止时间确认收货的订单列表
     * @param int $storeId 商城ID
     * @param int $deadlineTime 截止时间
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOrderListBySettled(int $storeId, int $deadlineTime): \think\Collection
    {
        // 查询条件
        $filter = [
            ['order_status', '=', OrderStatusEnum::COMPLETED],
            ['receipt_time', '<=', $deadlineTime],
            ['is_settled', '=', 0]
        ];
        // 查询列表记录
        return $this->getList($storeId, $filter, ['goods.refund']);
    }

    /**
     * 批量更新订单状态为已收货
     * @param array $orderIds 订单ID集
     * @return false|int
     */
    public function onUpdateReceived(array $orderIds)
    {
        return $this->onBatchUpdate($orderIds, [
            'receipt_status' => ReceiptStatusEnum::RECEIVED,
            'receipt_time' => time(),
            'order_status' => OrderStatusEnum::COMPLETED
        ]);
    }
}
