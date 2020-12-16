<?php

namespace app\common\model;

use think\Model;

class Recharge extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'xy_recharge';

    // 默认主键
    protected $pk = 'id';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'addtime';
    protected $updateTime = 'endtime';

    public function getTransferNomineeAttr($value, $data)
    {
        return $data['nominee'] . ' ' . $data['real_name'];
    }

    public function getNumAttr($value, $data)
    {
        return (int) $data['num'];
    }
}
