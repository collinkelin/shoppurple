<?php

namespace app\index\controller;

use think\Controller;

class Support extends Base
{
    /**
     * 首页
     */
    public function index()
    {
        $this->info = db('xy_cs')->where('status', 1)->order('type DESC')->select();
        $this->assign('list', $this->info);
        $this->msg = db('xy_index_msg')->where('status', 1)->select();
        return $this->fetch();
    }

    /**
     * [live 线上客服]
     * @return [type] [description]
     */
    public function live()
    {
        $id   = input('id');
        $info = db('xy_cs')->where('id', $id)->find();
        return view('live', ['info' => $info]);
    }

    /**
     * 首页
     */
    public function detail()
    {
        $id         = input('get.id/d', 1);
        $this->info = db('xy_index_msg')->where('id', $id)->find();
        return $this->fetch();
    }

    /**
     * 换一个客服
     */
    public function other_cs()
    {
        $data = db('xy_cs')->where('status', 1)->where('id', '<>', $id)->find();
        if ($data) {
            return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $data]);
        }
        return json(['code' => 1, 'info' => lang('No data')]);
    }
}
