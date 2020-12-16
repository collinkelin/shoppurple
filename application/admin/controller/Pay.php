<?php

namespace app\admin\controller;

use app\common\model\Pay as PayModel;
use library\Controller;
use library\tools\Data;
use think\Db;

/**
 * 会员管理
 * Class Users
 * @package app\admin\controller
 */
class Pay extends Base
{

    public function initialize()
    {
        $host = \think\facade\Request::host();
        if (config('site_admin_domain') && config('site_admin_domain') != $host) {
            $this->redirect('@index');
        }
    }

    /**
     * 支付方式管理
     * @auth true
     * @menu true
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $this->title = '支付方式管理';
        $list        = PayModel::order('status DESC,recharge DESC')->select()->toArray();
        return view('', ['list' => $list]);
    }

    /**
     * 编辑会员
     * @auth true
     * @menu true
     */
    public function edit()
    {
        $this->applyCsrfToken();
        $prams = input('');
        if (request()->isPost()) {

            $info = new PayModel;
            // 过滤post数组中的非数据表字段数据
            $res = $info->allowField(true)->save($prams, ['id' => $prams['id']]);
            if (!$res) {
                return $this->error($res['info']);
            }
            $this->success('编辑成功', '/admin.html#/admin/pay/index.html');
        }
        if (empty($prams['id'])) {
            $this->error('参数错误');
        }
        $info = PayModel::where('id', $prams['id'])->find();
        return view('', ['info' => $info]);
    }

    /**
     * [status 修改状态]
     * @return [type] [description]
     */
    public function status()
    {
        // $this->applyCsrfToken();
        $prams = input('');
        if (empty($prams['id'])) {
            $this->error('参数错误');
        }
        $info = new PayModel;
        // 过滤post数组中的非数据表字段数据
        $res = $info->allowField(true)->save($prams, ['id' => $prams['id']]);
        if ($res) {
            $this->success('操作成功');
        }
        $this->error($res['info']);
    }
}
