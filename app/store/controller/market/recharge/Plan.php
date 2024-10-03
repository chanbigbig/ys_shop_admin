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

namespace app\store\controller\market\recharge;

use think\response\Json;
use app\store\controller\Controller;
use app\store\model\recharge\Plan as PlanModel;

/**
 * 充值套餐管理
 * Class Coupon
 * @package app\store\controller\market
 */
class Plan extends Controller
{
    /**
     * 充值套餐列表
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function list(): Json
    {
        $model = new PlanModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 添加充值套餐
     * @return Json
     */
    public function add(): Json
    {
        // 新增记录
        $model = new PlanModel;
        if ($model->add($this->postForm())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($this->model->getError() ?: '添加失败');
    }

    /**
     * 更新充值套餐
     * @param int $planId
     * @return Json
     */
    public function edit(int $planId): Json
    {
        // 充值套餐详情
        $model = PlanModel::detail($planId);
        // 更新记录
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除充值套餐
     * @param int $planId
     * @return Json
     */
    public function delete(int $planId): Json
    {
        // 套餐详情
        $model = PlanModel::detail($planId);
        // 删除记录
        if ($model->setDelete()) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError($model->getError() ?: '删除失败');
    }
}
