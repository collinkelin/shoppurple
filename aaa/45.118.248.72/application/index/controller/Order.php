<?php

namespace app\index\controller;

use app\common\model\Convey as ConveyModel;
use think\Controller;
use think\Db;
use think\Request;

/**
 * 订单列表
 */
class Order extends Base
{
    /**
     * 获取订单列表
     */
    public function order_list()
    {
        $page = input('post.page/d', 1);
        $num  = input('post.num/d', 10);
        $type = input('post.type/d', 1);
        switch ($type) {
            // 获取待处理订单
            case 1:
                $type = 0;
                break;
            // 获取冻结中订单
            case 2:
                $type = 5;
                break;
            // 获取已完成订单
            case 3:
                $type = 1;
                break;
        }
        $data = db('xy_convey')
            ->where('xc.uid', session('user_id'))
            ->where('xc.status', $type)
            ->alias('xc')
            ->leftJoin('xy_goods_list xg', 'xc.goods_id=xg.id')
            ->field('xc.*,xg.goods_name,xg.shop_name,xg.goods_price,xg.goods_pic')
            ->order('xc.addtime desc')
            ->limit((($page - 1) * $num), $num)
            ->select();
        foreach ($data as $key => $value) {
            $data[$key]['infourl'] = url('order/order_info', ['id' => $value['id']]);
        }
        if (!$data) {
            return json(['code' => 1, 'info' => lang('No data')]);
        }
        return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $data]);
    }

    /**
     * 获取单笔订单详情
     */
    public function order_info($id = '')
    {
        if (empty($id)) {
            $id = input('id', '');
        }
        if (empty($id)) {
            return view('index');
        }
        $oinfo = db('xy_convey')
            ->alias('xc')
            ->leftJoin('xy_member_address ar', 'ar.uid=xc.uid', 'ar.is_default=1')
            ->leftJoin('xy_goods_list xg', 'xg.id=xc.goods_id')
            ->leftJoin('xy_users u', 'u.id=xc.uid')
            ->field('xc.id oid,xc.*,xg.goods_name,xg.goods_price,xg.shop_name,xg.goods_pic,ar.name,ar.tel,ar.address,u.balance')
            ->where('xc.id', $id)
            ->where('xc.uid', $this->user['id'])
            ->find();
        $map = [
            ['uid', '=', $this->user['id']],
            ['status', '=', 1],
            ['special', '>', 0],
        ];
        $count = Db::name('xy_convey')
            ->where($map)
            ->count();
        $match = [
            'cancel' => 0,
        ];
        if ($this->user['task'] >= 1 && $this->user['special'] > 0) {
            $match = get_convey_match($count + 1);
        }
        return view('', ['info' => $oinfo, 'match' => $match]);
    }

    /**
     * 处理订单
     */
    public function do_order()
    {
        if (!empty(input('oid')) && !empty(input('add_id'))) {
            $oid    = input('oid/s', '');
            $status = input('status/d', 1);
            $add_id = input('add_id/d', 0);
            if (!\in_array($status, [1, 2])) {
                return json([
                    'code' => 1,
                    'info' => lang('Parameter error'),
                    'url'  => url('index'),
                ]);
            }
            $res = (new ConveyModel)->do_order($oid, 0, $status, session('user_id'), $add_id);
            return json($res);
        }
        return json(['code' => 1, 'info' => lang('Error failed')]);
    }

    /**
     * 验证提现密码
     */
    public function check_pwd2()
    {
        if (!request()->isPost()) {
            return json(['code' => 1, 'info' => lang('Error failed')]);
        }

        $pwd2 = input('post.pwd2/s', '');
        $info = db('xy_users')
            ->field('pwd2,salt2')
            ->find(session('user_id'));
        if ($info['pwd2'] == '') {
            return json(['code' => 1, 'info' => lang('No transaction password set'), 'url' => url('ctrl/edit_deposit_pwd')]);
        }

        if ($info['pwd2'] != sha1($pwd2 . $info['salt2'] . config('pwd_str'))) {
            return json(['code' => 1, 'info' => lang('wrong password')]);
        }

        return json(['code' => 0, 'info' => lang('Verified')]);
    }
}
