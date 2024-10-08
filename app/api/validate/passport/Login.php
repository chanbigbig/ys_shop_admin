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

namespace app\api\validate\passport;

use think\Validate;

/**
 * 验证类：发送短信验证码
 * Class Login
 * @package app\api\validate\passport
 */
class Login extends Validate
{
    /**
     * 验证规则
     * @var array
     */
    protected $rule = [
        // 短信验证码 (用户输入)
        'smsCode' => ['require'],
        // 用户手机号
        'mobile' => ['require'],
    ];

    /**
     * 验证提示
     * @var string[]
     */
    protected $message  =   [
        'smsCode.require' => '短信验证码不能为空',
        'mobile.require' => '手机号不能为空',
    ];
}