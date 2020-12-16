<?php

namespace app\common\model;

use think\Model;

class UserLevel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'xy_user_level';
    // 默认主键
    protected $pk = 'id';

    public function setExtendedAttr($value, $data)
    {
        return json_encode($value);
    }

    public function getExtendedAttr($value, $data)
    {
        $d                    = json_decode($value, true);
        $d['_commission_min'] = ($d['commission_min'] * 100) . '%';
        $d['_commission_max'] = ($d['commission_max'] * 100) . '%';
        $d['_match_min']      = ($d['match_min'] * 100) . '%';
        $d['_match_max']      = ($d['match_max'] * 100) . '%';
        return $d;
    }
}
