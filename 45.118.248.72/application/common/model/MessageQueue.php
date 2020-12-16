<?php

namespace app\common\model;

use think\Model;

class MessageQueue extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'xy_message_queue';

    // 默认主键
    protected $pk = 'id';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    protected $auto   = [];
    protected $insert = ['create_uid', 'status' => 0];
    protected $update = [];

    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    protected function setCreateUidAttr($value)
    {
        if (empty($value)) {
            return session('user_id');
        }
    }
}
