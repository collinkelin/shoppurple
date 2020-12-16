<?php

namespace app\admin\controller;

use app\admin\service\NodeService;
use app\common\model\Deposit as DepositModel;
use app\common\model\Recharge as RechargeModel;
use app\common\model\Users as UsersModel;
use library\Controller;
use library\tools\Data;
use think\Console;
use think\Db;
use think\exception\HttpResponseException;
use think\facade\Cache;
use util\Time;

/**
 * 系统公共操作
 * Class Index
 * @package app\admin\controller
 */
class Index extends Controller
{

    public function initialize()
    {
        $host = \think\facade\Request::host();
        if (config('site_admin_domain') && config('site_admin_domain') != $host) {
            $this->redirect('@index');
        }
    }

    /**
     * 显示后台首页
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $this->title = '系统管理后台';
        NodeService::applyUserAuth(true);
        $this->menus = NodeService::getMenuNodeTree();
        if (empty($this->menus) && !NodeService::islogin()) {
            $this->redirect('@admin/login');
        } else {
            $this->fetch();
        }
    }

    /**
     * 后台环境信息
     * @auth true
     * @menu true
     */
    public function main()
    {
        $times = [
            'today'     => Time::today(),
            'yesterday' => Time::yesterday(),
            'week'      => Time::week(),
            'lastWeek'  => Time::lastWeek(),
            'month'     => Time::month(),
            'lastMonth' => Time::lastMonth(),
        ];
        $this->goods_num = db('xy_goods_list')->count('id');
        $this->users_num = db('xy_users')->count('id');
        $this->order_num = db('xy_convey')->count('id');
        $today           = Time::today();
        $map_end_today   = [
            ['status', '=', 1],
            ['endtime', 'between', $today],
        ];
        $map_add_today = [
            ['status', '=', 1],
            ['addtime', 'between', $today],
        ];
        $this->sell_num   = db('xy_convey')->where($map_end_today)->sum('num');
        $this->sell_count = db('xy_convey')->where($map_end_today)->count('id');
        $this->new_user   = db('xy_users')->where($map_add_today)->count('id');
        $this->user_order = db('xy_convey')->where($map_add_today)->field('uid')->Distinct(true)->select();
        $this->user_order = count($this->user_order);

        $map_end_yesterday = [
            ['status', '=', 1],
            ['endtime', 'between', $times['yesterday']],
        ];
        $map_add_yesterday = [
            ['status', '=', 1],
            ['addtime', 'between', $times['yesterday']],
        ];
        $this->sell_y_num   = db('xy_convey')->where($map_end_yesterday)->sum('num');
        $this->sell_y_count = db('xy_convey')->where($map_end_yesterday)->count('id');
        $this->new_y_user   = db('xy_users')->where($map_add_yesterday)->count('id');
        $this->user_y_order = db('xy_convey')->where($map_add_yesterday)->field('uid')->Distinct(true)->select();
        $this->user_y_order = count($this->user_y_order);
        $map                = [
            ['status', '=', 1],
            ['child_level', '=', 0],
            ['parent_first', '=', 0],
        ];
        $users = UsersModel::where($map)->field('id,username')->select();
        $list  = [];
        foreach ($users as $key => $value) {
            $map = [
                ['status', '=', 1],
                ['parent_first', '=', $value['id']],
                ['private', '=', 0],
            ];
            $childs = UsersModel::where($map)->field('id')->select();
            $ids    = [];
            foreach ($childs as $c => $child) {
                $ids[] = $child['id'];
            }
            $tims = [];
            foreach ($times as $ke => $var) {
                $recharge = 0;
                $deposit  = 0;
                $rmap     = [
                    ['uid', 'in', $ids],
                    ['status', '=', 2],
                    ['is_vip', '=', 0],
                    ['addtime', 'between', $var],
                ];
                $dmap = [
                    ['uid', 'in', $ids],
                    ['status', '=', 2],
                    ['addtime', 'between', $var],
                ];
                $sum = RechargeModel::alias('r')
                    ->where($rmap)
                    ->sum('num');
                if (empty($sum)) {
                    $sum = 0;
                }
                $recharge += $sum;
                $sum = DepositModel::alias('d')
                    ->where($dmap)
                    ->sum('num');
                if (empty($sum)) {
                    $sum = 0;
                }
                $deposit += $sum;
                $tims[$ke] = [
                    'recharge' => $recharge,
                    'deposit'  => $deposit,
                ];
            }
            $list[] = [
                'username' => $value['username'],
                'data'     => $tims,
            ];
        }
        $this->assign('list', $list);
        $this->fetch();
    }

    /**
     * 修改密码
     * @param integer $id
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function pass($id)
    {
        $this->applyCsrfToken();
        if (intval($id) !== intval(session('admin_user.id'))) {
            $this->error('只能修改当前用户的密码！');
        }
        if (!NodeService::islogin()) {
            $this->error('需要登录才能操作哦！');
        }
        if ($this->request->isGet()) {
            $this->verify = true;
            $this->_form('SystemUser', 'admin@user/pass', 'id', [], ['id' => $id]);
        } else {
            $data = $this->_input([
                'password'    => $this->request->post('password'),
                'repassword'  => $this->request->post('repassword'),
                'oldpassword' => $this->request->post('oldpassword'),
            ], [
                'oldpassword' => 'require',
                'password'    => 'require|min:4',
                'repassword'  => 'require|confirm:password',
            ], [
                'oldpassword.require' => '旧密码不能为空！',
                'password.require'    => '登录密码不能为空！',
                'password.min'        => '登录密码长度不能少于4位有效字符！',
                'repassword.require'  => '重复密码不能为空！',
                'repassword.confirm'  => '重复密码与登录密码不匹配，请重新输入！',
            ]);
            $user = Db::name('SystemUser')->where(['id' => $id])->find();
            if (md5($data['oldpassword']) !== $user['password']) {
                $this->error('旧密码验证失败，请重新输入！');
            }
            $result = NodeService::checkpwd($data['password']);
            if (empty($result['code'])) {
                $this->error($result['msg']);
            }

            if (Data::save('SystemUser', ['id' => $user['id'], 'password' => md5($data['password'])])) {
                $this->success('密码修改成功，下次请使用新密码登录！', '');
            } else {
                $this->error('密码修改失败，请稍候再试！');
            }
        }
    }

    /**
     * 修改用户资料
     * @param integer $id 会员ID
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function info($id = 0)
    {
        if (!NodeService::islogin()) {
            $this->error('需要登录才能操作哦！');
        }
        $this->applyCsrfToken();
        if (intval($id) === intval(session('admin_user.id'))) {
            $this->_form('SystemUser', 'admin@user/form', 'id', [], ['id' => $id]);
        } else {
            $this->error('只能修改登录用户的资料！');
        }
    }

    /**
     * 清理运行缓存
     * @auth true
     */
    public function clearRuntime()
    {
        if (!NodeService::islogin()) {
            $this->error('需要登录才能操作哦！');
        }
        try {
            Console::call('clear');
            Console::call('xclean:session');
            Cache::clear();
            $this->success('清理运行缓存成功！');
        } catch (HttpResponseException $exception) {
            throw $exception;
        } catch (\Exception $e) {
            $this->error("清理运行缓存失败，{$e->getMessage()}");
        }
    }

    /**
     * 压缩发布系统
     * @auth true
     */
    public function buildOptimize()
    {
        if (!NodeService::islogin()) {
            $this->error('需要登录才能操作哦！');
        }
        try {
            Console::call('optimize:route');
            Console::call('optimize:schema');
            Console::call('optimize:autoload');
            Console::call('optimize:config');
            $this->success('压缩发布成功！');
        } catch (HttpResponseException $exception) {
            throw $exception;
        } catch (\Exception $e) {
            $this->error("压缩发布失败，{$e->getMessage()}");
        }
    }

    public function tip()
    {
        $rmap = [
            ['addtime', '>', time() - 1200],
            ['endtime', '=', 0],
            ['tip', '<', 5],
            ['status', '=', 1],
            ['pic', '<>', ''],
        ];
        $map = [
            ['addtime', '>', time() - 1200],
            ['endtime', '=', 0],
            ['tip', '<', 5],
            ['status', '=', 1],
        ];
        $data = [
            'recharge' => 0,
            'deposit'  => 0,
        ];
        $recharge = Db::table('xy_recharge')->where($rmap)->find();
        if (!empty($recharge)) {
            $data['recharge'] = $recharge;
            Db::table('xy_recharge')->where('id', $recharge['id'])->inc('tip')->update();
        }
        $deposit = Db::table('xy_deposit')->where($map)->find();
        if (!empty($deposit)) {
            $data['deposit'] = $deposit;
            Db::table('xy_deposit')->where('id', $deposit['id'])->inc('tip')->update();
        }
        $this->success('', $data);
    }

    public function clear()
    {
        $isVersion = $this->Update(0);
    }

    //查看图片
    public function picinfo()
    {
        $this->pic = input('get.pic/s', '');
        if (!$this->pic) {
            return;
        }
        $this->fetch();
    }
}
