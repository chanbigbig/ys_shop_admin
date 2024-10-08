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

namespace app\common\model;

use cores\BaseModel;
use think\model\relation\HasOne;

/**
 * 商品分类模型
 * Class Category
 * @package app\common\model
 */
class Category extends BaseModel
{
    // 定义表名
    protected $name = 'category';

    // 定义主键
    protected $pk = 'category_id';

    /**
     * 分类图片
     * @return HasOne
     */
    public function image(): HasOne
    {
        return $this->hasOne('UploadFile', 'file_id', 'image_id');
    }

    /**
     * 分类详情
     * @param int|array $where
     * @param array $with
     * @return static|array|null
     */
    public static function detail($where, array $with = [])
    {
        return static::get($where, $with);
    }

    /**
     * 获取列表记录
     * @param array $param
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(array $param = []): array
    {
        $list = $this->getAll($param);
        return $this->getTreeData($list);
    }

    /**
     * 获取所有记录
     * @param array $param
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected function getAll(array $param = []): \think\Collection
    {
        // 默认查询参数
        $params = $this->setQueryDefaultValue($param, [
            'status' => -1  // 状态(1显示 0隐藏 -1全部)
        ]);
        // 设置检索条件
        $filter = [];
        $params['status'] > -1 && $filter[] = ['status', '=', $params['status']];
        // 查询列表数据
        return $this->with(['image'])
            ->where($filter)
            ->order(['sort', 'create_time'])
            ->select();
    }

    /**
     * 获取树状列表
     * @param $list
     * @param int $parentId
     * @return array
     */
    private function getTreeData($list, int $parentId = 0): array
    {
        $data = [];
        foreach ($list as $key => $item) {
            if ($item['parent_id'] == $parentId) {
                $children = $this->getTreeData($list, $item['category_id']);
                !empty($children) && $item['children'] = $children;
                $data[] = $item;
                unset($list[$key]);
            }
        }
        return $data;
    }

    /**
     * 过滤不存在的分类ID集
     * @param array $categoryIds
     * @param int|null $storeId
     * @return array
     */
    public static function filterCategoryIds(array $categoryIds, int $storeId = null): array
    {
        return (new static)->where('category_id', 'in', $categoryIds)
            ->where('store_id', '=', $storeId ?: self::$storeId)
            ->column('category_id');
    }
}
