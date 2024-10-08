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

namespace app\common\model\user;

use cores\BaseModel;
use app\common\library\helper;

/**
 * 用户会员等级模型
 * Class Grade
 * @package app\common\model\user
 */
class Grade extends BaseModel
{
    // 定义表名
    protected $name = 'user_grade';

    // 定义主键
    protected $pk = 'grade_id';

    /**
     * 获取器：升级条件
     * @param $json
     * @return mixed
     */
    public function getUpgradeAttr($json)
    {
        return helper::jsonDecode($json);
    }

    /**
     * 获取器：等级权益
     * @param $json
     * @return mixed
     */
    public function getEquityAttr($json)
    {
        return helper::jsonDecode($json);
    }

    /**
     * 修改器：升级条件
     * @param $data
     * @return mixed
     */
    public function setUpgradeAttr($data)
    {
        return helper::jsonEncode($data);
    }

    /**
     * 修改器：等级权益
     * @param $data
     * @return mixed
     */
    public function setEquityAttr($data)
    {
        return helper::jsonEncode($data);
    }

    /**
     * 会员等级详情
     * @param int $gradId
     * @param array $with
     * @return static|array|null
     */
    public static function detail(int $gradId, array $with = [])
    {
        return static::get($gradId, $with);
    }

    /**
     * 验证等级权重是否存在
     * @param int $weight 验证的权重
     * @param int $gradeId 自身的等级ID
     * @return bool
     */
    public static function checkExistByWeight(int $weight, int $gradeId = 0): bool
    {
        $filter = [];
        $gradeId > 0 && $filter[] = ['grade_id', '<>', (int)$gradeId];
        return !!(new static)->where('weight', '=', (int)$weight)
            ->where($filter)
            ->where('is_delete', '=', 0)
            ->value('grade_id');
    }
}
