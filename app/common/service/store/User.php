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

namespace app\common\service\store;

use think\facade\Cache;
use app\common\service\BaseService;

/**
 * 商家用户服务类
 * Class User
 */
class User extends BaseService
{
    // 用于生成token的自定义盐
    const TOKEN_SALT = '_store_user_salt_';

    /**
     * 获取当前登录用户的信息
     * @return array
     */
    public static function getLoginInfo(): array
    {
        if (($token = self::getToken()) !== false) {
            return Cache::get($token) ?: [];
        }
        return [];
    }

    /**
     * 获取当前登录用户的ID
     * @return int|null
     */
    public static function getLoginUserId(): ?int
    {
        $userInfo = static::getLoginInfo();
        if (empty($userInfo)) {
            return null;
        }
        return (int)$userInfo['user']['store_user_id'];
    }

    /**
     * 获取当前登录的商城ID
     * @return int|null
     */
    public static function getLoginStoreId(): ?int
    {
        $userInfo = static::getLoginInfo();
        if (empty($userInfo)) {
            return null;
        }
        return (int)$userInfo['store_id'];
    }

    /**
     * 记录登录信息
     * @param array $userInfo
     * @return string
     */
    public static function login(array $userInfo): string
    {
        // 生成token
        $token = self::makeToken((int)$userInfo['store_user_id']);
        // 记录缓存, 7天
        Cache::set($token, [
            'user' => $userInfo,
            'store_id' => $userInfo['store_id'],
            'is_login' => true,
        ], 86400 * 7);
        return $token;
    }

    /**
     * 清空登录状态
     * @return bool
     */
    public static function logout(): bool
    {
        $token = self::getToken();
        !empty($token) && Cache::delete($token);
        return true;
    }

    /**
     * 更新登录信息
     * @param array $userInfo
     * @return bool
     */
    public static function update(array $userInfo): bool
    {
        return Cache::set(self::getToken(), [
            'user' => $userInfo,
            'store_id' => $userInfo['store_id'],
            'is_login' => true,
        ], 86400 * 7);
    }

    /**
     * 生成用户认证的token
     * @param int $userId
     * @return string
     */
    protected static function makeToken(int $userId): string
    {
        // 生成一个不会重复的随机字符串
        $guid = get_guid_v4();
        // 当前时间戳 (精确到毫秒)
        $timeStamp = microtime(true);
        // 自定义一个盐
        $salt = self::TOKEN_SALT;
        return md5("{$timeStamp}_{$userId}_{$guid}_{$salt}");
    }

    /**
     * 获取用户认证Token
     * @return bool|string
     */
    private static function getToken()
    {
        // 获取请求中的token
        $token = request()->header('Access-Token');
        // 调试模式下可通过param
        if (empty($token) && is_debug()) {
            $token = request()->param('Access-Token');
        }
        // 不存在token报错
        if (empty($token)) {
            return false;
        }
        return $token;
    }
}
