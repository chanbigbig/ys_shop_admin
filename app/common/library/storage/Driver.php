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

namespace app\common\library\storage;

use think\Exception;
use app\common\library\storage\engine\Aliyun;
use app\common\library\storage\engine\Local;
use app\common\library\storage\engine\Qcloud;
use app\common\library\storage\engine\Qiniu;
use app\common\enum\file\Storage as StorageEnum;

/**
 * 存储模块驱动
 * Class driver
 * @package app\common\library\storage
 */
class Driver
{
    // upload 配置
    private array $config;

    // 当前存储引擎类
    private $engine;

    /**
     * 存储引擎类列表
     */
    const ENGINE_CLASS_LIST = [
        StorageEnum::LOCAL => Local::class,
        StorageEnum::QINIU => QINIU::class,
        StorageEnum::ALIYUN => Aliyun::class,
        StorageEnum::QCLOUD => Qcloud::class
    ];

    /**
     * 构造方法
     * Driver constructor.
     * @param array $config
     * @param string|null $storage 指定存储方式，如不指定则为系统默认
     * @throws Exception
     */
    public function __construct(array $config, string $storage = null)
    {
        // upload 配置
        $this->config = $config;
        // 实例化当前存储引擎
        $this->engine = $this->getEngineClass($storage);
    }

    /**
     * 设置上传的文件信息
     * @param string $name
     * @return mixed
     */
    public function setUploadFile(string $name = 'iFile')
    {
        return $this->engine->setUploadFile($name);
    }

    /**
     * 设置上传的文件信息
     * @param string $filePath
     * @return mixed
     */
    public function setUploadFileByReal(string $filePath)
    {
        return $this->engine->setUploadFileByReal($filePath);
    }

    /**
     * 设置上传的文件信息
     * @param string $name
     * @return mixed
     */
    public function setRootName(string $name = '')
    {
        return $this->engine->setRootName($name);
    }

    /**
     * 设置上传文件的验证规则
     * @param array $rules
     * @return mixed
     */
    public function setValidationScene(array $rules = [])
    {
        return $this->engine->setValidationScene($rules);
    }

    /**
     * 执行文件上传
     */
    public function upload()
    {
        return $this->engine->upload();
    }

    /**
     * 执行文件删除
     * @param string $filePath
     * @return mixed
     */
    public function delete(string $filePath)
    {
        return $this->engine->delete($filePath);
    }

    /**
     * 获取错误信息
     * @return mixed
     */
    public function getError()
    {
        return $this->engine->getError();
    }

    /**
     * 返回保存的文件信息
     * @return array
     */
    public function getSaveFileInfo(): array
    {
        return $this->engine->getSaveFileInfo();
    }

    /**
     * 获取当前的存储引擎
     * @param string|null $storage 指定存储方式，如不指定则为系统默认
     * @return mixed
     * @throws Exception
     */
    private function getEngineClass(string $storage = null)
    {
        $storage = is_null($storage) ? $this->config['default'] : $storage;
        if (!isset(self::ENGINE_CLASS_LIST[$storage])) {
            throw new Exception("未找到存储引擎类: {$storage}");
        }
        $class = self::ENGINE_CLASS_LIST[$storage];
        return new $class($storage, $this->config['engine'][$storage]);
    }
}
