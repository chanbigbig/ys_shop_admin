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

namespace app\timer\controller;

use app\timer\service\UserGrade as UserGradeService;

/**
 * 定时任务：会员等级
 * Class UserGrade
 * @package app\timer\controller
 */
class UserGrade extends Controller
{
    // 当前任务唯一标识
    private string $taskKey = 'UserGrade';

    // 任务执行间隔时长 (单位:秒)
    protected int $taskExpire = 60 * 30;

    // 当前商城ID
    private int $storeId;

    /**
     * 任务处理
     * @param array $param
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function handle(array $param)
    {
        ['storeId' => $this->storeId] = $param;
        $this->setInterval($this->storeId, $this->taskKey, $this->taskExpire, function () {
            echo $this->taskKey . PHP_EOL;
            // 设置用户的会员等级
            $this->setUserGrade();
        });
    }

    /**
     * 设置用户的会员等级
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function setUserGrade()
    {
        $service = new UserGradeService;
        $service->setUserGrade($this->storeId);
    }
}