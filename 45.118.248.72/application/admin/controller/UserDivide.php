<?php

namespace app\admin\controller;

use library\Controller;
use think\Db;

/**
 * 分成比例
 * Class UserDivide
 * @package app\admin\controller
 */
class UserDivide extends Controller
{

    /**
     * 指定当前数据表
     * @var string
     */
    protected $table = 'xy_user_divide';

    public function initialize()
    {
        $host = \think\facade\Request::host();
        if (config('site_admin_domain') && config('site_admin_domain') != $host) {
            $this->redirect('@index');
        }
    }

    public function index()
    {
        $this->title = '分成比例';
        $this->_query($this->table)->page();
    }

    /**
     * [add 添加分成比例]
     */
    public function add()
    {
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $level  = input('post.level/d', 0);
            $divide = input('post.divide/d', 0);
            $res    = db($this->table)->insert(
                [
                    'level'  => $level,
                    'divide' => $divide,
                ]);
            if ($res !== false) {
                return $this->success('操作成功');
            } else {
                return $this->error('操作失败');
            }
        }
        return $this->fetch();
    }

    /**
     * [edit 编辑分成比例]
     * @return [type] [description]
     */
    public function edit()
    {
        $id = input('id/d', 0);
        if (empty($id)) {
            return $this->error('参数错误');
        }
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $level  = input('post.level/d', 0);
            $divide = input('post.divide/d', 0);
            $res    = db($this->table)->where('id', $id)->update(
                [
                    'level'  => $level,
                    'divide' => $divide,
                ]);
            if ($res !== false) {
                return $this->success('操作成功');
            } else {
                return $this->error('操作失败');
            }
        }
        $info = Db::name($this->table)->where('id', $id)->find();
        return view('', ['info' => $info]);
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
