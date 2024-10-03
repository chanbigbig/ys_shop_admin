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

namespace app\common\model\store;

use cores\BaseModel;
use think\model\relation\BelongsTo;
use think\model\relation\BelongsToMany;

/**
 * 商家用户模型
 * Class User
 * @package app\common\model
 */
class User extends BaseModel
{
    // 定义表名
    protected $name = 'store_user';

    // 定义主键
    protected $pk = 'store_user_id';

    /**
     * 关联商家记录表
     * @return BelongsTo
     */
    public function store(): BelongsTo
    {
        $module = self::getCalledModule();
        return $this->belongsTo("app\\{$module}\\model\\Store", 'store_id');
    }

    /**
     * 关联用户角色表表
     * @return BelongsToMany
     */
    public function role(): BelongsToMany
    {
        return $this->belongsToMany('Role', 'StoreUserRole');
    }

    /**
     * 验证用户名是否重复
     * @param string $userName
     * @return bool
     */
    public static function checkExist(string $userName): bool
    {
        return (bool)static::withoutGlobalScope()
            ->where('user_name', '=', $userName)
            ->where('is_delete', '=', 0)
            ->value('store_user_id');
    }

    /**
     * 商家用户详情
     * @param $where
     * @param array $with
     * @return static|array|null
     */
    public static function detail($where, array $with = [])
    {
        return static::get($where, $with);
    }
}
