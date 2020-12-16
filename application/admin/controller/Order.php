<?php

namespace app\admin\controller;

use app\common\model\Convey as ConveyModel;
use think\Controller;
use think\facade\Request;
use think\facade\View;

/**
 * 订单列表
 */
class Order extends Base
{

    public function initialize()
    {
        parent::initialize();
    }

    public function index()
    {
        $param = input('');
        if (Request::isPost()) {
            $page  = Request::post('page') ? Request::post('page') : 1;
            $limit = Request::post('limit') ? Request::post('limit') : $this->pageSize;
            $map   = [];
            if (isset($param['type']) && $param['type'] >= 0) {
                switch ($param['type']) {
                    case 0:
                        $map[] = ['xc.special', '=', $param['type']];
                        break;
                    default:
                        $map[] = ['xc.special', '>', 0];
                        break;
                }
            }
            if (isset($param['status']) && $param['status'] >= 0) {
                $map[] = ['xc.status', '=', $param['status']];
            }
            if (!empty($param['value'])) {
                switch ($param['valueKey']) {
                    case 'username':
                        $map[] = ['u.' . $param['valueKey'], 'like', '%' . $param['value'] . '%'];
                        break;
                    case 'id':
                    case 'uid':
                        $map[] = ['xc.' . $param['valueKey'], '=', $param['value']];
                        break;
                    default:
                        $map[] = ['xc.' . $param['valueKey'], '=', $param['value']];
                        break;
                }
            }
            if (!empty($param['startTime'])) {
                $start = strtotime($param['startTime'] . ' 00:00:00');
                $map[] = ['xc.addtime', '>', $start];
            }
            if (!empty($param['endTime'])) {
                $time = strtotime($param['endTime'] . ' 23:59:59');
                if (!empty($param['startTime']) && $start > $time) {
                    $this->error('请重新选择结束时间');
                }
                $map[] = ['xc.addtime', '<', $time];
            }
            $sort = 'id DESC';
            if (!empty($param['sort'])) {
                $sort = 'xc.' . $param['sortKey'] . ' ' . $param['sort'];
            }
            $param['map'] = $map;
            $status       = [0 => '<span style="color:#009688;">等待付款</span>', 1 => '<span style="color:#5FB878;">完成付款</span>', 2 => '<span style="color:#FFB800;">用户取消</span>', 3 => '<span style="color:#1E9FFF;">强制付款</span>', 4 => '<span style="color:#FF5722;">系统取消</span>', 5 => '<span style="color:#2F4056;">订单冻结</span>'];
            $list         = ConveyModel::alias('xc')
                ->leftJoin('xy_users u', 'u.id=xc.uid')
                ->leftJoin('xy_goods_list g', 'g.id=xc.goods_id')
                ->field('xc.*,u.username,g.goods_name,g.goods_price')
                ->where($map)
                ->order($sort)
                ->paginate(['list_rows' => $limit, 'page' => $page])
                ->toArray();
            foreach ($list['data'] as $key => $value) {
                $list['data'][$key]['status_text'] = $status[$value['status']];
            }
            return $result = [
                'code'  => 0,
                'msg'   => '获取成功!',
                'data'  => $list['data'],
                'count' => $list['total'],
                'where' => $param,
                'token' => [
                    'order'  => systoken('admin/order/order_confirm'),
                    'cancel' => systoken('admin/order/order_cancel'),
                ],
            ];
        }
        $view = [
            'where' => $param,
        ];
        View::assign($view);
        return View::fetch();
    }

    /**
     * [order_confirm 强制付款]
     * @return [type] [description]
     */
    public function order_confirm()
    {
        $this->applyCsrfToken();
        $oid = input('post.id/s', '');
        if (empty($oid)) {
            return $this->error('参数错误');
        }
        $res = (new ConveyModel)->do_order($oid, 1, 3);
        if ($res['code'] === 0) {
            return $this->success('操作成功');
        } else {
            return $this->error($res['info']);
        }
    }

    /**
     * [order_cancel 取消订单]
     * @return [type] [description]
     */
    public function order_cancel()
    {
        $this->applyCsrfToken();
        $oid = input('post.id/s', '');
        if (empty($oid)) {
            return $this->error('参数错误');
        }
        $res = (new ConveyModel)->cancel($oid, 4);
        if ($res['code'] === 0) {
            return $this->success('操作成功');
        } else {
            return $this->error($res['info']);
        }
    }
}
