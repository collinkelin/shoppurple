<?php

namespace app\common\model;

use think\Model;
use util\Time;

class TaskRecord extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'xy_task_record';

    // 默认主键
    protected $pk = 'id';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    // protected $createTime = 'addtime';
    // protected $updateTime = false;

    protected function setCreateUidAttr($value)
    {
        if (empty($value)) {
            $value = session('user_id');
        }
        return $value;
    }

    protected function setCreateDayAttr($value)
    {
        if (empty($value)) {
            $time  = Time::today();
            $value = $time[0];
        }
        return $value;
    }

    // protected function setCreateIpAttr($value)
    // {
    //     return request()->ip();
    // }
}
