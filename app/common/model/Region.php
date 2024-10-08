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
use think\facade\Cache;
use app\common\library\helper;

/**
 * 地区模型
 * Class Region
 * @package app\common\model
 */
class Region extends BaseModel
{
    // 定义表名
    protected $name = 'region';

    // 定义主键
    protected $pk = 'id';

    protected $createTime = false;
    protected $updateTime = false;

    /**
     * 类型自动转换
     * @var array
     */
    protected $type = [
        'id' => 'integer',
        'pid' => 'integer',
        'level' => 'integer',
    ];

    // 当前数据版本号
    private static string $version = '1.0.3';

    /**
     * 根据ID获取地区名称
     * @param int $id
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getNameById(int $id = 0)
    {
        if ($id == 0) {
            return '其他';
        }
        $data = self::getCacheAll();
        return isset($data[$id]) ? $data[$id]['name'] : '其他';
    }

    /**
     * 根据名称获取地区ID
     * @param string $name
     * @param int $level
     * @param int $pid
     * @return int|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getIdByName(string $name, int $level = 0, int $pid = 0)
    {
        $data = self::getCacheAll();
        foreach ($data as $item) {
            if ($item['name'] == $name && $item['level'] == $level && $item['pid'] == $pid) {
                return $item['id'];
            }
        }
        return 0;
    }

    /**
     * 获取所有地区(树状结构)
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getCacheTree(): array
    {
        return static::getCacheData('tree');
    }

    /**
     * 获取所有地区列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getCacheAll(): array
    {
        return static::getCacheData('all');
    }

    /**
     * 获取所有地区的总数
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getCacheCounts(): array
    {
        return static::getCacheData('counts');
    }

    /**
     * 获取缓存中的数据(存入静态变量)
     * @param null $item
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private static function getCacheData($item = null)
    {
        static $cacheData = [];
        if (empty($cacheData)) {
            $static = new static;
            $cacheData = $static->regionCache();
        }
        if (is_null($item)) {
            return $cacheData;
        }
        return $cacheData[$item];
    }

    /**
     * 获取地区缓存
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function regionCache(): array
    {
        // 缓存的数据
        $complete = Cache::get('region');
        // 如果存在缓存则返回缓存的数据，否则从数据库中查询
        // 条件1: 获取缓存数据
        // 条件2: 数据版本号要与当前一致
        if (
            !empty($complete)
            && isset($complete['version'])
            && $complete['version'] == self::$version
        ) {
            return $complete;
        }
        // 所有地区
        $allList = $tempList = $this->getAllList();
        // 已完成的数据
        $complete = [
            'all' => $allList,
            'tree' => $this->getTreeList($allList),
            'counts' => $this->getCount($allList),
            'version' => self::$version,
        ];
        // 写入缓存
        Cache::tag('cache')->set('region', $complete);
        return $complete;
    }

    /**
     * 地区总数
     * @param $allList
     * @return array
     */
    private static function getCount($allList): array
    {
        $counts = [
            'total' => count($allList),
            'province' => 0,
            'city' => 0,
            'region' => 0,
        ];
        $level = [1 => 'province', 2 => 'city', 3 => 'region'];
        foreach ($allList as $item) {
            $counts[$level[$item['level']]]++;
        }
        return $counts;
    }

    /**
     * 格式化为树状格式
     * @param $allList
     * @return array
     */
    private function getTreeList($allList): array
    {
        $treeList = [];
        foreach ($allList as $pKey => $province) {
            if ($province['level'] == 1) {    // 省份
                $treeList[$province['id']] = $province;
                unset($allList[$pKey]);
                foreach ($allList as $cKey => $city) {
                    if ($city['level'] == 2 && $city['pid'] == $province['id']) {    // 城市
                        $treeList[$province['id']]['city'][$city['id']] = $city;
                        unset($allList[$cKey]);
                        foreach ($allList as $rKey => $region) {
                            if ($region['level'] == 3 && $region['pid'] == $city['id']) {    // 地区
                                $treeList[$province['id']]['city'][$city['id']]['region'][$region['id']] = $region;
                                unset($allList[$rKey]);
                            }
                        }
                    }
                }
            }
        }
        return $treeList;
    }

    /**
     * 从数据库中获取所有地区
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getAllList(): array
    {
        $list = self::withoutGlobalScope()
            ->field('id, pid, name, level')
            ->select()
            ->toArray();
        return helper::arrayColumn2Key($list, 'id');
    }
}
