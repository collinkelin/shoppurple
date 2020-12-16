<?php

namespace app\index\controller;

use app\common\model\MessageQueue as MessageQueueModel;
use app\common\model\Users as UsersModel;
use library\Controller;
use think\Db;
use \think\facade\Lang;

/**
 * 登录控制器
 */
class User extends Controller
{

    protected $table = 'xy_users';

    public function __construct()
    {
        $sys_config = sys_config();
        if (empty($sys_config['site_switch']) || $sys_config['site_switch'] == 0) {
            $host = \think\facade\Request::host();
            if (empty(config('site_admin_domain')) || config('site_admin_domain') != $host) {
                $this->assign([
                    'bulletin' => $sys_config['site_switch_bulletin'],
                    'title'    => $sys_config['site_name'],
                ]);
                return $this->fetch('public/maintain');
            }
        }
        // // 设置允许的语言，从数据库读取
        // Lang::setAllowLangList(lang_list(false)['allow']);
        // // 设置当前语言，数据库的默认语言
        // Lang::range(lang_list(false)['default']);
    }

    /**
     * 空操作 用于显示错误页面
     */
    public function _empty($name)
    {

        return $this->fetch($name);
    }

    //用户登录页面
    public function login()
    {
        if (session('user_id')) {
            $this->redirect('index/index');
        }

        return $this->fetch();
    }

    //用户登录接口
    public function do_login()
    {
        $username = input('post.username/s', '');
        $pwd      = input('post.pwd/s', '');
        $keep     = input('post.keep/b', false);
        $user     = UsersModel::where('username', $username)->field('id,pwd,salt,pwd_error_num,allow_login_time,status,login_status,headpic')->find();
        if (empty($user)) {
            return json(['code' => 1, 'info' => lang('Account or password is incorrect')]);
        } else {
            if ($user['status'] != 1) {
                return json(['code' => 1, 'info' => lang('User has been disabled')]);
            }

            if ($user['allow_login_time'] && ($user['allow_login_time'] > time()) && ($user['pwd_error_num'] > config('pwd_error_num'))) {
                return ['code' => 1, 'info' => lang('error limit', [config('allow_login_min')])];
            }

            if ($user['pwd'] != sha1($pwd . $user['salt'] . config('pwd_str'))) {
                $up = [
                    'pwd_error_num'    => Db::raw('pwd_error_num + 1'),
                    'allow_login_time' => (time() + (config('allow_login_min') * 60)),
                ];
                UsersModel::where('id', $user['id'])->update($up);
                return json(['code' => 1, 'info' => lang('Account or password is incorrect')]);
            }
            $up = [
                'pwd_error_num'    => 0,
                'allow_login_time' => 0,
                'login_status'     => 1,
            ];
            UsersModel::where('id', $user['id'])->update($up);
            session('user_id', $user['id']);
            // cookie('user_id', $user['id']);
            // cookie('avatar', $user['headpic']);

            // if ($keep) {
            //     Cookie::forever('user_id', $user['id']);
            //     Cookie::forever('username', $username);
            //     Cookie::forever('pwd', $pwd);
            // }
        }
        return json(['code' => 0, 'info' => lang('login successful')]);
    }

    /**
     * 用户注册接口
     */
    public function do_register()
    {
        $username    = input('post.username/s', '');
        $is_mobile   = input('post.is_mobile/d', 0);
        $verify      = input('post.verify/d', ''); //短信验证码
        $pwd         = input('post.pwd/s', '');
        $pwd2        = input('post.deposit_pwd/s', '');
        $invite_code = input('post.invite_code/s', ''); //邀请码
        if (sysconf('invite_reg') && !$invite_code) {
            return json(['code' => 1, 'info' => lang('Invitation code cannot be empty')]);
        }
        if (sysconf('verify_switch') == 1) {
            $verify_msg = Db::table('xy_verify_msg')->field('msg,addtime')->where(['username' => $username, 'type' => 1])->find();
            if (!$verify_msg) {
                return json(['code' => 1, 'info' => lang('Verification code does not exist')]);
            }
            if ($verify != $verify_msg['msg']) {
                return json(['code' => 1, 'info' => lang('Verification code error')]);
            }
            if (($verify_msg['addtime'] + (sysconf('verify_time') * 60)) < time()) {
                return json(['code' => 1, 'info' => lang('Verification code has expired')]);
            }
        }
        $pid          = 0;
        $parent_first = 0;
        $child_level  = 0;
        if ($invite_code) {
            $parentinfo = Db::table($this->table)->field('id,parent_id,parent_first,child_level,status')->where('invite_code', $invite_code)->find();
            if (!$parentinfo) {
                return json(['code' => 1, 'info' => lang('Invitation code does not exist')]);
            }

            if ($parentinfo['status'] != 1) {
                return json(['code' => 1, 'info' => lang('This recommended user has been disabled')]);
            }

            $pid = $parentinfo['id'];
            if ($parentinfo['parent_first'] > 0) {
                $parent_first = $parentinfo['parent_first'];
            } else {
                $parent_first = $parentinfo['id'];
            }
            $child_level = $parentinfo['child_level'] + 1;
        }
        $data = [
            'username'     => $username,
            'is_mobile'    => $is_mobile,
            'pwd'          => $pwd,
            'parent_id'    => $pid,
            'pwd2'         => $pwd2,
            'parent_first' => $parent_first,
            'child_level'  => $child_level,
        ];
        $res = (new UsersModel)->add_users($data);
        // $res = model('common/Users')->add_users($data);
        return json($res);
    }

    public function logout()
    {
        \Session::delete('user_id');
        $this->redirect('login');
    }

    /**
     * 重置密码
     */
    public function do_forget()
    {
        if (!request()->isPost()) {
            return json(['code' => 1, 'info' => lang('Error failed')]);
        }
        $username = input('post.username/s', '');
        $pwd      = input('post.pwd/s', '');
        $verify   = input('post.verify/d', 0);
        // if (config('app.verify')) {
        $user = UsersModel::where('username', $username)->find();
        if (empty($user)) {
            return json(['code' => 1, 'info' => lang('User does not exist')]);
        }
        $map = [
            ['create_uid', '=', $user['id']],
            ['type', '=', 2],
        ];
        $verify_msg = MessageQueueModel::where($map)->find();
        if (!$verify_msg) {
            return json(['code' => 1, 'info' => lang('Verification code does not exist')]);
        }
        if (sysconf('verify_switch') == 1 && $verify != $verify_msg['token']) {
            return json(['code' => 1, 'info' => lang('Verification code error')]);
        }
        if ($verify_msg['expire_time'] < time()) {
            return json(['code' => 1, 'info' => lang('Verification code has expired')]);
        }
        // }
        $data = [
            'username' => $username,
            'pwd'      => $pwd,
        ];
        $res = (new UsersModel)->reset_pwd($data);
        // $res = model('common/Users')->reset_pwd($data);
        return json($res);
    }

    public function register($action = '', $invite_code = '')
    {
        $param             = input('');
        $this->username    = isset($param['u']) ? trim($param['u']) : '';
        $this->password    = isset($param['p']) ? trim($param['p']) : '';
        $this->invite_code = $invite_code;
        if (empty($this->invite_code)) {
            $this->invite_code = isset($param['c']) ? trim($param['c']) : '';
        }
        if (empty($this->invite_code)) {
            $this->invite_code = isset($param['invite_code']) ? trim($param['invite_code']) : '';
        }
        return $this->fetch();
    }
}
