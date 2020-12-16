<?php

namespace app\common\model;

use think\Model;

class Config extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'system_config';

    // 默认主键
    protected $pk = 'id';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = false;

}
