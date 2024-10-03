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
use think\model\relation\BelongsTo;
use app\common\enum\file\Storage as StorageEnum;
use app\common\enum\file\FileType as FileTypeEnum;

/**
 * 文件库模型
 * Class UploadFile
 * @package app\common\model
 */
class UploadFile extends BaseModel
{
    // 定义表名
    protected $name = 'upload_file';

    // 定义主键
    protected $pk = 'file_id';

    // 追加的字段
    protected $append = [
        'preview_url',     // 图片预览url
        'external_url'     // 文件外链url (用于视频文件)
    ];

    /**
     * 关联模型：文件库分组
     * @return BelongsTo
     */
    public function uploadGroup(): BelongsTo
    {
        return $this->belongsTo('UploadGroup', 'group_id');
    }

    /**
     * 生成预览url (preview_url)
     * @param $value
     * @param $data
     * @return string
     */
    public function getPreviewUrlAttr($value, $data): string
    {
        // 图片的预览图直接使用外链
        if ($data['file_type'] == FileTypeEnum::IMAGE) {
            return $this->getExternalUrlAttr($value, $data);
        }
        // 生成默认的预览图
        $previewPath = FileTypeEnum::data()[$data['file_type']]['preview_path'];
        return base_url() . $previewPath;
    }

    /**
     * 生成外链url (external_url)
     * @param $value
     * @param $data
     * @return string
     */
    public function getExternalUrlAttr($value, $data): string
    {
        // 存储方式本地：拼接当前域名
        if ($data['storage'] === StorageEnum::LOCAL) {
            $data['domain'] = rtrim(uploads_url(), '/');
        }
        return "{$data['domain']}/{$data['file_path']}";
    }

    /**
     * 文件详情
     * @param int $fileId
     * @return static|array|null
     */
    public static function detail(int $fileId)
    {
        return self::get($fileId);
    }

    /**
     * 过滤不存在的文件ID集
     * @param array $fileIds
     * @param int|null $storeId
     * @return array
     */
    public static function filteFileIds(array $fileIds, int $storeId = null): array
    {
        return (new static)->where('file_id', 'in', $fileIds)
            ->where('store_id', '=', $storeId ?: self::$storeId)
            ->where('is_delete', '=', 0)
            ->column('file_id');
    }
}
