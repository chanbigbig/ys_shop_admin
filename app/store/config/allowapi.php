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

// 商户后台api白名单配置
// 此处定义的api所有账户均有权访问
// Auth类: app\store\service\Auth.php
return [
    // 用户登录
    '/passport/login',
    // 退出登录
    '/passport/logout',

    // 当前商城信息
    '/store/info',
    // 当前用户信息
    '/store.user/info',
    // 修改当前用户信息
    '/store.user/renew',

    // 文件库列表
    '/files/list',
    // 删除文件
    '/files/delete',
    // 移动文件
    '/files/moveGroup',
    // 文件分组列表
    '/files.group/list',
    // 新增文件分组
    '/files.group/add',
    // 上传图片文件
    '/upload/image',
    // 上传视频文件
    '/upload/video',

    // 获取所有地区
    '/region/all',
    // 获取所有地区(树状格式)
    '/region/tree',

    // 腾讯地图API
    '/map/transfer',
];