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

namespace app\common\model\goods;

use cores\BaseModel;

/**
 * 商品服务与承诺模型
 * Class ServiceRel
 */
class ServiceRel extends BaseModel
{
    // 定义表名
    protected $name = 'goods_service_rel';

    // 定义主键
    protected $pk = 'id';

    protected $updateTime = false;

    /**
     * 获取商品服务与承诺ID集
     * @param int $goodsId
     * @return array
     */
    public static function getServiceIds(int $goodsId): array
    {
        return (new static)->where('goods_id', '=', $goodsId)->column('service_id');
    }

    /**
     * 批量写入商品服务记录
     * @param int $goodsId
     * @param array $serviceIds
     * @param int|null $storeId 商城ID
     * @return array|false
     */
    public static function increased(int $goodsId, array $serviceIds, int $storeId = null)
    {
        $dataset = [];
        foreach ($serviceIds as $serviceId) {
            $dataset[] = [
                'service_id' => $serviceId,
                'goods_id' => $goodsId,
                'store_id' => $storeId ?: self::$storeId
            ];
        }
        return (new static)->addAll($dataset);
    }
}
