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

namespace app\store\controller\order;

use think\response\Json;
use app\store\controller\Controller;
use app\store\model\order\Delivery as DeliveryModel;
use app\store\service\order\EOrder as EOrderService;
use app\store\service\order\Delivery as DeliveryService;

/**
 * 订单发货管理
 * Class Delivery
 * @package app\store\controller\order
 */
class Delivery extends Controller
{
    /**
     * 订单发货记录
     * @return Json
     */
    public function list(): Json
    {
        $model = new DeliveryModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 发货单详情
     * @param int $deliveryId 发货单ID
     * @return Json
     */
    public function detail(int $deliveryId): Json
    {
        $detail = DeliveryModel::detail($deliveryId);
        return $this->renderSuccess(compact('detail'));
    }

    /**
     * 确认发货 (手动录入)
     * @param int $orderId 订单ID
     * @return Json
     */
    public function delivery(int $orderId): Json
    {
        $service = new DeliveryService;
        if ($service->delivery($orderId, $this->postForm())) {
            return $this->renderSuccess('发货成功');
        }
        return $this->renderError($service->getError() ?: '发货失败');
    }
}