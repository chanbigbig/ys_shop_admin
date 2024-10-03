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
use app\common\library\helper;
use app\common\enum\coupon\ExpireType as ExpireTypeEnum;

/**
 * 优惠券模型
 * Class Coupon
 * @package app\common\model
 */
class Coupon extends BaseModel
{
    // 定义表名
    protected $name = 'coupon';

    // 定义主键
    protected $pk = 'coupon_id';

    /**
     * 追加字段
     * @var array
     */
    protected $append = ['state'];

    /**
     * 优惠券状态 (是否可领取)
     * @param $value
     * @param array $data
     * @return array
     */
    public function getStateAttr($value, array $data): array
    {
        if (isset($data['is_receive']) && $data['is_receive']) {
            return ['text' => '已领取', 'value' => 0];
        }
        if ($data['total_num'] > -1 && $data['receive_num'] >= $data['total_num']) {
            return ['text' => '已抢光', 'value' => 0];
        }
        if ($data['expire_type'] == ExpireTypeEnum::FIXED_TIME && ($data['end_time'] + 86400) < time()) {
            return ['text' => '已过期', 'value' => 0];
        }
        return ['text' => '正常', 'value' => 1];
    }

    /**
     * 获取器：格式化折扣率
     * @param $value
     * @return float|int
     */
    public function getDiscountAttr($value)
    {
        return $value / 10;
    }

    /**
     * 获取器：开始时间
     * @param $value
     * @return string
     */
    public function getStartTimeAttr($value): ?string
    {
        return $value ? date('Y/m/d', $value) : null;
    }

    /**
     * 获取器：结束时间
     * @param $value
     * @return string
     */
    public function getEndTimeAttr($value): ?string
    {
        return $value ? date('Y/m/d', $value) : null;
    }

    /**
     * 获取器：适用范围配置
     * @param $value
     * @return mixed
     */
    public function getApplyRangeConfigAttr($value)
    {
        return $value ? helper::jsonDecode($value) : [];
    }

    /**
     * 修改器：格式化折扣率
     * @param $value
     * @return float|int
     */
    public function setDiscountAttr($value)
    {
        return $value * 10;
    }

    /**
     * 修改器：适用范围配置
     * @param $array
     * @return mixed
     */
    public function setApplyRangeConfigAttr($array)
    {
        return helper::jsonEncode($array);
    }

    /**
     * 优惠券详情
     * @param int $couponId 优惠券ID
     * @return static|array|null
     */
    public static function detail(int $couponId)
    {
        return self::get($couponId);
    }

    /**
     * 验证优惠券状态是否可领取
     * @param $couponInfo
     * @return bool
     */
    public function checkReceive($couponInfo): bool
    {
        if (empty($couponInfo)) {
            $this->error = '很抱歉，当前优惠券不存在';
            return false;
        }
        if ($couponInfo['total_num'] > -1 && $couponInfo['receive_num'] >= $couponInfo['total_num']) {
            $this->error = '很抱歉，当前优惠券已发完';
            return false;
        }
        if ($couponInfo['expire_type'] == ExpireTypeEnum::FIXED_TIME && ($couponInfo->getData('end_time') + 86400) < time()) {
            $this->error = '很抱歉，当前优惠券已过期';
            return false;
        }
        return true;
    }

    /**
     * 累计优惠券已领取的数量
     * @param int $couponId
     * @return mixed
     */
    public static function setIncReceiveNum(int $couponId)
    {
        return (new static)->setInc($couponId, 'receive_num', 1);
    }
}
