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
 * 商家记录表模型
 * Class Store
 * @package app\common\model
 */
class Store extends BaseModel
{
    // 定义表名
    protected $name = 'store';

    // 定义主键
    protected $pk = 'store_id';

    /**
     * 关联logo图片
     * @return HasOne
     */
    public function logoImage(): HasOne
    {
        return $this->hasOne('UploadFile', 'file_id', 'logo_image_id');
    }

    /**
     * 详情信息
     * @param int $storeId
     * @return static|array|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail(int $storeId)
    {
        return self::withoutGlobalScope()
            ->with(['logoImage'])
            ->where('store_id', '=', $storeId)
            ->find();
    }

    /**
     * 获取列表数据
     * @param bool $isRecycle 是否在回收站
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList(bool $isRecycle = false): \think\Paginator
    {
        return $this->where('is_recycle', '=', (int)$isRecycle)
            ->where('is_delete', '=', 0)
            ->order(['create_time' => 'desc', $this->getPk()])
            ->paginate(15);
    }
}
