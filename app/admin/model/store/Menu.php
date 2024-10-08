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

use app\common\model\store\Menu as MenuModel;
use app\admin\model\store\MenuApi as MenuApiModel;

/**
 * 商家后台菜单模型
 * Class Menu
 * @package app\admin\model\store
 */
class Menu extends MenuModel
{
    /**
     * 获取菜单列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(): array
    {
        return $this->getTreeData(static::getAll());
    }

    /**
     * 新增记录
     * @param array $data
     * @return bool
     */
    public function add(array $data): bool
    {
        return $this->save($data);
    }

    /**
     * 更新记录
     * @param array $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit(array $data): bool
    {
        // 判断上级角色是否为当前子级
        if (isset($data['parent_id']) && $data['parent_id'] > 0) {
            // 获取所有上级id集
            $parentIds = $this->getTopMenuIds($data['parent_id']);
            if (in_array($this['menu_id'], $parentIds)) {
                $this->error = '上级菜单不允许设置为当前子菜单';
                return false;
            }
        }
        return $this->save($data);
    }

    /**
     * 设置菜单的API权限
     * @param array $data
     * @return bool
     */
    public function setApis(array $data): bool
    {
        if (empty($data['apiIds'])) {
            $this->error = 'API权限不能为空';
            return false;
        }
        // 根据菜单id批量更新API关联记录
        return (new MenuApiModel)->updateByMenuId($this['menu_id'], $data['apiIds']);
    }

    /**
     * 删除菜单
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \Exception
     */
    public function remove(): bool
    {
        // 判断是否存在下级菜单
        if (self::detail(['parent_id' => $this['menu_id']])) {
            $this->error = '当前菜单下存在子菜单或操作，请先删除';
            return false;
        }
        // 清空菜单与API关联关系
        MenuApiModel::deleteAll(['menu_id' => $this['menu_id']]);
        // 删除API记录
        return $this->delete();
    }

    /**
     * 获取所有上级id集
     * @param int $menuId
     * @param null $menuList
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getTopMenuIds(int $menuId, $menuList = null): array
    {
        static $ids = [];
        is_null($menuList) && $menuList = $this->getAll();
        foreach ($menuList as $item) {
            if ($item['menu_id'] == $menuId && $item['parent_id'] > 0) {
                $ids[] = $item['parent_id'];
                $this->getTopMenuIds($item['parent_id'], $menuList);
            }
        }
        return $ids;
    }
}
