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

namespace app\common\service\order;

use app\common\library\helper;
use app\common\model\User as UserModel;
use app\common\model\Order as OrderModel;
use app\common\model\store\Setting as SettingModel;
use app\common\model\user\PointsLog as PointsLogModel;
use app\common\enum\Setting as SettingEnum;
use app\common\enum\order\refund\RefundType as RefundTypeEnum;
use app\common\enum\order\refund\AuditStatus as AuditStatusEnum;
use app\common\service\BaseService;

/**
 * 已完成订单结算服务类
 * Class Complete
 * @package app\common\service\order
 */
class Complete extends BaseService
{
    // 订单模型
    /* @var OrderModel $model */
    private OrderModel $model;

    // 用户模型
    /* @var UserModel $model */
    private UserModel $UserModel;

    /**
     * 构造方法
     * Complete constructor.
     */
    public function initialize()
    {
        $this->model = new OrderModel;
        $this->UserModel = new UserModel;
    }

    /**
     * 执行订单完成后的操作
     * @param iterable $orderList
     * @param int $storeId
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function complete(iterable $orderList, int $storeId): bool
    {
        // 已完成订单结算
        // 条件：后台订单流程设置 - 已完成订单设置0天不允许申请售后
        if (SettingModel::getItem(SettingEnum::TRADE, $storeId)['order']['refund_days'] == 0) {
            $this->settled($orderList);
        }
        return true;
    }

    /**
     * 执行订单结算
     * @param $orderList
     * @return bool
     */
    public function settled($orderList): bool
    {
        // 订单id集
        $orderIds = helper::getArrayColumn($orderList, 'order_id');
        // 累积用户实际消费金额
        $this->setIncUserExpend($orderList);
        // 处理订单赠送的积分
        $this->setGiftPointsBonus($orderList);
        // 将订单设置为已结算
        $this->model->onBatchUpdate($orderIds, ['is_settled' => 1, 'settled_time' => \time()]);
        return true;
    }

    /**
     * 处理订单赠送的积分
     * @param $orderList
     * @return void
     */
    private function setGiftPointsBonus($orderList): void
    {
        // 计算用户所得积分
        $userData = [];
        $logData = [];
        foreach ($orderList as $order) {
            // 计算用户所得积分
            $pointsBonus = $order['points_bonus'];
            if ($pointsBonus <= 0) continue;
            // 减去订单退款的积分
            foreach ($order['goods'] as $goods) {
                if (
                    !empty($goods['refund'])
                    && $goods['refund']['type'] == RefundTypeEnum::RETURN      // 售后类型：退货退款
                    && $goods['refund']['audit_status'] == AuditStatusEnum::REVIEWED  // 商家审核：已同意
                ) {
                    $pointsBonus -= $goods['points_bonus'];
                }
            }
            // 计算用户所得积分
            !isset($userData[$order['user_id']]) && $userData[$order['user_id']] = 0;
            $userData[$order['user_id']] += $pointsBonus;
            // 整理用户积分变动明细
            $logData[] = [
                'user_id' => $order['user_id'],
                'value' => $pointsBonus,
                'describe' => "订单赠送：{$order['order_no']}",
                'store_id' => $order['store_id'],
            ];
        }
        if (!empty($userData)) {
            // 累积到会员表记录
            $this->UserModel->onBatchIncPoints($userData);
            // 批量新增积分明细记录
            (new PointsLogModel)->onBatchAdd($logData);
        }
    }

    /**
     * 累积用户实际消费金额
     * @param $orderList
     * @return void
     */
    private function setIncUserExpend($orderList): void
    {
        // 计算并累积实际消费金额(需减去售后退款的金额)
        $userData = [];
        foreach ($orderList as $order) {
            // 订单实际支付金额
            $expendMoney = $order['pay_price'];
            // 减去订单退款的金额
            foreach ($order['goods'] as $goods) {
                if (
                    !empty($goods['refund'])
                    && $goods['refund']['type'] == RefundTypeEnum::RETURN      // 售后类型：退货退款
                    && $goods['refund']['audit_status'] == AuditStatusEnum::REVIEWED  // 商家审核：已同意
                ) {
                    $expendMoney = helper::bcsub($expendMoney, $goods['refund']['refund_money']);
                }
            }
            !isset($userData[$order['user_id']]) && $userData[$order['user_id']] = 0.00;
            if ($expendMoney > 0) {
                $userData[$order['user_id']] = helper::bcadd($userData[$order['user_id']], $expendMoney);
            }
        }
        // 累积到会员表记录
        $this->UserModel->onBatchIncExpendMoney($userData);
    }
}
