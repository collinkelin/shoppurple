<?php

namespace app\admin\controller;

use app\common\model\Pay as PayModel;
use app\common\model\PayInfo as PayInfoModel;
use library\Controller;
use think\Db;
use think\facade\Request;
use think\facade\View;

/**
 * 支付管理
 * Class Users
 * @package app\admin\controller
 */
class PayInfo extends Base
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
            $list  = PayInfoModel::alias('pi')
                ->leftJoin('xy_pay p', 'pi.payid=p.id')
                ->field('pi.*,p.name pay_name')
                ->order('pi.id DESC')
                ->paginate(['list_rows' => $limit, 'page' => $page])
                ->toArray();
            return $result = [
                'code'  => 0,
                'msg'   => '获取成功!',
                'data'  => $list['data'],
                'count' => $list['total'],
                'where' => $param,
                'token' => [
                    'save'   => systoken('admin/pay_info/save'),
                    'delete' => systoken('admin/pay_info/delete'),
                ],
            ];
        }
        $pay = PayModel::where('recharge', 1)->order('status DESC,recharge DESC')->select();
        return view('', ['where' => $param, 'pay' => $pay]);
    }

    /**
     * [save 保存数据]
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function save()
    {
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $param = input('');
            if (empty($param['id'])) {
                $res = (new PayInfoModel)->allowField(true)->save($param);
            } else {
                $res = (new PayInfoModel)->allowField(true)->save($param, ['id' => $param['id']]);
            }
            if ($res) {
                $this->success('操作成功');
            } else {
                $this->error('操作失败');
            }
        }
    }

    /**
     * 删除
     * @auth true
     * @menu true
     */
    public function delete()
    {
        $this->applyCsrfToken();
        $id = input('post.id/d', 0);
        if (empty($id)) {
            return $this->error('参数错误');
        }
        $info = PayInfoModel::get($id);
        $res  = $info->delete();
        if ($res) {
            $this->success('删除成功!');
        } else {
            $this->error('删除失败!');
        }
    }

    // /**
    //  * 指定当前数据表
    //  * @var string
    //  */
    // protected $table = 'xy_pay_info';

    // public function initialize()
    // {
    //     $host = \think\facade\Request::host();
    //     if (config('site_admin_domain') && config('site_admin_domain') != $host) {
    //         $this->redirect('@index');
    //     }
    // }

    // /**
    //  * 支付信息
    //  * @auth true
    //  * @menu true
    //  * @throws \think\Exception
    //  * @throws \think\db\exception\DataNotFoundException
    //  * @throws \think\db\exception\ModelNotFoundException
    //  * @throws \think\exception\DbException
    //  * @throws \think\exception\PDOException
    //  */
    // public function index()
    // {
    //     $this->title = '支付信息';
    //     $query       = $this->_query($this->table)->alias('u');
    //     $where       = [];
    //     if (input('username/s', '')) {
    //         $where[] = ['u.username', 'like', '%' . input('username/s', '') . '%'];
    //     }
    //     if (input('addtime/s', '')) {
    //         $arr     = explode(' - ', input('addtime/s', ''));
    //         $where[] = ['u.addtime', 'between', [strtotime($arr[0]), strtotime($arr[1])]];
    //     }
    //     $query->field('u.*,p.ico,p.name pname')
    //         ->leftJoin('xy_pay p', 'u.payid=p.id')
    //         ->where($where)
    //         ->order('u.id asc')
    //         ->page();
    // }

    /**
     * 添加支付信息
     * @auth true
     * @menu true
     */
    public function add()
    {
        if (request()->isPost()) {
            $payid         = input('post.payid/d', 0);
            $bank_name     = input('post.bank_name/s', '');
            $branch_name   = input('post.branch_name/s', '');
            $branch_number = input('post.branch_number/s', '');
            $card_number   = input('post.card_number/s', '');
            $name_e        = input('post.name_e/s', '');
            $name          = input('post.name/s', '');
            $phone         = input('post.phone/s', '');
            $default       = input('post.default/d', 0);
            $bank_name     = input('post.bank_name/s', '');
            $bank_name     = input('post.bank_name/s', '');
            $min           = input('post.min/f', 0);
            $max           = input('post.max/f', '');
            $qrcode        = input('post.qrcode/s', '');
            $description   = input('post.description/s', '');
            $token         = input('__token__');

            $data = array(
                'payid'         => $payid,
                'bank_name'     => $bank_name,
                'branch_name'   => $branch_name,
                'branch_number' => $branch_number,
                'card_number'   => $card_number,
                'name_e'        => $name_e,
                'name'          => $name,
                'phone'         => $phone,
                'default'       => $default,
                'bank_name'     => $bank_name,
                'bank_name'     => $bank_name,
                'min'           => $min,
                'max'           => $max,
                'qrcode'        => $qrcode,
                'description'   => $description,
            );
            $res = Db::table($this->table)->insert($data);
            if (!$res) {
                return $this->error($res['info']);
            }
            $this->success('编辑成功', '/admin.html#/admin/pay_info/index.html');
        }

        $pay = db('xy_pay')->where(['status' => 1, 'recharge' => 1])->select();
        $this->assign('pay', $pay);
        return $this->fetch();
    }

    /**
     * 编辑支付信息
     * @auth true
     * @menu true
     */
    public function edit()
    {
        $id = input('get.id', 0);
        if (request()->isPost()) {
            $payid         = input('post.payid/d', 0);
            $bank_name     = input('post.bank_name/s', '');
            $branch_name   = input('post.branch_name/s', '');
            $branch_number = input('post.branch_number/s', '');
            $card_number   = input('post.card_number/s', '');
            $name_e        = input('post.name_e/s', '');
            $name          = input('post.name/s', '');
            $phone         = input('post.phone/s', '');
            $default       = input('post.default/d', 0);
            $bank_name     = input('post.bank_name/s', '');
            $bank_name     = input('post.bank_name/s', '');
            $min           = input('post.min/f', 0);
            $max           = input('post.max/f', '');
            $qrcode        = input('post.qrcode/s', '');
            $description   = input('post.description/s', '');
            $token         = input('__token__');

            $data = array(
                'payid'         => $payid,
                'bank_name'     => $bank_name,
                'branch_name'   => $branch_name,
                'branch_number' => $branch_number,
                'card_number'   => $card_number,
                'name_e'        => $name_e,
                'name'          => $name,
                'phone'         => $phone,
                'default'       => $default,
                'bank_name'     => $bank_name,
                'bank_name'     => $bank_name,
                'min'           => $min,
                'max'           => $max,
                'qrcode'        => $qrcode,
                'description'   => $description,
            );
            $res = Db::table($this->table)->where('id', $id)->update($data);
            if (!$res) {
                return $this->error($res['info']);
            }
            $this->success('编辑成功', '/admin.html#/admin/pay_info/index.html');
        }
        if (!$id) {
            $this->error('参数错误');
        }

        $this->info = Db::table($this->table)->find($id);
        $pay        = db('xy_pay')->where(['status' => 1, 'recharge' => 1])->select();
        $this->assign('pay', $pay);
        //var_dump($this->info);die;
        return $this->fetch();
    }

    /**
     * [del 删除分成比例]
     * @return [type] [description]
     */
    public function del()
    {
        $this->applyCsrfToken();
        $id  = input('id/d', 0);
        $res = Db::table($this->table)->where('id', $id)->delete();
        if ($res) {
            $this->success('删除成功!');
        } else {
            $this->error('删除失败!');
        }
    }
}
