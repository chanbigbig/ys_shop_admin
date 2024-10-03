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

namespace app\api\service\order\source\checkout;

use app\api\service\Order as OrderService;
use app\common\enum\goods\Status as GoodsStatusEnum;
use app\common\enum\order\OrderSource as OrderSourceEnum;

/**
 * 订单结算台-普通商品扩展类
 * Class Main
 * @package app\api\service\order\source\checkout
 */
class Main extends Basics
{
    /**
     * 验证商品列表
     * @return bool
     */
    public function validateGoodsList(): bool
    {
        // 验证商品是否下架
        if (!$this->validateGoodsStatus()) {
            return false;
        }
        // 判断商品库存
        if (!$this->validateGoodsSeckillStock()) {
            return false;
        }
        // 验证商品限购
        if (!$this->validateLimitNum()) {
            return false;
        }
        return true;
    }

    /**
     * 判断商品是否下架
     * @return bool
     */
    private function validateGoodsStatus(): bool
    {
        foreach ($this->goodsList as $goods) {
            if ($goods['is_delete'] || $goods['status'] == GoodsStatusEnum::OFF_SALE) {
                $this->error = "很抱歉，商品 [{$goods['goods_name']}] 已下架";
                return false;
            }
        }
        return true;
    }

    /**
     * 判断商品库存数量
     * @return bool
     */
    private function validateGoodsSeckillStock(): bool
    {
        foreach ($this->goodsList as $goods) {
            if ($goods['total_num'] > $goods['skuInfo']['stock_num']) {
                $this->error = "很抱歉，商品 [{$goods['goods_name']}] 库存不足";
                return false;
            }
        }
        return true;
    }

    /**
     * 验证商品限购
     * @return bool
     */
    public function validateLimitNum(): bool
    {
        foreach ($this->goodsList as $goods) {
            // 不限购
            if (!$goods['is_restrict']) return true;
            // 商品单次限购数量
            if ($goods['total_num'] > $goods['restrict_single']) {
                $this->error = "很抱歉，商品 [{$goods['goods_name']}] 限购{$goods['restrict_single']}件，请修改购买数量";
                return false;
            }
            // 获取用户已下单的件数（未取消 订单来源）
            $alreadyBuyNum = OrderService::getGoodsBuyNum($this->user['user_id'], $goods['goods_id'], OrderSourceEnum::MAIN);
            // 情况1: 已购买0件, 实际想购买5件
            if ($alreadyBuyNum == 0 && $goods['total_num'] > $goods['restrict_total']) {
                $this->error = "很抱歉，商品 [{$goods['goods_name']}] 限购{$goods['restrict_total']}件，请修改购买数量";
                return false;
            }
            // 情况2: 已购买3件, 实际想购买1件
            if ($alreadyBuyNum >= $goods['restrict_total']) {
                $this->error = "很抱歉，商品 [{$goods['goods_name']}] 限购{$goods['restrict_total']}件，您已购买过{$alreadyBuyNum}件";
                return false;
            }
            // 情况3: 已购买2件, 实际想购买2件
            if (($alreadyBuyNum + $goods['total_num']) > $goods['restrict_total']) {
                $diffNum = ($alreadyBuyNum + $goods['total_num']) - $goods['restrict_total'];
                $this->error = "很抱歉，商品 [{$goods['goods_name']}] 限购{$goods['restrict_total']}件，您最多能再购买{$diffNum}件";
                return false;
            }
        }
        return true;
    }
}