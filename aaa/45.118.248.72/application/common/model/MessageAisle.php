<?php

namespace app\common\model;

use think\Model;

class MessageAisle extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'xy_message_aisle';

    // 默认主键
    protected $pk = 'id';

    // 开启自动写入时间戳字段
    // protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    // protected $createTime = 'addtime';
    // protected $updateTime = false;
}
