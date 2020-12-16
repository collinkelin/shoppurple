<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use util\Time;

class My extends Base
{
    /**
     * 首页
     */
    public function index()
    {
        $uid        = session('user_id');
        $this->info = db('xy_users')->field('username,is_mobile,level,id,headpic,balance,freeze_balance,invite_code,show_td')->find($uid);
        $this->lv3  = [0, config('vip_3_num')];
        $this->lv2  = [0, config('vip_2_num')];
        $map        = [
            ['uid', '=', $uid],
            ['status', '=', 1],
            ['addtime', 'between', Time::today()],
        ];
        $this->sell_y_num = db('xy_convey')->where($map)->sum('commission');
        $map              = [
            ['uid', '=', $uid],
            ['status', '=', 1],
            ['addtime', 'between', Time::today()],
        ];
        $this->sell_y_num += db('xy_reward_log')->where($map)->sum('num');

        $l           = $this->info['level'];
        $this->level = get_levels($l);

        return $this->fetch();
    }

    /**
     * 获取收货地址
     */
    public function get_address()
    {
        $id   = session('user_id');
        $data = db('xy_member_address')->where('uid', $id)->select();
        if ($data) {
            // 请求成功
            return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $data]);
        } else {
            // 请求错误
            return json(['code' => 1, 'info' => lang('No data')]);
        }

    }

    /**
     * 添加收货地址
     */
    public function add_address()
    {
        if (request()->isPost()) {
            $uid      = session('user_id');
            $name     = input('post.name/s', '');
            $tel      = input('post.tel/s', '');
            $address  = input('post.address/s', '');
            $area     = input('post.area/s', '');
            $token    = input("token"); //获取提交过来的令牌
            $data     = ['__token__' => $token];
            $validate = \Validate::make($this->rule, ['__token__' => lang('Please do not submit again!')]);
            if (!$validate->check($data)) {
                return json(['code' => 1, 'info' => $validate->getError()]);
            }
            $data = [
                'uid'     => $uid,
                'name'    => $name,
                'tel'     => $tel,
                'area'    => $area,
                'address' => $address,
                'addtime' => time(),
            ];
            $tmp = db('xy_member_address')->where('uid', $uid)->find();
            if (!$tmp) {
                $data['is_default'] = 1;
            }

            $res = db('xy_member_address')->insert($data);
            if ($res) {
                // 操作成功
                return json(['code' => 0, 'info' => lang('Successful operation')]);
            } else {
                //lang('operation failed')                return json(['code' => 1, 'info' => lang('operation failed')]);
            }

        }
        // 参数错误
        return json(['code' => 1, 'info' => lang('Error failed')]);
    }

    /**
     * 编辑收货地址
     */
    public function edit_address()
    {
        if (request()->isPost()) {
            $uid     = session('user_id');
            $name    = input('post.name/s', '');
            $tel     = input('post.tel/s', '');
            $address = input('post.address/s', '');
            $area    = input('post.area/s', '');
            $id      = input('post.id/d', 0);

            $info = db('xy_member_address')->find($id);
            if (!$info) {
                // 收货地址不存在，请刷新再试
                return json(['code' => 1, 'info' => lang('The delivery address does not exist, please refresh and try again')]);
            }

            if ($info['uid'] != session('user_id')) {
                // 参数错误
                return json(['code' => 1, 'info' => lang('Parameter error')]);
            }

            $res = db('xy_member_address')
                ->where('id', $id)
                ->update([
                    'uid'     => $uid,
                    'name'    => $name,
                    'tel'     => $tel,
                    'area'    => $area,
                    'address' => $address,
                    'addtime' => time(),
                ]);
            if ($res) {
                return json(['code' => 0, 'info' => lang('Successful operation')]);
            } else {
                return json(['code' => 1, 'info' => lang('operation failed')]);
            }

        } elseif (request()->isGet()) {
            $id   = input('id/d', 0);
            $info = db('xy_member_address')->find($id);
            if (!$info) {
                // 收货地址不存在，请刷新再试
                return json(['code' => 1, 'info' => lang('The delivery address does not exist, please refresh and try again')]);
            }

            if ($info['uid'] != session('user_id')) {
                // 参数错误
                return json(['code' => 1, 'info' => lang('Parameter error')]);
            }
            // 请求成功
            return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $info]);
        }
    }

    /**
     * 设置默认收货地址
     */
    public function set_address()
    {
        if (request()->isPost()) {
            $id = input('post.id/d', 0);
            Db::startTrans();
            $res  = db('xy_member_address')->where('uid', session('user_id'))->update(['is_default' => 0]);
            $res1 = db('xy_member_address')->where('id', $id)->update(['is_default' => 1]);
            if ($res && $res1) {
                Db::commit();
                return json(['code' => 0, 'info' => lang('Successful operation')]);
            } else {
                Db::rollback();
                return json(['code' => 1, 'info' => lang('operation failed')]);
            }
        }
        // 错误请求
        return json(['code' => 1, 'info' => lang('Error failed')]);
    }

    /**
     * 删除收货地址
     */
    public function del_address()
    {
        if (request()->isPost()) {
            $id   = input('post.id/d', 0);
            $info = db('xy_member_address')->find($id);
            if ($info['is_default'] == 1) {
                return json(['code' => 1, 'info' => lang('Cannot delete the default shipping address')]);
            }
            $res = db('xy_member_address')->where('id', $id)->delete();
            if ($res) {
                return json(['code' => 0, 'info' => lang('Successful operation')]);
            } else {
                return json(['code' => 1, 'info' => lang('operation failed')]);
            }

        }
        return json(['code' => 1, 'info' => lang('Error failed')]);
    }

    public function get_bot()
    {
        $data = model('common/Users')->get_botuser(session('user_id'), 3);
        halt($data);
    }

    //邀请界面
    public function invite()
    {
        $uid = session('user_id');
        $this->assign('pic', getinvite_pic($uid));
        return $this->fetch();
    }

    //我的资料
    public function do_my_info()
    {
        if (request()->isPost()) {
            $headpic  = input('post.headpic/s', '');
            $wx_ewm   = input('post.wx_ewm/s', '');
            $zfb_ewm  = input('post.zfb_ewm/s', '');
            $nickname = input('post.nickname/s', '');
            $sign     = input('post.sign/s', '');
            $data     = [
                'nickname'  => $nickname,
                'signiture' => $sign,
            ];

            if ($headpic) {
                if (is_image_base64($headpic)) {
                    $headpic = '/' . $this->upload_base64('xy', $headpic);
                }
                //调用图片上传的方法
                else {
                    return json(['code' => 1, 'info' => lang('Picture format error')]);
                }

                $data['headpic'] = $headpic;
            }

            if ($wx_ewm) {
                if (is_image_base64($wx_ewm)) {
                    $wx_ewm = '/' . $this->upload_base64('xy', $wx_ewm);
                }
                //调用图片上传的方法
                else {
                    return json(['code' => 1, 'info' => lang('Picture format error')]);
                }

                $data['wx_ewm'] = $wx_ewm;
            }

            if ($zfb_ewm) {
                if (is_image_base64($zfb_ewm)) {
                    $zfb_ewm = '/' . $this->upload_base64('xy', $zfb_ewm);
                }
                //调用图片上传的方法
                else {
                    return json(['code' => 1, 'info' => lang('Picture format error')]);
                }

                $data['zfb_ewm'] = $zfb_ewm;
            }

            $res = db('xy_users')->where('id', session('user_id'))->update($data);
            if ($res !== false) {
                return json(['code' => 0, 'info' => lang('Successful operation')]);
            } else {
                return json(['code' => 1, 'info' => lang('operation failed')]);
            }
        } elseif (request()->isGet()) {
            $info = db('xy_users')->field('username,headpic,nickname,signiture sign,wx_ewm,zfb_ewm')->find(session('user_id'));
            return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $info]);
        }
    }

    // 消息
    public function msg()
    {
        $this->info = db('xy_message')
            ->alias('m')
        // ->leftJoin('xy_users u','u.id=m.sid')
            ->leftJoin('xy_reads r', 'r.mid=m.id and r.uid=' . session('user_id'))
            ->field('m.*,r.id rid')
            ->where('m.uid', 'in', [0, session('user_id')])
            ->order('addtime desc')
            ->select();
        return $this->fetch();
    }

    //记录阅读情况
    public function reads()
    {
        if (\request()->isPost()) {
            $id = input('post.id/d', 0);
            db('xy_reads')->insert(['mid' => $id, 'uid' => session('user_id'), 'addtime' => time()]);
            return $this->success(lang('Request succeeded'));
        }
    }

    //修改绑定手机号
    public function reset_tel()
    {
        $pwd      = input('post.pwd', '');
        $verify   = input('post.verify/s', '');
        $tel      = input('post.tel/s', '');
        $userinfo = Db::table('xy_users')->field('id,pwd,salt')->find(session('user_id'));
        if ($userinfo['pwd'] != sha1($pwd . $userinfo['salt'] . config('pwd_str'))) {
            return json(['code' => 1, 'info' => lang('Incorrect login password')]);
        }

        if (config('app.verify')) {
            $verify_msg = Db::table('xy_verify_msg')->field('msg,addtime')->where(['tel' => $tel, 'type' => 3])->find();
            if (!$verify_msg) {
                return json(['code' => 1, 'info' => lang('Verification code does not exist')]);
            }

            if ($verify != $verify_msg['msg']) {
                return json(['code' => 1, 'info' => lang('Verification code error')]);
            }

            if (($verify_msg['addtime'] + (config('app.zhangjun_sms.min') * 60)) < time()) {
                return json(['code' => 1, 'info' => lang('Verification code has expired')]);
            }

        }
        $res = db('xy_users')->where('id', session('user_id'))->update(['tel' => $tel]);
        if ($res !== false) {
            return json(['code' => 0, 'info' => lang('Successful operation')]);
        } else {
            return json(['code' => 1, 'info' => lang('operation failed')]);
        }

    }

    //团队佣金列表
    public function get_team_reward()
    {
        $uid = session('user_id');
        $lv  = input('post.lv/d', 1);
        $map = [
            ['r.uid', '=', $uid],
            ['r.addtime', 'between', Time::today()],
            ['r.lv', '=', $lv],
            ['r.status', '=', 1],
        ];
        $num = Db::name('xy_reward_log')
            ->alias('r')
            ->where($map)
            ->sum('num');
        $data = Db::name('xy_reward_log')
            ->alias('r')
            ->leftJoin('xy_convey c', 'c.id=r.oid')
            ->leftJoin('xy_users u', 'u.id=c.uid')
            ->where($map)
            ->field('r.num,u.username,u.nickname,u.id')
            ->select();
        $list = [];
        foreach ($data as $key => $value) {
            $nickname = $value['nickname'];
            if (empty($nickname)) {
                $nickname = hiddenCharacter($value['username']);
            }
            if (empty($list[$value['id']])) {
                $list[$value['id']] = [
                    'nickname' => $nickname,
                    'num'      => round($value['num'], 2),
                ];
            } else {
                $list[$value['id']]['num'] = round($list[$value['id']]['num'] + $value['num'], 2);
            }
        }
        if ($num) {
            return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => ['num' => $num, 'list' => $list]]);
        }

        return json(['code' => 1, 'info' => lang('No data')]);
    }
}
