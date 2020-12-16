<?php

namespace app\admin\controller;

use app\common\model\Lang as LangModel;
use library\Controller;
use think\Db;
use think\facade\Cache;

/**
 * 语言中心
 * Class Users
 * @package app\admin\controller
 */
class Langs extends Controller
{

    public function initialize()
    {
        $host = \think\facade\Request::host();
        if (config('site_admin_domain') && config('site_admin_domain') != $host) {
            $this->redirect('@index');
        }
    }

    /**
     * 语言管理
     * @auth true
     * @menu true
     */
    public function langs_index()
    {
        $this->title = '语言管理';
        $where       = [];
        if (input('range/s', '')) {
            $where[] = ['range', '=', input('range/s', '')];
        }

        if (input('value/s', '')) {
            $where[] = [input('key', ''), 'like', '%' . input('value/s', '') . '%'];
        }
        $this->assign('search', input('') ? json_encode(input('')) : []);
        $lang_list = lang_list(false)['list'];
        $this->assign('lang_list', $lang_list);
        $this->_query('system_lang')->where($where)->order('id DESC')->page();
    }

    public function info()
    {
        $para = input('');
        if (empty($para['name'])) {
            $this->error('');
        }
        $map = [
            ['name', '=', $para['name']],
            ['range', '=', getRange()],
        ];
        if (!empty($para['type'])) {
            $map[] = ['type', '=', $para['type']];
        }
        if (!empty($para['position'])) {
            $map[] = ['position', '=', $para['position']];
        }
        $lang = LangModel::where($map)->find();
        $this->success('', $lang);
    }

    /**
     * 添加语言
     * @auth true
     * @menu true
     */
    public function langs_add($id = 0)
    {
        $id        = intval($id);
        $tpls      = Db::table('system_lang')->where('range', 'zh-cn')->select();
        $lang_list = lang_list(false)['list'];
        $info      = '';
        $info      = Db::table('system_lang')->find($id);
        if (!$info) {
            $this->error('没有该数据');
        }
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $range   = input('post.range/s', '');
            $content = input('post.content/s', '');
            if ($range == 'zh-cn') {
                $this->error('添加中文，请联系管理员');
            }
            if (!array_key_exists($range, $lang_list)) {
                $this->error('语言错误');
            }
            if (!$range) {
                $this->error('语言为必填项');
            }
            if (!$content) {
                $this->error('通知模版内容为必填项');
            }
            $data = [
                'range'    => $range,
                'name'     => $info['name'],
                'position' => $info['position'],
                'content'  => $content,
            ];
            $res = Db::table('system_lang')->insert($data);
            if ($res) {
                Cache::clear();
                $this->success('添加成功', '/admin.html#/admin/langs/langs_index.html');
            } else {
                $this->error('添加失败');
            }
        }
        return view('', ['info' => $info, 'tpls' => $tpls, 'lang_list' => $lang_list]);
    }

    /**
     * 编辑语言
     * @auth true
     * @menu true
     */
    public function langs_edit($id)
    {
        $id        = intval($id);
        $tpls      = Db::table('system_lang')->where('range', 'zh-cn')->select();
        $lang_list = lang_list(false)['list'];
        $info      = Db::table('system_lang')->find($id);
        if (!$info) {
            $this->error('没有该数据');
        }
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $lang_list = lang_list(false)['list'];
            $id        = input('post.id/d', 0);
            $range     = input('post.range/s', '');
            $content   = input('post.content/s', '');
            if (!array_key_exists($range, $lang_list)) {
                $this->error('语言错误');
            }
            if (!$range) {
                $this->error('语言为必填项');
            }
            if ($info['range'] != $range && $range == 'zh-cn') {
                $this->error('添加中文，请联系管理员');
            }
            if (!$content) {
                $this->error('通知模版内容为必填项');
            }

            $data = [
                'range'   => $range,
                'content' => $content,
            ];
            $res = Db::table('system_lang')->where('id', $id)->update(['range' => $range, 'content' => $content]);
            if ($res) {
                Cache::clear();
                $this->success('编辑成功', '/admin.html#/admin/langs/langs_index.html');
            } else {
                $this->error('编辑失败');
            }
        }
        return view('', ['info' => $info, 'tpls' => $tpls, 'lang_list' => $lang_list]);
    }

    /**
     * 删除语言
     * @auth true
     * @menu true
     */
    public function langs_del()
    {
        $this->applyCsrfToken();
        $id   = input('post.id/d', 0);
        $info = Db::table('system_lang')->find($id);
        if (!$info) {
            $this->error('没有该数据');
        }
        if ($info['range'] == 'zh-cn') {
            $this->error('中文不能删除，请联系管理员');
        }
        $res = Db::table('system_lang')->where('id', $id)->delete();
        if ($res) {
            Cache::clear();
            $this->success('删除成功!');
        } else {
            $this->error('删除失败!');
        }

    }

}
