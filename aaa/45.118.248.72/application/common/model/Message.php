<?php

namespace app\common\model;

use think\Model;

class Message extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'xy_message';

    // 默认主键
    protected $pk = 'id';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'addtime';
    protected $updateTime = false;

    public function getUrlAttr($value, $data)
    {
        switch ($value) {
            case 1:
                return url('index/rot_order/index', ['mid' => $data['id']]);
                break;

            default:
                return $data['url'];
                break;
        }
    }
}
