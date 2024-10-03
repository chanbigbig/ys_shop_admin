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

namespace app\api\controller;

use app\api\model\Region as RegionModel;
use think\response\Json;

/**
 * 地区管理
 * Class Region
 * @package app\api\controller
 */
class Region extends Controller
{
    /**
     * 获取所有地区
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function all(): Json
    {
        $list = RegionModel::getCacheAll();
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 获取所有地区(树状)
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function tree(): Json
    {
        $list = RegionModel::getCacheTree();
        return $this->renderSuccess(compact('list'));
    }
}
