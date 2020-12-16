<?php

namespace app\admin\controller;

use app\common\model\Deposit as DepositModel;
use app\common\model\Message as MessageModel;
use app\common\model\Recharge as RechargeModel;
use app\common\model\UserLevel as UserLevelModel;
use app\common\model\Users as UsersModel;
use library\Controller;
use library\tools\Data;
use think\Db;
use util\Time;

/**
 * 会员管理
 * Class Users
 * @package app\admin\controller
 */
class Users extends Controller
{

    /**
     * 指定当前数据表
     * @var string
     */
    protected $table = 'xy_users';

    protected $selectTime;

    public function initialize()
    {
        $host = \think\facade\Request::host();
        if (config('site_admin_domain') && config('site_admin_domain') != $host) {
            $this->redirect('/');
        }
    }

    /**
     * 会员列表
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
        $this->selectTime = Time::today();
        $this->title      = '会员列表';
        $param            = input('');
        $page             = input('page') ? input('page') : 1;
        $limit            = input('limit') ? input('limit') : 15;
        $query            = $this->_query($this->table)->alias('u');
        $where            = [];
        if (!empty($param['username'])) {
            $where[] = ['u.username', 'like', '%' . $param['username'] . '%'];
        }

        if (!empty($param['parent'])) {
            $where[] = ['u1.username', '=', $param['parent']];
        }

        if (!empty($param['balance'])) {
            $where[] = ['u.balance', '>=', $param['balance']];
        }

        if (!empty($param['addtime'])) {
            $arr     = explode(' - ', $param['addtime']);
            $where[] = ['u.addtime', 'between', [strtotime($arr[0]), strtotime($arr[1] . ' 23:59:59')]];
        }

        if (!empty($param['actiontime'])) {
            $arr              = explode(' - ', $param['actiontime']);
            $this->selectTime = [strtotime($arr[0]), strtotime($arr[1] . ' 23:59:59')];
        }

        $rmap = [
            ['r.status', '=', 2],
            ['r.is_vip', '=', 0],
            ['r.addtime', 'between', $this->selectTime],
        ];
        $dmap = [
            ['d.status', '=', 2],
            ['d.addtime', 'between', $this->selectTime],
        ];

        if (!empty($param['parent_first'])) {
            $where[] = ['up.username', '=', $param['parent_first']];
            $rmap[]  = ['up.username', '=', $param['parent_first']];
            $dmap[]  = ['up.username', '=', $param['parent_first']];
        }

        $sum = RechargeModel::alias('r')
            ->where($rmap)
            ->leftJoin('xy_users u', 'u.id=r.uid')
            ->leftJoin('xy_users up', 'up.id=u.parent_first')
            ->sum('num');
        if (empty($sum)) {
            $sum = 0;
        }
        $this->recharge = $sum;
        // $sums           = db('xy_convey')
        //     ->where($dmap)
        //     ->limit(20)
        //     ->group('xc.uid')
        //     ->sum();
        $sum = DepositModel::alias('d')
            ->where($dmap)
        // ->leftJoin('xy_users u', 'u.id=d.uid')
        // ->leftJoin('xy_users up', 'up.id=u.parent_first')
            ->group('uid')
            ->sum('num');
        if (empty($sum)) {
            $sum = 0;
        }
        $this->deposit = $sum;
        $this->counts  = UsersModel::where('balance', '>', 0)->count();
        $sort          = 'u.id DESC';
        if (!empty($param['sort'])) {
            $sort = 'u.' . $param['sortKey'] . ' ' . $param['sort'];
        }
        $query->field('u.*,u1.username as parent_name')
            ->leftJoin('xy_users u1', 'u.parent_id=u1.id')
            ->leftJoin('xy_users up', 'up.id=u.parent_first')
            ->withAttr('recharge_days', function ($value, $data) {
                $rmap = [
                    ['uid', '=', $data['id']],
                    ['status', '=', 2],
                    ['is_vip', '=', 0],
                    ['addtime', 'between', $this->selectTime],
                ];
                $sum = RechargeModel::where($rmap)->sum('num');
                if (empty($sum)) {
                    $sum = 0;
                }
                return $sum;
            })
            ->withAttr('deposit_days', function ($value, $data) {
                $rmap = [
                    ['uid', '=', $data['id']],
                    ['status', '=', 2],
                    ['addtime', 'between', $this->selectTime],
                ];
                $sum = DepositModel::where($rmap)->sum('num');
                if (empty($sum)) {
                    $sum = 0;
                }
                return $sum;
            })
            ->where($where)
            ->order($sort)
            ->page();
    }

    public function sendmsg()
    {
        $param = input('');
        if (empty($param['uid'])) {
            $this->error('请选择用户');
        }
        if (request()->isPost()) {
            // $this->applyCsrfToken();
            if (empty($param['title'])) {
                $this->error('请输入标题');
            }
            if (empty($param['content'])) {
                $this->error('请输入内容');
            }
            if (!empty($param['task'])) {
                if (empty($param['url'])) {
                    $param['url'] = 1;
                }
                $map = [
                    ['id', '=', $param['uid']],
                ];
                $data = [
                    'task'               => $param['task'] ?? 0,
                    'deal_special_count' => 0,
                ];
                UsersModel::where($map)->update($data);
            }
            $us = [];
            if (!empty($param['all'])) {
                $map = [
                    ['status', '=', 1],
                ];
                $us = UsersModel::where($map)->field('id')->select();
            } elseif (!empty($param['childs'])) {
                $map = [
                    ['parent_first', '=', $param['uid']],
                    ['status', '=', 1],
                ];
                $us = UsersModel::where($map)->field('id')->select();
            } elseif (!empty($param['child'])) {
                $map = [
                    ['parent_first', '=', $param['uid']],
                    ['child_level', 'in', $param['child']],
                    ['status', '=', 1],
                ];
                $us = UsersModel::where($map)->field('id')->select();
            }
            if (!empty($us)) {
                foreach ($us as $key => $value) {
                    $uids[] = $value['id'];
                }
                if (!empty($param['task'])) {
                    $map = [
                        ['id', 'in', $uids],
                    ];
                    $data = [
                        'task'               => $param['task'] ?? 0,
                        'deal_special_count' => 0,
                    ];
                    UsersModel::where($map)->update($data);
                }
                $uids[]       = $param['uid'];
                $param['uid'] = $uids;
            }
            $res = send_msg($param['uid'], $param['title'], $param['content'], $param['tip'] ?? 0, $param['special'] ?? 0, $param['url']);
            if (!$res) {
                return $this->error('操作失败');
            }
            return $this->success('操作成功', $res);
        }
        // SELECT child_level FROM `xy_users` WHERE `parent_first` = 1 GROUP BY child_level ORDER BY `child_level` ASC
        $lists = UsersModel::where('parent_first', $param['uid'])->field('child_level')->group('child_level')->order('child_level ASC')->select();
        if (!empty($lists)) {
            $levels = $lists->toArray();
        } else {
            $levels = [];
        }
        return view('', ['uid' => $param['uid'], 'levels' => $levels]);
    }

    public function cancel_special()
    {
        $id = input('id/d', 0);
        if (!$id) {
            return $this->error('参数错误');
        }
        $data = [
            'task'               => 0,
            'special'            => 0,
            'deal_special_count' => 0,
        ];
        $res = UsersModel::where('id', $id)->update($data);
        $map = [
            ['uid', '=', $id],
            ['tip', '=', 1],
            ['special', '=', 1],
        ];
        $d = [
            'tip'     => 0,
            'special' => 0,
        ];
        MessageModel::where($map)->update($d);
        if (!$res) {
            return $this->error('操作失败');
        }
        return $this->success('操作成功');
    }

    /**
     * 添加会员
     *@auth true
     *@menu true
     */
    public function add_users()
    {
        if (request()->isPost()) {
            $username  = input('post.username/s', '');
            $is_mobile = input('post.is_mobile/d', 0);
            $pwd       = input('post.pwd/s', '');
            $parent_id = input('post.parent_id/d', 0);
            $private   = input('post.private/d', 0);
            $token     = input('__token__', 1);
            $data      = [
                'username'  => $username,
                'is_mobile' => $is_mobile,
                'pwd'       => $pwd,
                'parent_id' => $parent_id,
                'private'   => $private,
            ];
            $res = model('common/Users')->add_users($data);
            if ($res['code'] !== 0) {
                return $this->error($res['info']);
            }
            return $this->success($res['info']);
        }
        $this->levels = get_levels();
        return $this->fetch();
    }

    /**
     * 编辑会员
     * @auth true
     * @menu true
     */
    public function edit_users()
    {
        $id = input('get.id', 0);
        if (request()->isPost()) {
            $id             = input('post.id/d', 0);
            $user_name      = input('post.user_name/s', '');
            $username       = input('post.username/s', '');
            $balance        = input('post.balance/d', 0);
            $freeze_balance = input('post.freeze_balance/d', 0);
            $pwd            = input('post.pwd/s', '');
            $pwd2           = input('post.pwd2/s', '');
            $parent_id      = input('post.parent_id/d', 0);
            $level          = input('post.level/d', 0);
            $recharge_total = input('post.recharge_total/d', 0);
            $private        = input('post.private/d', 0);
            $deal_status    = input('deal_status/d', 1);
            $data           = array(
                'id'             => $id,
                'user_name'      => $user_name,
                'username'       => $username,
                'balance'        => $balance,
                'freeze_balance' => $freeze_balance,
                'parent_id'      => $parent_id,
                'level'          => $level,
                'base_level'     => $level,
                'recharge_total' => $recharge_total,
                'private'        => $private,
                'deal_status'    => $deal_status,
            );
            if (!empty($pwd)) {
                $data['pwd'] = $pwd;
            }
            if (!empty($pwd2)) {
                $data['pwd2'] = $pwd2;
            }
            $res = model('common/Users')->edit_users($data);
            if ($res['code'] !== 0) {
                return $this->error($res['info']);
            }
            return $this->success($res['info']);
        }
        if (!$id) {
            $this->error('参数错误');
        }

        $this->info   = Db::table($this->table)->find($id);
        $this->levels = get_levels();
        return $this->fetch();
    }

    public function delete_user()
    {
        $this->applyCsrfToken();
        $id = input('post.id/d', 0);
        // $res = Db::table('xy_users')->where('id', $id)->delete();
        // if ($res) {
        // $this->success('删除成功!');
        // } else {
        $this->error('删除失败!');
        // }
    }

    /**
     * 编辑会员
     * @auth true
     * @menu true
     */
    public function edit_users_ankou()
    {
        $id = input('get.id', 0);
        if (request()->isPost()) {
            $id = input('post.id/d', 0);
            //            $show_td = input('post.show_td/d',0);  //显示我的团队
            //            $show_cz = input('post.show_cz/d',0);  //显示充值
            //            $show_tx = input('post.show_tx/d',0);  //显示提现
            //            $show_num = input('post.show_num/d',0);  //显示推荐人数
            //            $show_tel = input('post.show_tel/d',0);  //显示电话
            //            $show_tel2 = input('post.show_tel2/d',0);  //显示电话隐藏
            $kouchu_balance_uid = input('post.kouchu_balance_uid/d', 0); //扣除人
            $kouchu_balance     = input('post.kouchu_balance/f', 0); //扣除金额

            $show_td   = (isset($_REQUEST['show_td']) && $_REQUEST['show_td'] == 'on') ? 1 : 0; //显示我的团队
            $show_cz   = (isset($_REQUEST['show_cz']) && $_REQUEST['show_cz'] == 'on') ? 1 : 0; //显示充值
            $show_tx   = (isset($_REQUEST['show_tx']) && $_REQUEST['show_tx'] == 'on') ? 1 : 0; //显示提现
            $show_num  = (isset($_REQUEST['show_num']) && $_REQUEST['show_num'] == 'on') ? 1 : 0; //显示推荐人数
            $show_tel  = (isset($_REQUEST['show_tel']) && $_REQUEST['show_tel'] == 'on') ? 1 : 0; //显示电话
            $show_tel2 = (isset($_REQUEST['show_tel2']) && $_REQUEST['show_tel2'] == 'on') ? 1 : 0; //显示电话隐藏

            $token = input('__token__');
            $data  = [
                '__token__'          => $token,
                'show_td'            => $show_td,
                'show_cz'            => $show_cz,
                'show_tx'            => $show_tx,
                'show_num'           => $show_num,
                'show_tel'           => $show_tel,
                'show_tel2'          => $show_tel2,
                'kouchu_balance_uid' => $kouchu_balance_uid,
                'kouchu_balance'     => $kouchu_balance,
            ];

            //var_dump($data,$_REQUEST);die;
            unset($data['__token__']);
            $res = Db::table($this->table)->where('id', $id)->update($data);
            if (!$res) {
                return $this->error('编辑失败!');
            }
            return $this->success('编辑成功!');
        }

        if (!$id) {
            $this->error('参数错误');
        }

        $this->info = Db::table($this->table)->find($id);

        //
        $uid  = $id;
        $data = db('xy_users')->where('parent_id', $uid)
            ->field('id,username,headpic,addtime,childs')
            ->order('addtime desc')
            ->select();

        foreach ($data as &$datum) {
            //充值
            $datum['chongzhi'] = db('xy_recharge')->where('uid', $datum['id'])->where('status', 2)->sum('num');
            //提现
            $datum['tixian'] = db('xy_deposit')->where('uid', $datum['id'])->where('status', 1)->sum('num');
        }

        //var_dump($data,$uid);die;

        //$this->cate = db('xy_goods_cate')->order('addtime asc')->select();
        $this->assign('cate', $data);

        return $this->fetch();
    }

    /**
     * 编辑会员登录状态
     * @auth true
     */
    public function edit_users_status()
    {
        $id     = input('id/d', 0);
        $status = input('status/d', 0);
        if (!$id || !$status) {
            return $this->error('参数错误');
        }

        $res = model('common/Users')->edit_users_status($id, $status);
        if ($res['code'] !== 0) {
            return $this->error($res['info']);
        }
        return $this->success($res['info']);
    }

    /**
     * 编辑会员登录状态
     * @auth true
     */
    public function edit_users_ewm()
    {
        $id          = input('id/d', 0);
        $invite_code = input('status/s', '');
        if (!$id || !$invite_code) {
            return $this->error('参数错误');
        }

        $user = Db::table('xy_users')->where('id', $id)->find();
        if (!empty($user['invite_pic']) && file_exists('.' . $user['invite_pic'])) {
            unlink('.' . $user['invite_pic']);
        }
        // if (file_exists('.' . $user['invite_pic'])) {
        //     unlink('.' . $user['invite_pic']);
        // }

        $res = model('common/Users')->create_qrcode($invite_code, $id);
        if (0 && $res['code'] !== 0) {
            return $this->error('失败');
        }
        return $this->success('成功');
    }

    /**
     * 客服管理
     * @auth true
     * @menu true
     */
    public function cs_list()
    {
        $this->title = '客服列表';
        $where       = [];
        if (input('tel/s', '')) {
            $where[] = ['tel', 'like', '%' . input('tel/s', '') . '%'];
        }

        if (input('username/s', '')) {
            $where[] = ['username', 'like', '%' . input('username/s', '') . '%'];
        }

        if (input('addtime/s', '')) {
            $arr     = explode(' - ', input('addtime/s', ''));
            $where[] = ['addtime', 'between', [strtotime($arr[0]), strtotime($arr[1])]];
        }
        $this->_query('xy_cs')
            ->where($where)
            ->page();
    }

    /**
     * 添加客服
     * @auth true
     * @menu true
     */
    public function add_cs()
    {
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $title    = input('post.title/s', '');
            $username = input('post.username/s', '');
            $tel      = input('post.tel/s', '');
            $pwd      = input('post.pwd/s', '');
            $account  = input('post.account/s', '');
            $type     = input('post.type/d', 0);
            $url      = input('post.url/s', '');
            $qr_code  = input('post.qr_code/s', '');
            $time     = input('post.time');
            $arr      = explode('-', $time);
            $btime    = substr($arr[0], 0, 5);
            $etime    = substr($arr[1], 1, 5);
            $data     = [
                'title'    => $title,
                'username' => $username,
                'tel'      => $tel,
                'pwd'      => $pwd, //需求不明确，暂时以明文存储密码数据
                'account'  => $account,
                'url'      => $url,
                'type'     => $type,
                'qr_code'  => $qr_code,
                'btime'    => $btime,
                'etime'    => $etime,
                'addtime'  => time(),
            ];
            $res = db('xy_cs')->insert($data);
            if ($res) {
                return $this->success('添加成功');
            }

            return $this->error('添加失败，请刷新再试');
        }
        return $this->fetch();
    }

    /**
     * 编辑客服信息
     * @auth true
     * @menu true
     */
    public function edit_cs()
    {
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $id       = input('post.id/d', 0);
            $title    = input('post.title/s', '');
            $username = input('post.username/s', '');
            $tel      = input('post.tel/s', '');
            $pwd      = input('post.pwd/s', '');
            $account  = input('post.account/s', '');
            $type     = input('post.type/d', 0);
            $url      = input('post.url/s', '');
            $qr_code  = input('post.qr_code/s', '');
            $time     = input('post.time');
            $arr      = explode('-', $time);
            $btime    = substr($arr[0], 0, 5);
            $etime    = substr($arr[1], 1, 5);
            $data     = [
                'title'    => $title,
                'username' => $username,
                'tel'      => $tel,
                'account'  => $account,
                'url'      => $url,
                'type'     => $type,
                'qr_code'  => $qr_code,
                'btime'    => $btime,
                'etime'    => $etime,
            ];
            if ($pwd) {
                $data['pwd'] = $pwd;
            }

            $res = db('xy_cs')->where('id', $id)->update($data);
            if ($res !== false) {
                return $this->success('编辑成功');
            }

            return $this->error('编辑失败，请刷新再试');
        }
        $id         = input('id/d', 0);
        $this->list = db('xy_cs')->find($id);
        return $this->fetch();
    }

    /**
     * 客服登录状态
     * @auth true
     */
    public function edit_cs_status()
    {
        $this->applyCsrfToken();
        $this->_save('xy_cs', ['status' => input('post.status/d', 1)]);
    }

    /**
     * 客服调用代码
     * @auth true
     * @menu true
     */
    public function cs_code()
    {
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $code = input('post.code');
            $res  = db('xy_script')->where('id', 1)->update(['script' => $code]);
            if ($res !== false) {
                $this->success('操作成功!');
            }
            $this->error('操作失败!');
        }
        $this->code = db('xy_script')->where('id', 1)->value('script');
        return $this->fetch();
    }

    /**
     * 编辑银行卡信息
     * @auth true
     * @menu true
     */
    public function edit_users_bk()
    {
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $bank_name     = input('post.bank_name/s', '');
            $branch_name   = input('post.branch_name/s', '');
            $branch_number = input('post.branch_number/s', '');
            $card_number   = input('post.card_number/s', '');
            $name_e        = input('post.name_e/s', '');
            $name          = input('post.name/s', '');
            $tel           = input('post.tel/s', '');
            $status        = input('post.default/d', 0);
            $bkid          = input('post.bkid/d', 0); //是否为更新数据
            $data          = [
                'bank_name'     => $bank_name,
                'branch_name'   => $branch_name,
                'branch_number' => $branch_number,
                'card_number'   => $card_number,
                'name_e'        => $name_e,
                'name'          => $name,
                'status'        => $status,
                'tel'           => $tel,
            ];
            $res = db('xy_bankinfo')->where('id', $id)->update($data);
            if ($res !== false) {
                return $this->success('操作成功');
            } else {
                return $this->error('操作失败');
            }
        }
        $this->bk_info = Db::name('xy_bankinfo')->where('uid', input('id/d', 0))->select();
        if (!$this->bk_info) {
            $this->error('没有数据');
        }

        return $this->fetch();
    }

    public function level()
    {
        $this->title = '用户等级';
        $this->_query('xy_user_level')->page();
    }

    /**
     * 添加等级
     *@auth true
     *@menu true
     */
    public function add_level()
    {
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $name       = input('post.name/s', '');
            $level      = input('post.level/d', 0);
            $num        = input('post.num/s', '');
            $order_num  = input('post.order_num/s', '');
            $extended   = input('post.extended/a', []);
            $num_min    = input('post.num_min/s', '');
            $directions = input('post.directions/s', '');
            $res        = db('xy_user_level')->insert(
                [
                    'name'       => $name,
                    'level'      => $level,
                    'num'        => $num,
                    'order_num'  => $order_num,
                    'extended'   => json_encode($extended),
                    'num_min'    => $num_min,
                    'directions' => $directions,
                ]);
            if ($res !== false) {
                return $this->success('操作成功');
            } else {
                return $this->error('操作失败');
            }
        }
        return $this->fetch();
    }

    //
    public function edit_users_level()
    {
        $id = input('id/d', 0);
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $id         = input('post.id/d', 0);
            $name       = input('post.name/s', '');
            $level      = input('post.level/d', 0);
            $num        = input('post.num/s', '');
            $order_num  = input('post.order_num/s', '');
            $extended   = input('post.extended/a', []);
            $num_min    = input('post.num_min/s', '');
            $directions = input('post.directions/s', '');
            $res        = db('xy_user_level')->where('id', $id)->update(
                [
                    'name'       => $name,
                    'level'      => $level,
                    'num'        => $num,
                    'order_num'  => $order_num,
                    'extended'   => json_encode($extended),
                    'num_min'    => $num_min,
                    'directions' => $directions,
                ]);
            if ($res !== false) {
                return $this->success('操作成功');
            } else {
                return $this->error('操作失败');
            }
        }
        $info = UserLevelModel::where('id', $id)->find();
        if (empty($info)) {
            $this->error('没有数据');
        }
        return view('', ['info' => $info]);
    }

    public function delete_level()
    {
        $this->applyCsrfToken();
        $id  = input('post.id/d', 0);
        $res = UserLevelModel::delete($id);
        // $res = Db::table('xy_user_level')->where('id', $id)->delete();
        if ($res) {
            $this->success('删除成功!');
        } else {
            $this->error('删除失败!');
        }
    }

    public function send_msg()
    {
        $this->applyCsrfToken();
        $para = input('');
        $tpl  = db('system_message_tpl')->where(['name' => 'Order return', 'range' => getRange()])->cache(true)->find();
        $res  = Db::name('xy_message')->insert([
            'uid'     => $para['uid'],
            'type'    => 2,
            'title'   => $tpl['title'],
            'content' => sprintf($tpl['content'], $oid),
            'addtime' => time(),
        ]);
        if ($res !== false) {
            return $this->success('操作成功');
        } else {
            return $this->error('操作失败');
        }
    }

    public function user_divide()
    {
        $this->title = '分成比例';
        $this->_query('xy_user_divide')->page();
    }

    /**
     * 添加等级
     *@auth true
     *@menu true
     */
    public function add_user_divide()
    {
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $name       = input('post.name/s', '');
            $level      = input('post.level/d', 0);
            $num        = input('post.num/s', '');
            $order_num  = input('post.order_num/s', '');
            $bili       = input('post.bili/a', []);
            $match      = input('post.match/a', []);
            $limit      = input('post.limit/a', []);
            $withdraw   = input('post.withdraw/a', []);
            $num_min    = input('post.num_min/s', '');
            $directions = input('post.directions/s', '');
            $res        = db('xy_user_divide')->insert(
                [
                    'name'       => $name,
                    'level'      => $level,
                    'num'        => $num,
                    'order_num'  => $order_num,
                    'bili'       => json_encode($bili),
                    'match'      => json_encode($match),
                    'limit'      => json_encode($limit),
                    'withdraw'   => json_encode($withdraw),
                    'num_min'    => $num_min,
                    'directions' => $directions,
                ]);
            if ($res !== false) {
                return $this->success('操作成功');
            } else {
                return $this->error('操作失败');
            }
        }
        return $this->fetch();
    }

    //
    public function edit_user_divide()
    {
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $id         = input('post.id/d', 0);
            $name       = input('post.name/s', '');
            $level      = input('post.level/d', 0);
            $num        = input('post.num/s', '');
            $order_num  = input('post.order_num/s', '');
            $bili       = input('post.bili/a', []);
            $match      = input('post.match/a', []);
            $limit      = input('post.limit/a', []);
            $withdraw   = input('post.withdraw/a', []);
            $num_min    = input('post.num_min/s', '');
            $directions = input('post.directions/s', '');
            $res        = db('xy_user_divide')->where('id', $id)->update(
                [
                    'name'       => $name,
                    'level'      => $level,
                    'num'        => $num,
                    'order_num'  => $order_num,
                    'bili'       => json_encode($bili),
                    'match'      => json_encode($match),
                    'limit'      => json_encode($limit),
                    'withdraw'   => json_encode($withdraw),
                    'num_min'    => $num_min,
                    'directions' => $directions,
                ]);
            if ($res !== false) {
                return $this->success('操作成功');
            } else {
                return $this->error('操作失败');
            }
        }
        $info = Db::name('xy_user_divide')->where('id', input('id/d', 0))->find();
        if (empty($info)) {
            $this->error('没有数据');
        }
        if (!empty($info['bili'])) {
            $info['bili'] = json_decode($info['bili'], true);
        }
        if (!empty($info['match'])) {
            $info['match'] = json_decode($info['match'], true);
        }
        if (!empty($info['withdraw'])) {
            $info['withdraw'] = json_decode($info['withdraw'], true);
        }
        if (!empty($info['limit'])) {
            $info['limit'] = json_decode($info['limit'], true);
        }
        return view('', ['info' => $info]);
    }

    public function del_user_divide()
    {
        $this->applyCsrfToken();
        $id  = input('post.id/d', 0);
        $res = Db::table('xy_user_divide')->where('id', $id)->delete();
        if ($res) {
            $this->success('删除成功!');
        } else {
            $this->error('删除失败!');
        }
    }
}
