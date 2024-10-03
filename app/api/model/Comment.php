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

namespace app\api\model;

use app\api\model\Order as OrderModel;
use app\common\model\Comment as CommentModel;
use app\api\service\User as UserService;
use app\common\library\helper;
use cores\exception\BaseException;

/**
 * 商品评价模型
 * Class Comment
 * @package app\api\model
 */
class Comment extends CommentModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'user_id',
        'status',
        'sort',
        'order_id',
        'goods_id',
        'order_goods_id',
        'store_id',
        'is_delete',
        'update_time'
    ];

    /**
     * 获取指定商品评价列表
     * @param int $goodsId 商品ID
     * @param int|null $scoreType 评分 (10好评 20中评 30差评)
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getCommentList(int $goodsId, int $scoreType = null): \think\Paginator
    {
        // 获取评价列表记录
        $filter = $this->getFilter($goodsId, $scoreType);
        return $this->with(['user.avatar', 'orderGoods', 'images.file'])
            ->where($filter)
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->paginate(15);
    }

    /**
     * 获取指定商品评价列表 (限制数量, 不分页)
     * @param int $goodsId 商品ID
     * @param int $limit 限制的数量
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function listRows(int $goodsId, int $limit = 5): \think\Collection
    {
        $filter = $this->getFilter($goodsId);
        return $this->with(['user.avatar'])
            ->where($filter)
            ->order(['sort' => 'asc', $this->getPk()])
            ->limit($limit)
            ->select();
    }

    /**
     * 获取指定商品评价总数量
     * @param int $goodsId
     * @return int
     */
    public function rowsTotal(int $goodsId): int
    {
        $filter = $this->getFilter($goodsId);
        return $this->where($filter)->count();
    }

    /**
     * 获取查询条件
     * @param int $goodsId 商品ID
     * @param int|null $scoreType 评分 (10好评 20中评 30差评)
     * @return array[]
     */
    private function getFilter(int $goodsId, int $scoreType = null): array
    {
        // 筛选条件
        $filter = [
            ['goods_id', '=', $goodsId],
            ['status', '=', 1],
            ['is_delete', '=', 0],
        ];
        // 评分
        $scoreType > 0 && $filter[] = ['score', '=', $scoreType];
        return $filter;
    }

    /**
     * 获取指定评分总数
     * @param int $goodsId
     * @return array|null|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getTotal(int $goodsId)
    {
        return $this->field([
            'count(comment_id) AS `all`',
            'count(score = 10 OR NULL) AS `praise`',
            'count(score = 20 OR NULL) AS `review`',
            'count(score = 30 OR NULL) AS `negative`',
        ])->where([
            'goods_id' => $goodsId,
            'is_delete' => 0,
            'status' => 1
        ])->find();
    }

    /**
     * 验证订单是否允许评价
     * @param OrderModel $order
     * @return boolean
     */
    public function checkOrderAllowComment(OrderModel $order): bool
    {
        // 验证订单是否已完成
        if ($order['order_status'] != 30) {
            $this->error = '该订单未完成，无法评价';
            return false;
        }
        // 验证订单是否已评价
        if ($order['is_comment'] == 1) {
            $this->error = '该订单已完成评价';
            return false;
        }
        return true;
    }

    /**
     * 根据已完成订单商品 添加评价
     * @param OrderModel $order
     * @param $goodsList
     * @param array $data
     * @return boolean
     * @throws BaseException
     */
    public function increased(OrderModel $order, $goodsList, array $data): bool
    {
        // 生成 formData
        $formData = $this->formatFormData($data);
        // 生成评价数据
        $data = $this->createCommentData($order['order_id'], $goodsList, $formData);
        if (empty($data)) {
            $this->error = '没有输入评价内容';
            return false;
        }
        return $this->transaction(function () use ($order, $goodsList, $formData, $data) {
            // 记录评价内容
            $result = $this->addAll($data);
            // 记录评价图片`
            $this->saveAllImages($result, $formData);
            // 更新订单评价状态
            $isComment = count($goodsList) === count($data);
            $this->updateOrderIsComment($order, $isComment, $result);
            return true;
        });
    }

    /**
     * 更新订单评价状态
     * @param OrderModel $order
     * @param $isComment
     * @param $commentList
     * @return void
     */
    private function updateOrderIsComment(OrderModel $order, $isComment, $commentList): void
    {
        // 更新订单商品
        $orderGoodsData = [];
        foreach ($commentList as $comment) {
            $orderGoodsData[] = [
                'where' => [
                    'order_goods_id' => $comment['order_goods_id'],
                ],
                'data' => [
                    'is_comment' => 1
                ]
            ];
        }
        // 更新订单
        $isComment && $order->save(['is_comment' => 1]);
        (new OrderGoods)->updateAll($orderGoodsData);
    }

    /**
     * 生成评价数据
     * @param int $orderId
     * @param $goodsList
     * @param array $formData
     * @return array
     * @throws BaseException
     */
    private function createCommentData(int $orderId, $goodsList, array $formData): array
    {
        $data = [];
        foreach ($goodsList as $goods) {
            if (!isset($formData[$goods['order_goods_id']])) {
                throwError('提交的数据不合法');
            }
            $commentItem = $formData[$goods['order_goods_id']];
            $commentItem['content'] = trim($commentItem['content']);
            !empty($commentItem['content']) && $data[$goods['order_goods_id']] = [
                'score' => $commentItem['score'],
                'content' => $commentItem['content'],
                'is_picture' => !empty($commentItem['uploaded']),
                'sort' => 100,
                'status' => 1,
                'user_id' => UserService::getCurrentLoginUserId(),
                'order_id' => $orderId,
                'goods_id' => $commentItem['goods_id'],
                'order_goods_id' => $commentItem['order_goods_id'],
                'store_id' => self::$storeId
            ];
        }
        return $data;
    }

    /**
     * 格式化 formData
     * @param array $data
     * @return array
     */
    private function formatFormData(array $data): array
    {
        return helper::arrayColumn2Key($data, 'order_goods_id');
    }

    /**
     * 记录评价图片
     * @param $commentList
     * @param $formData
     * @return void
     */
    private function saveAllImages($commentList, $formData): void
    {
        // 生成评价图片数据
        $imageData = [];
        foreach ($commentList as $comment) {
            $item = $formData[$comment['order_goods_id']];
            foreach ($item['uploaded'] as $imageId) {
                $imageData[] = [
                    'comment_id' => $comment['comment_id'],
                    'image_id' => $imageId,
                    'store_id' => self::$storeId
                ];
            }
        }
        $model = new CommentImage;
        !empty($imageData) && $model->addAll($imageData) !== false;
    }
}
