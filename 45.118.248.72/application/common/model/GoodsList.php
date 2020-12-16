<?php

namespace app\common\model;

use think\Model;

class GoodsList extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'xy_goods_list';

    // 默认主键
    protected $pk = 'id';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'addtime';
    // protected $updateTime = false;

    /**
     * 添加商品
     *
     * @param string $shop_name
     * @param string $goods_name
     * @param string $goods_price
     * @param string $goods_pic
     * @param string $goods_info
     * @param string $id 传参则更新数据,不传则写入数据
     * @return array
     */
    public function submit_goods($shop_name, $goods_name, $goods_price, $goods_pic, $goods_info, $cid, $id = '')
    {
        if (!$goods_pic) {
            return ['code' => 1, 'info' => ('请上传商品图片')];
        }

        if (!$goods_name) {
            return ['code' => 1, 'info' => ('请输入商品名称')];
        }

        if (!$shop_name) {
            return ['code' => 1, 'info' => ('请输入店铺名称')];
        }

        if (!$goods_price) {
            return ['code' => 1, 'info' => ('请填写正确的商品价格')];
        }

        $data = [
            'shop_name'   => $shop_name,
            'goods_name'  => $goods_name,
            'goods_price' => $goods_price,
            'goods_pic'   => $goods_pic,
            'goods_info'  => $goods_info,
            'cid'         => $cid,
        ];
        if (!$id) {
            $res = self::create($data);
        } else {
            $res = self::where('id', $id)->update($data);
        }
        if ($res) {
            return ['code' => 0, 'info' => '操作成功!'];
        } else {
            return ['code' => 1, 'info' => '操作失败!'];
        }
    }
}
