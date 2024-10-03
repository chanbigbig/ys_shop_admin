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

use think\model\relation\BelongsTo;
use app\common\model\CommentImage as CommentImageModel;

/**
 * 商品图片模型
 * Class GoodsImage
 * @package app\api\model
 */
class CommentImage extends CommentImageModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'store_id',
        'create_time',
    ];

    /**
     * 关联文件库
     * @return BelongsTo
     */
    public function file(): BelongsTo
    {
        return parent::file()->bind(['image_url' => 'preview_url']);
    }
}
