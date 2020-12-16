<?php

namespace app\admin\controller;

use library\Controller;
use think\Db;
use think\facade\Cache;

/**
 * 帮助中心
 * Class Users
 * @package app\admin\controller
 */
class Help extends Controller
{

    public function initialize()
    {
        $host = \think\facade\Request::host();
        if (config('site_admin_domain') && config('site_admin_domain') != $host) {
            $this->redirect('@index');
        }
    }

    /**
     * 公告管理
     * @auth true
     * @menu true
     */
    public function message_ctrl()
    {
        $this->title = '公告管理';
        $this->_query('xy_message')->order('id DESC')->page();
    }

    /**
     * 添加公告
     * @auth true
     * @menu true
     */
    public function add_message()
    {
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $title   = input('post.title/s', '');
            $content = input('post.content/s', '');

            if (!$title) {
                $this->error('标题为必填项');
            }

            if (mb_strlen($title) > 50) {
                $this->error('标题长度限制为50个字符');
            }

            if (!$content) {
                $this->error('公告内容为必填项');
            }

            $res = Db::table('xy_message')->insert(['addtime' => time(), 'sid' => 0, 'type' => 3, 'title' => $title, 'content' => $content]);
            if ($res) {
                $this->success('发送公告成功', '/admin.html#/admin/help/message_ctrl.html');
            } else {
                $this->error('发送公告失败');
            }

        }
        return $this->fetch();
    }

    /**
     * 编辑公告
     * @auth true
     * @menu true
     */
    public function edit_message($id)
    {
        $id = intval($id);
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $title   = input('post.title/s', '');
            $content = input('post.content/s', '');
            $id      = input('post.id/d', 0);

            if (!$title) {
                $this->error('标题为必填项');
            }

            if (mb_strlen($title) > 50) {
                $this->error('标题长度限制为50个字符');
            }

            if (!$content) {
                $this->error('公告内容为必填项');
            }

            $res = Db::table('xy_message')->where('id', $id)->update(['addtime' => time(), 'type' => 3, 'title' => $title, 'content' => $content]);
            if ($res) {
                $this->success('编辑成功', '/admin.html#/admin/help/message_ctrl.html');
            } else {
                $this->error('编辑失败');
            }

        }

        $info = Db::table('xy_message')->find($id);
        $this->assign('info', $info);
        $this->fetch();
    }

    /**
     * 删除公告
     * @auth true
     * @menu true
     */
    public function del_message()
    {
        $this->applyCsrfToken();
        $id  = input('post.id/d', 0);
        $res = Db::table('xy_message')->where('id', $id)->delete();
        if ($res) {
            $this->success('删除成功!');
        } else {
            $this->error('删除失败!');
        }

    }

    /**
     * 前台首页文本
     * @auth true
     * @menu true
     */
    public function home_msg()
    {
        $this->_query('xy_index_msg')->page();
    }

    /**
     * 编辑前台首页文本
     * @auth true
     * @menu true
     */
    public function edit_home_msg($id)
    {
        $id = intval($id);
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $title   = input('post.title/s', '');
            $content = input('post.content/s', '');
            $id      = input('post.id/d', 0);

            if (!$title) {
                $this->error('标题为必填项');
            }
            if (!$content) {
                $this->error('正文内容为必填项');
            }

            $res = Db::table('xy_index_msg')->where('id', $id)->update(['addtime' => time(), 'title' => $title, 'content' => $content]);
            if ($res) {
                $this->success('编辑成功', '/admin.html#/admin/help/home_msg.html');
            } else {
                $this->error('编辑失败');
            }

        }

        $info = Db::table('xy_index_msg')->find($id);
        $this->assign('info', $info);
        $this->fetch();
    }

    /**
     * 首页轮播图
     * @auth true
     * @menu true
     */
    public function banner()
    {
        // if (request()->isPost()) {
        //     $image = input('post.image/s', '');
        //     if ($image == '') {
        //         $this->error('请上传图片');
        //     }
        //     $res = Db::name('xy_banner')->where('id', 1)->update(['image' => $image]);
        //     if ($res !== false) {
        //         $this->success('操作成功');
        //     } else {
        //         $this->error('操作失败');
        //     }
        // }
        // $this->title = '轮播图设置';
        // $this->info  = Db::name('xy_banner')->find(1);
        // $this->fetch();

        $this->_query('xy_banner')->page();
    }

    /**
     * 编辑前台首页文本
     * @auth true
     * @menu true
     */
    public function edit_banner($id)
    {
        $id = intval($id);
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $id    = input('post.id/d', 0);
            $url   = input('post.url/s', '');
            $image = input('post.image/s', '');

            if (!$image) {
                $this->error('图片为必填项');
            }

            $res = Db::table('xy_banner')->where('id', $id)->update(['image' => $image, 'url' => $url]);
            if ($res) {
                $this->success('编辑成功', '/admin.html#/admin/help/banner.html');
            } else {
                $this->error('编辑失败');
            }

        }

        $info = Db::table('xy_banner')->find($id);
        $this->assign('info', $info);
        $this->fetch();
    }

    /**
     * 添加公告
     * @auth true
     * @menu true
     */
    public function add_banner()
    {
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $url   = input('post.url/s', '');
            $image = input('post.image/s', '');

            //if(!$title)$this->error('标题为必填项');
            //if(mb_strlen($title) > 50)$this->error('标题长度限制为50个字符');
            if (!$url) {
                $this->error('图片为必填项');
            }

            $res = Db::table('xy_banner')->insert(['url' => $url, 'image' => $image]);
            if ($res) {
                $this->success('提交成功', '/admin.html#/admin/help/banner.html');
            } else {
                $this->error('提交失败');
            }

        }
        return $this->fetch();
    }

    public function del_banner()
    {
        $this->applyCsrfToken();
        $id  = input('post.id/d', 0);
        $res = Db::table('xy_banner')->where('id', $id)->delete();
        if ($res) {
            $this->success('删除成功!');
        } else {
            $this->error('删除失败!');
        }

    }

    /**
     * 通知模版管理
     * @auth true
     * @menu true
     */
    public function message_tpl()
    {
        $this->title = '通知模版管理';
        $where       = [];
        if (input('range/s', '')) {
            $where[] = ['range', '=', input('range/s', '')];
        }
        if (input('type/d', 0)) {
            $where[] = ['type', '=', input('type/d')];
        }

        if (input('value/s', '')) {
            $where[] = [input('key', ''), 'like', '%' . input('value/s', '') . '%'];
        }
        $this->assign('search', input('') ? json_encode(input('')) : []);
        $lang_list = lang_list(false)['list'];
        $this->assign('lang_list', $lang_list);
        $this->_query('system_message_tpl')->where($where)->order('id DESC')->page();
    }

    /**
     * 添加通知模版
     * @auth true
     * @menu true
     */
    public function add_message_tpl($id)
    {
        $id        = intval($id);
        $tpls      = Db::table('system_message_tpl')->where('range', 'zh-cn')->select();
        $lang_list = lang_list(false)['list'];
        $info      = '';
        $info      = Db::table('system_message_tpl')->find($id);
        if (!$info) {
            $this->error('没有该数据');
        }
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $type    = input('post.type/d', 0);
            $range   = input('post.range/s', '');
            $title   = input('post.title/s', '');
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
            if (!$title) {
                $this->error('标题为必填项');
            }
            if (!$content) {
                $this->error('通知模版内容为必填项');
            }
            $data = [
                'type'    => $type,
                'range'   => $range,
                'name'    => $info['name'],
                'title'   => $title,
                'content' => $content,
            ];
            $res = Db::table('system_message_tpl')->insert($data);
            if ($res) {
                Cache::clear();
                $this->success('发送通知模版成功', '/admin.html#/admin/help/message_tpl.html');
            } else {
                $this->error('发送通知模版失败');
            }
        }
        return view('', ['info' => $info, 'tpls' => $tpls, 'lang_list' => $lang_list]);
    }

    /**
     * 编辑通知模版
     * @auth true
     * @menu true
     */
    public function edit_message_tpl($id)
    {
        $id        = intval($id);
        $tpls      = Db::table('system_message_tpl')->where('range', 'zh-cn')->select();
        $lang_list = lang_list(false)['list'];
        $info      = Db::table('system_message_tpl')->find($id);
        if (!$info) {
            $this->error('没有该数据');
        }
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $lang_list = lang_list(false)['list'];
            $id        = input('post.id/d', 0);
            $type      = input('post.type/d', 0);
            $range     = input('post.range/s', '');
            $title     = input('post.title/s', '');
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
            if (!$title) {
                $this->error('标题为必填项');
            }
            if (!$content) {
                $this->error('通知模版内容为必填项');
            }
            $data = [
                // 'type'    => $type,
                'range'   => $range,
                'title'   => $title,
                'content' => $content,
            ];

            $res = Db::table('system_message_tpl')->where('id', $id)->update($data);
            if ($res) {
                Cache::clear();
                $this->success('编辑成功', '/admin.html#/admin/help/message_tpl.html');
            } else {
                $this->error('编辑失败');
            }
        }
        return view('', ['info' => $info, 'tpls' => $tpls, 'lang_list' => $lang_list]);
    }

    /**
     * 删除通知模版
     * @auth true
     * @menu true
     */
    public function del_message_tpl()
    {
        $this->applyCsrfToken();
        $id   = input('post.id/d', 0);
        $info = Db::table('system_message_tpl')->find($id);
        if (!$info) {
            $this->error('没有该数据');
        }
        if ($info['range'] == 'zh-cn') {
            $this->error('中文不能删除，请联系管理员');
        }
        $res = Db::table('system_message_tpl')->where('id', $id)->delete();
        if ($res) {
            Cache::clear();
            $this->success('删除成功!');
        } else {
            $this->error('删除失败!');
        }
    }

    /**
     * 帮助内容
     * @auth true
     * @menu true
     */
    public function help_list()
    {
        $this->_query('system_help')->page();
    }

    /**
     * 添加帮助内容
     * @auth true
     * @menu true
     */
    public function help_add()
    {
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $title   = input('post.title/s', '');
            $url     = input('post.url/s', '');
            $content = input('post.content/s', '');
            $sort    = input('post.sort/d', 0);

            if (!$title) {
                $this->error('标题为必填项');
            }
            $data = [
                'title'   => $title,
                'url'     => $url,
                'content' => $content,
                'sort'    => $sort,
            ];
            $res = Db::table('system_help')->insert($data);
            if ($res) {
                $this->success('提交成功', '/admin.html#/admin/help/help_list.html');
            } else {
                $this->error('提交失败');
            }
        }
        return $this->fetch();
    }

    /**
     * 编辑帮助内容
     * @auth true
     * @menu true
     */
    public function help_edit($id)
    {
        $id = intval($id);
        if (empty($id)) {
            $this->error('参数错误');
        }
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $id      = input('post.id/d', 0);
            $title   = input('post.title/s', '');
            $url     = input('post.url/s', '');
            $content = input('post.content/s', '');
            $sort    = input('post.sort/d', 0);

            if (!$title) {
                $this->error('标题为必填项');
            }
            $data = [
                'title'   => $title,
                'url'     => $url,
                'content' => $content,
                'sort'    => $sort,
            ];
            $res = Db::table('system_help')->where('id', $id)->update($data);
            if ($res) {
                $this->success('编辑成功', '/admin.html#/admin/help/help_list.html');
            } else {
                $this->error('编辑失败');
            }
        }

        $info = Db::table('system_help')->find($id);
        $this->assign('info', $info);
        $this->fetch();
    }

    /**
     * 删除帮助内容
     * @auth true
     * @menu true
     */
    public function help_del()
    {
        $this->applyCsrfToken();
        $id = input('post.id/d', 0);
        if (empty($id)) {
            $this->error('参数错误');
        }
        $res = Db::table('system_help')->where('id', $id)->delete();
        if ($res) {
            $this->success('删除成功!');
        } else {
            $this->error('删除失败!');
        }
    }
}
