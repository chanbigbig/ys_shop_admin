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

namespace app\admin\model\store;

use app\common\model\store\MenuApi as MenuApiModel;

/**
 * 商家后台用户角色与菜单权限关系表模型
 * Class MenuApi
 * @package app\admin\model\store
 */
class MenuApi extends MenuApiModel
{
    /**
     * 根据菜单id批量更新记录
     * @param int $menuId
     * @param array $apiIds
     * @return bool
     */
    public function updateByMenuId(int $menuId, array $apiIds): bool
    {
        return $this->transaction(function () use ($menuId, $apiIds) {
            $this->removeByMenuId($menuId);
            return $this->insertByMenuId($menuId, $apiIds);
        });
    }

    /**
     * 根据菜单id批量删除记录
     * @param int $menuId
     * @return void
     */
    private function removeByMenuId(int $menuId): void
    {
        static::deleteAll(['menu_id' => $menuId]);
    }

    /**
     * 根据菜单id批量新增记录
     * @param int $menuId
     * @param array $apiIds
     * @return bool
     */
    private function insertByMenuId(int $menuId, array $apiIds): bool
    {
        $data = [];
        foreach ($apiIds as $api) {
            $data[] = ['menu_id' => $menuId, 'api_id' => $api];
        }
        return (bool)$this->addAll($data);
    }
}
