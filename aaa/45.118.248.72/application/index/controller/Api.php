<?php

namespace app\index\controller;

use app\common\model\Convey as ConveyModel;
use app\common\model\GoodsCate as GoodsCateModel;
use app\common\model\GoodsList as GoodsListModel;
use app\common\model\RewardLog as RewardLogModel;
use app\common\model\Users as UsersModel;
use app\common\validation\Validations;
use library\Controller;
use think\Db;
use think\facade\Cache;
use think\facade\Request;
use util\Time;

/**
 * 支付控制器
 */
class Api extends Controller
{

    public $BASE_URL  = "https://bapi.app";
    public $appKey    = '';
    public $appSecret = '';

    const POST_URL = "https://pay.bbbapi.com/";

    public function __construct()
    {
        $this->appKey    = config('app.bipay.appKey');
        $this->appSecret = config('app.bipay.appSecret');
    }

    public function auto_freeze()
    {
        $today = Time::today();
        $map   = [
            ['freeze_balance', '>', 0],
            ['status', '=', 1],
        ];
        $users = UsersModel::where($map)->field('id,username,balance,freeze_balance,recharge_total,deposit_total,deal_time,addtime')->select();
        $data  = [];
        foreach ($users as $key => $user) {
            $freeze_num = Db::table('xy_convey')->where('uid', $user['id'])->where('status', 5)->sum('num');
            if ($freeze_num == 0) {
                $commission = Db::table('xy_convey')->where('uid', $user['id'])->where('status', 1)->sum('commission');
                $cash       = Db::table('xy_reward_log')->where('uid', $user['id'])->where('status', 1)->where('type', 2)->sum('num');
                $recharge   = Db::table('xy_recharge')->where('uid', $user['id'])->where('status', 2)->where('is_vip', 0)->sum('num');
                $vip        = Db::table('xy_recharge')->where('uid', $user['id'])->where('status', 2)->where('is_vip', 1)->sum('num');
                $deposit    = Db::table('xy_deposit')->where('uid', $user['id'])->where('status', 2)->sum('num');
                $deposit_1  = Db::table('xy_deposit')->where('uid', $user['id'])->where('status', 1)->sum('num');

                $freeze_commission = Db::table('xy_convey')->where('uid', $user['id'])->where('status', 5)->sum('commission');
                $freeze_num        = Db::table('xy_convey')->where('uid', $user['id'])->where('status', 5)->sum('num');

                $balance        = $recharge + $cash + $commission - $deposit - $vip - $freeze_commission - $deposit_1;
                $freeze_balance = $freeze_commission + $freeze_num;
                $res            = Db::table('xy_users')->where('id', $user['id'])->update(['balance' => $balance, 'freeze_balance' => $freeze_balance]);

                $username        = $user['username'];
                $balance1        = $user['balance'];
                $freeze_balance1 = $user['freeze_balance'];
                $addtime         = date('Y-m-d H:i:s', $user['addtime']);
                $data[]          = "账号:$username;余额:$balance1;冻结:$freeze_balance1;注册时间:$addtime;充值:$recharge;提现(通过):$deposit;提现(处理中):$deposit_1;VIP花费:$vip;刷单收益:$commission;下线返利:$cash;处理后余额:$balance;";
            }
        }
        return json($data);
    }

    public function setmoney()
    {
        $user = [
            'balance'        => 9500,
            'freeze_balance' => 0,
            'level'          => 0,
            'base_level'     => 0,
        ];
        echo UsersModel::where('id', '>', 0)->update($user);
        echo UsersModel::where('id', '=', 16193)->update(['base_level' => 3]);
    }

    public function test()
    {
        $user = [
            'id'             => 16156,
            'balance'        => Db::raw('balance+1'),
            'freeze_balance' => Db::raw('freeze_balance-1'),
        ];
        // echo (new UsersModel)->where(['id' => 16156])->save($user, ['id' => 16156]);
        echo UsersModel::update($user);
    }

    public function auto()
    {
        $users = UsersModel::orderRaw('rand()')->where('balance', '>', 2000)->limit(30)->select();
        $data  = [];
        foreach ($users as $key => $user) {
            $uid = $user['id'];
            $tmp = (new Base)->check_deal($uid);
            if ($tmp) {
                $tmp['user'] = [
                    'id'             => $user['id'],
                    'username'       => $user['username'],
                    'balance'        => $user['balance'],
                    'freeze_balance' => $user['freeze_balance'],
                    'level'          => $user['level'],
                    'parent_id'      => $user['parent_id'],
                    'deal_status'    => $user['deal_status'],
                ];
                // $data[] = $tmp;
            } else {
                $add_id = db('xy_member_address')->where('uid', $uid)->where('is_default', 1)->value('id'); //获取收款地址信息
                if (empty($add_id)) {
                    db('xy_member_address')->insert([
                        'uid'        => $uid,
                        'name'       => 'a',
                        'tel'        => 'a',
                        'area'       => 'a',
                        'address'    => 'a',
                        'is_default' => 1,
                    ]);
                    $add_id = db('xy_member_address')->where('uid', $uid)->where('is_default', 1)->value('id');
                }
                //检查交易状态
                $res = db('xy_users')->where('id', $uid)->update(['deal_status' => 2]); //将账户状态改为等待交易
                // if ($res) {
                // if ($user['freeze_balance'] == 0) {
                $res = (new ConveyModel)->create_order($uid, 1);
                if ($res['code'] == 0) {
                    $oid    = $res['oid'];
                    $status = 1;
                    $oinfo  = db('xy_convey')
                        ->where('id', $oid)
                        ->find();

                    if ($oinfo) {
                        $res = (new ConveyModel)->do_order($oid, 0, $status, $uid, $add_id);
                        if ($res['code'] > 0) {
                            $res['data'] = [
                                $oid, $status, $uid, $add_id,
                            ];
                            $res['user'] = [
                                'id'             => $user['id'],
                                'username'       => $user['username'],
                                'balance'        => $user['balance'],
                                'freeze_balance' => $user['freeze_balance'],
                                'level'          => $user['level'],
                                'parent_id'      => $user['parent_id'],
                                'deal_status'    => $user['deal_status'],
                            ];
                            $data[] = $res;
                        }
                    }
                } else {
                    $res['user'] = [
                        'id'             => $user['id'],
                        'username'       => $user['username'],
                        'balance'        => $user['balance'],
                        'freeze_balance' => $user['freeze_balance'],
                        'level'          => $user['level'],
                        'parent_id'      => $user['parent_id'],
                        'deal_status'    => $user['deal_status'],
                    ];
                    $data[] = $res;
                }
                // } else {
                //     $res = [
                //         'info' => '冻结资金大于0',
                //         'user' => [
                //             'id'             => $user['id'],
                //             'username'       => $user['username'],
                //             'balance'        => $user['balance'],
                //             'freeze_balance' => $user['freeze_balance'],
                //             'deal_status'    => $user['deal_status'],
                //         ],
                //     ];
                //     $data[] = $res;
                // }
                // } else {
                //     $res = [
                //         'info' => '交易状态变更错误',
                //         'user' => [
                //             'id'             => $user['id'],
                //             'username'       => $user['username'],
                //             'balance'        => $user['balance'],
                //             'freeze_balance' => $user['freeze_balance'],
                //             'deal_status'    => $user['deal_status'],
                //         ],
                //     ];
                //     $data[] = $res;
                // }
            }
        }
        echo json_encode($data);
    }

    public function auto_order()
    {
        $uid = input('uid');
        if (empty($uid)) {
            $uid = session('user_id');
        }
        $time = time();
        $tmp  = (new Base)->check_deal($uid);
        if ($tmp) {
            return json($tmp);
        }

        if (!empty(config('time_start')) && !empty(config('time_end'))) {
            $se = Time::startEnd(config('time_start'), config('time_end'));
            if (($time < $se[0] || $time > $se[1])) {
                $vars = [
                    'time_start' => config('time_start'),
                    'time_end'   => config('time_end'),
                ];
                return json(['code' => 1, 'info' => lang('Grab order period', $vars)]);
            }
        }
        $add_id = db('xy_member_address')->where('uid', $uid)->where('is_default', 1)->value('id'); //获取收款地址信息
        if (!$add_id) {
            return json([
                'code' => 1,
                'info' => lang('No shipping address has been set'),
                'url'  => url('Ctrl/receive_site'),
            ]);
        }

        //检查交易状态
        // $sleep = mt_rand(config('min_time'),config('max_time'));
        $res = db('xy_users')->where('id', $uid)->update(['deal_status' => 2]); //将账户状态改为等待交易
        if ($res === false) {
            return json(['code' => 1, 'info' => lang('Rush order failure')]);
        }

        // session_write_close();//解决sleep造成的进程阻塞问题
        // sleep($sleep);
        //
        $cid   = input('post.cid/d', 1);
        $count = db('xy_goods_list')->where('cid', '=', $cid)->count();

        if ($count < 1) {
            return json(['code' => 1, 'info' => lang('Out of stock'), 'url' => url('index')]);
        }
        $user = db('xy_users')->field('id,balance,freeze_balance')->where('id', $uid)->find();
        // if ($user['freeze_balance'] != 0) {
        //     return json(['code' => 1, 'info' => lang('余额异常'), 'url' => url('index')]);
        // }

        $res = (new ConveyModel)->create_order($uid, $cid);
        if (empty($res['code'])) {
            $oid    = $res['oid'];
            $status = 1;
            $oinfo  = db('xy_convey')
                ->alias('xc')
                ->leftJoin('xy_member_address ar', 'ar.uid=xc.uid', 'ar.is_default=1')
                ->leftJoin('xy_goods_list xg', 'xg.id=xc.goods_id')
                ->leftJoin('xy_users u', 'u.id=xc.uid')
                ->field('xc.id oid,xc.commission,xc.num,xc.goods_count,xc.add_id,xg.goods_name,xg.goods_price,xg.shop_name,xg.goods_pic,ar.name,ar.tel,ar.address,u.balance')
                ->where('xc.id', $oid)
                ->where('xc.uid', $uid)
                ->find();

            if (!$oinfo) {
                return json(['code' => 1, lang('No data')]);
            }

            $add_id = $oinfo['add_id'];
            if (!\in_array($status, [1, 2])) {
                json(['code' => 1, 'info' => lang('Parameter error'), 'url' => url('index')]);
            }

            $res         = (new ConveyModel)->do_order($oid, 0, $status, $uid, $add_id);
            $res['u']    = $user;
            $res['user'] = db('xy_users')->where('id', $uid)->field('id,balance,freeze_balance,level,parent_id')->find();
        }
        return json($res);
    }

    public function getlevel()
    {
        $l = input('l', null);
        echo json_encode(get_levels($l));
    }

    public function setbalance()
    {
        $start = 0;
        $limit = 50;
        $map   = [
            ['type', '=', 6],
        ];
        do {
            $logs = RewardLogModel::where($map)->select();
            foreach ($logs as $key => $value) {
                $balance_log = [
                    'uid'     => $value['uid'],
                    'sid'     => $value['sid'],
                    'oid'     => $value['oid'],
                    'num'     => $value['num'],
                    'type'    => $value['type'],
                    'status'  => $value['status'],
                    'addtime' => $value['addtime'],
                ];
                // 启动事务
                Db::startTrans();
                $res = Db::name('xy_balance_log')->data($balance_log)->insert();
                $del = Db::name('xy_reward_log')->delete($value['id']);
                if ($res && $del) {
                    // 提交事务
                    Db::commit();
                    echo '成功;訂單號:', $value['oid'], '<br />';
                } else {
                    // 回滚事务
                    Db::rollback();
                    echo '失败;訂單號:', $value['oid'], '<br />';
                }
            }
            $start += $limit;
        } while (count($logs) == $limit);
    }

    public function addlang()
    {
        if (Request::isPost()) {
            $para = input('');
            if (!empty($para['content'])) {
                $data = [];
                foreach ($para['content'] as $key => $value) {
                    $lang = [
                        'type'       => $para['type'],
                        'position'   => $para['position'],
                        'range'      => $key,
                        'name'       => $para['name'],
                        'content'    => $value,
                        'directions' => $para['directions'],
                    ];
                    $data[] = $lang;
                }
                $l = new LangModel;
                if ($l->saveAll($data)) {
                    Cache::clear();
                    $this->success('操作成功');
                } else {
                    $this->error('操作失败');
                }
            }
            $this->error('数据错误');
        }
        return view('');
        // $type     = input('type', 0);
        // $name     = input('name');
        // $content  = input('content');
        // $position = input('position');
        // $r        = Db::table('system_lang')->insert(['type' => $type, 'name' => $name, 'content' => $content, 'position' => $position, 'range' => 'zh-cn', 'directions' => '']);
        // $r        = Db::table('system_lang')->insert(['type' => $type, 'name' => $name, 'content' => $content, 'position' => $position, 'range' => 'ja-jp', 'directions' => '']);
        // Cache::clear();
    }

    public function parent_user()
    {
        $uid     = input('uid');
        $parents = (new UsersModel)->parent_user($uid, 5);
        print_r($parents);
    }

    public function setparents()
    {
        $map = [
            ['child_level', '=', 0],
            ['parent_first', '=', 0],
        ];
        $users = UsersModel::where($map)->select();
        foreach ($users as $key => $value) {
            $parent = getParents($value['id']);
            echo $value['id'], ';';
            if (count($parent) > 1) {
                $parent_first = end($parent)['id'];
                $data         = [
                    'id'           => $value['id'],
                    'parent_first' => $parent_first,
                    'child_level'  => count($parent) - 1,
                ];
                UsersModel::update($data);
            } else {
                $data = [
                    'id'           => $value['id'],
                    'parent_first' => 0,
                    'child_level'  => 0,
                ];
                UsersModel::update($data);
            }
        }
    }

    public function syncgoods()
    {
        $params = input('');
        if (empty($params['page'])) {
            $params['page'] = 1;
        }
        if (empty($params['list_rows'])) {
            $params['list_rows'] = 30;
        }
        $cates          = GoodsCateModel::select()->toArray();
        $lists          = GoodsListModel::order('id ASC')->paginate($params)->toArray();
        $lists['cates'] = $cates;
        return json($lists);
    }

    public function moneys()
    {
        $uid      = input('uid/d', 0);
        $username = input('u/s', '');
        $set      = input('set/d', 0);
        if (empty($uid) && empty($username)) {
            $this->error('用户名或ID为空');
        }
        $map = ['id' => $uid];
        if (!empty($username)) {
            $map = ['username' => $username];
        }
        $user = Db::table('xy_users')->field('id,username,balance,freeze_balance,recharge_total,deposit_total,deal_time,addtime')->where($map)->find();
        if (empty($user)) {
            $this->error($username . ' -> User does not exist');
        }
        $today            = Time::today();
        $commission_today = Db::table('xy_convey')->where('uid', $user['id'])->where('status', 1)->where('addtime', 'between', $today)->sum('commission');
        $cash_today       = Db::table('xy_reward_log')->where('uid', $user['id'])->where('status', 1)->where('addtime', 'between', $today)->where('type', 2)->sum('num');
        $commission       = Db::table('xy_convey')->where('uid', $user['id'])->where('status', 1)->sum('commission');
        $cash             = Db::table('xy_reward_log')->where('uid', $user['id'])->where('status', 1)->where('type', 2)->sum('num');
        $recharge         = Db::table('xy_recharge')->where('uid', $user['id'])->where('status', 2)->where('is_vip', 0)->sum('num');
        $vip              = Db::table('xy_recharge')->where('uid', $user['id'])->where('status', 2)->where('is_vip', 1)->sum('num');
        $deposit          = Db::table('xy_deposit')->where('uid', $user['id'])->where('status', 2)->sum('num');
        $deposit_1        = Db::table('xy_deposit')->where('uid', $user['id'])->where('status', 1)->sum('num');

        $freeze_commission = Db::table('xy_convey')->where('uid', $user['id'])->where('status', 5)->sum('commission');
        $freeze_num        = Db::table('xy_convey')->where('uid', $user['id'])->where('status', 5)->sum('num');

        $balance        = $recharge + $cash + $commission - $deposit - $vip - $freeze_commission - $deposit_1;
        $freeze_balance = $freeze_commission + $freeze_num;
        if ($set > 0) {
            $res = Db::table('xy_users')->where('id', $user['id'])->update(['balance' => $balance, 'freeze_balance' => $freeze_balance]);
        }
        $user1 = Db::table('xy_users')->field('id,username,balance,freeze_balance,recharge_total,deposit_total')->where(['id' => $user['id']])->find();
        // $conveys    = Db::table('xy_convey')->where('uid', $user['id'])->order('addtime DESC')->select();
        // $reward_log = Db::table('xy_reward_log')->where('uid', $user['id'])->order('addtime DESC')->select();

        // $str1 = 'UPDATE `xy_users` SET `balance` = %1s, `freeze_balance` = 0 WHERE `id` = %2s;';
        // $str2 = 'SELECT `id`, `username`, `balance`, `freeze_balance`, `status` FROM `xy_users` ORDER BY `xy_users`.`freeze_balance` DESC;';
        // $str3 = 'SELECT * FROM `xy_convey` WHERE uid = %1s ORDER BY `addtime`  DESC;';
        // $str4 = 'SELECT * FROM `xy_reward_log` WHERE `uid` = %1s ORDER BY `addtime`  DESC;';

        $data = [
            'user'             => $user,
            'commission_today' => $commission_today,
            'cash_today'       => $cash_today,
            'commission'       => $commission,
            'cash'             => $cash,
            'recharge'         => $recharge,
            'deposit'          => $deposit,
            'deposit_1'        => $deposit_1,
            'vip'              => $vip,
            'balance'          => $balance,
            'freeze_balance'   => $freeze_balance,
            'deal_time'        => date('Y-m-d H:i:s', $user['deal_time']),
            'addtime'          => date('Y-m-d H:i:s', $user['addtime']),
            'over'             => [
                'user' => $user1,
                // 'conveys'    => $conveys,
                // 'reward_log' => $reward_log,
            ],
            'msg'              => '账号:' . $user['username'] . ' 注册时间:' . date('Y-m-d H:i:s', $user['addtime']) . ' 充值:' . $recharge . ' 提现(通过):' . $deposit . ' 提现(处理中):' . $deposit_1 . ' VIP:' . $vip . ' 刷单收益:' . $commission . ' 下线返利:' . $cash . ' 处理后余额:' . $balance,
        ];
        return json($data);
    }

    public function syncgood()
    {
        # code...
    }

    //发送验证码
    /**
     * [send_msg 发送验证码]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function send_msg()
    {
        $params = input('');
        if (empty($params['type']) || $params['type'] == 0) {
            try {
                // 验证（如果验证不通过，会抛出异常）
                $Validations = new Validations();
                $verify      = $Validations::validate($params, [
                    // 此处验证规则
                    // 参数 account 应该为 email
                    'username' => 'Email|Alias:' . lang('username', [], '', 3),
                ]);
            } catch (\Exception $e) {
                return json(['code' => 1, 'info' => $e->getMessage()]);
            }
        } else {
            if (!extract_phone_number($params['username'], config('system.mobile_area'))) {
                return json(['code' => 1, 'info' => lang('Mobile number format is incorrect')]);
            }
        }
        if (isset($params['new']) && $params['new'] == 1) {
            $num = Db::table('xy_users')->where(['username' => $params['username']])->find();
            if ($num) {
                return json(['code' => 1, 'info' => lang('Account already exists')]);
            }
        }
        $res = Db::table('xy_verify_msg')->field('addtime,username')->where(['username' => $params['username']])->find();
        if ($res && (($res['addtime'] + sysconf('verify_secondary')) > time())) {
            return json(['code' => 1, 'info' => lang('message limit', [sysconf('verify_secondary')])]);
        }

        $time = date('YmdHis', time());
        $num  = rand(10000, 99999);
        $tpl  = get_msg_tpl('verification msg');
        if (!empty($params['type'])) {
            $result = $this->smsjian($tel, $num);
        } else {
            $result = send_mail(sys_config(), $params['username'], $params['username'], $tpl['title'], _sprintf($tpl['content'], [sysconf('site_name'), $num, sysconf('verify_time')]));
        }

        if ($result == 1) {
            //发送成功
            if (!$res) {
                $r = Db::table('xy_verify_msg')->insert(['username' => $params['username'], 'is_mobile' => $params['type'], 'msg' => $num, 'addtime' => time(), 'type' => $params['new']]);
            } else {
                $r = Db::table('xy_verify_msg')->where(['username' => $params['username']])->data(['msg' => $num, 'addtime' => time(), 'type' => $params['new']])->update();
            }
            if ($r) {
                return json(['code' => 0, 'info' => lang('Sent successfully')]);
            } else {
                return json(['code' => 1, 'info' => lang('Failed to send')]);
            }

        } else {
            return $result;
        }
    }

    public function bipay()
    {

        $oid = isset($_REQUEST['oid']) ? $_REQUEST['oid'] : '';
        if ($oid) {
            $r = db('xy_recharge')->where('id', $oid)->find();
            if ($r) {
                $server_url = $_SERVER['SERVER_NAME'] ? "http://" . $_SERVER['SERVER_NAME'] : "http://" . $_SERVER['HTTP_HOST'];
                $notifyUrl  = $server_url . url('/index/api/bipay_notify');
                $returnUrl  = $server_url . url('/index/api/bipay_return');
                $price      = $r['num'] * 100;
                $res        = $this->create_order($oid, $price, '用户充值', $notifyUrl, $returnUrl);

                if ($res && $res['code'] == 200) {
                    $url = $res['data']['pay_url'];
                    $this->redirect($url);
                }
            }
        }
    }

    public function bipay_return()
    {
        return $this->fetch();
    }

    public function bipay_notify()
    {

        $content = file_get_contents('php://input');
        $post    = (array) json_decode($content, true);
        file_put_contents("bipay_notify.log", $content . "\r\n", FILE_APPEND);

        if (!$post['order_id']) {
            die('fail');
        }
        $oid = $post['order_id'];
        $r   = db('xy_recharge')->where('id', $oid)->find();
        if (!$r) {
            die('fail');
        }
        if ($post['order_state'] != 1) {
            die('fail');
        }

        if ($r['status'] == 2) {
            die('SUCCESS');
        }

        if ($post['order_state']) {
            $res   = Db::name('xy_recharge')->where('id', $oid)->update(['endtime' => time(), 'status' => 2]);
            $oinfo = $r;
            $res1  = Db::name('xy_users')->where('id', $oinfo['uid'])->setInc('balance', $oinfo['num']);
            $res2  = Db::name('xy_balance_log')
                ->insert([
                    'uid'     => $oinfo['uid'],
                    'oid'     => $oid,
                    'num'     => $oinfo['num'],
                    'type'    => 1,
                    'status'  => 1,
                    'addtime' => time(),
                ]);
            /************* 发放推广奖励 *********/
            $uinfo = Db::name('xy_users')->field('id,active')->find($oinfo['uid']);
            if ($uinfo['active'] === 0) {
                Db::name('xy_users')->where('id', $uinfo['id'])->update(['active' => 1]);
                //将账号状态改为已发放推广奖励
                $userList = model('common/Users')->parent_user($uinfo['id'], 3);
                if ($userList) {
                    foreach ($userList as $v) {
                        if ($v['status'] === 1 && ($oinfo['num'] * config($v['lv'] . '_reward') != 0)) {
                            Db::name('xy_reward_log')
                                ->insert([
                                    'uid'     => $v['id'],
                                    'sid'     => $uinfo['id'],
                                    'oid'     => $oid,
                                    'num'     => $oinfo['num'] * config($v['lv'] . '_reward'),
                                    'lv'      => $v['lv'],
                                    'type'    => 1,
                                    'status'  => 1,
                                    'addtime' => time(),
                                ]);
                        }
                    }
                }
            }
            /************* 发放推广奖励 *********/
            die('SUCCESS');
        }
    }

    public function create_order(
        $orderId, $amount, $body, $notifyUrl, $returnUrl, $extra = '', $orderIp = '', $amountType = 'CNY', $lang = 'zh_CN') {
        $reqParam = [
            'order_id'    => $orderId,
            'amount'      => $amount,
            'body'        => $body,
            'notify_url'  => $notifyUrl,
            'return_url'  => $returnUrl,
            'extra'       => $extra,
            'order_ip'    => $orderIp,
            'amount_type' => $amountType,
            'time'        => time() * 1000,
            'app_key'     => $this->appKey,
            'lang'        => $lang,
        ];
        $reqParam['sign'] = $this->create_sign($reqParam, $this->appSecret);
        $url              = $this->BASE_URL . '/api/v2/pay';

        return $this->http_request($url, 'POST', $reqParam);
    }

    /**
     * @return {
     * bapp_id: "2019081308272299266f",
     * order_id: "1565684838",
     * order_state: 0,
     * body: "php-sdk sample",
     * notify_url: "https://sdk.b.app/api/test/notify/test",
     * order_ip: "",
     * amount: 1,
     * amount_type: "CNY",
     * amount_btc: 0,
     * pay_time: 0,
     * create_time: 1565684842076,
     * order_type: 2,
     * app_key: "your_app_key",
     * extra: ""
     * }
     */
    public function get_order($orderId)
    {
        $reqParam = [
            'order_id' => $orderId,
            'time'     => time() * 1000,
            'app_key'  => $this->appKey,
        ];
        $reqParam['sign'] = $this->create_sign($reqParam, $this->appSecret);
        $url              = $this->BASE_URL . '/api/v2/order';
        return $this->http_request($url, 'GET', $reqParam);
    }

    public function is_sign_ok($params)
    {
        $sign = $this->create_sign($params, $this->appSecret);
        return $params['sign'] == $sign;
    }

    public function create_sign($params, $appSecret)
    {
        $signOriginStr = '';
        ksort($params);
        foreach ($params as $key => $value) {
            if (empty($key) || $key == 'sign') {
                continue;
            }
            $signOriginStr = "$signOriginStr$key=$value&";
        }
        return strtolower(md5($signOriginStr . "app_secret=$appSecret"));
    }

    private function http_request($url, $method = 'GET', $params = [])
    {
        $curl = curl_init();

        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
            $jsonStr = json_encode($params);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonStr);
        } else if ($method == 'GET') {
            $url = $url . "?" . http_build_query($params, '', '&');
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $output = curl_exec($curl);

        if (curl_errno($curl) > 0) {
            return [];
        }
        curl_close($curl);
        $json = json_decode($output, true);

        //var_dump($output,curl_errno($curl));die;

        return $json;
    }

    //----------------------------------------------------------------
    //  paysapi
    //----------------------------------------------------------------

    public function pay()
    {

        $oid = isset($_REQUEST['oid']) ? $_REQUEST['oid'] : '';
        if ($oid) {
            $r = db('xy_recharge')->where('id', $oid)->find();
            if ($r) {

                //var_dump($r);die;

                $server_url = $_SERVER['SERVER_NAME'] ? "http://" . $_SERVER['SERVER_NAME'] : "http://" . $_SERVER['HTTP_HOST'];
                $notify_url = $server_url . url('/index/api/pay_notify');
                $return_url = $server_url . url('/index/api/bipay_return');
                $price      = $r['num'] * 100;

                $uid   = config('app.paysapi.uid'); //"此处填写Yipay的uid";
                $token = config('app.paysapi.token'); //"此处填写Yipay的Token";

                $orderid   = $r['id'];
                $goodsname = '用户充值';
                $istype    = config('app.paysapi.istype');
                $orderuid  = session('user_id');

                $key = md5($goodsname . $istype . $notify_url . $orderid . $orderuid . $price . $return_url . $token . $uid);

                $data = array(
                    'goodsname'  => $goodsname,
                    'istype'     => $istype,
                    'key'        => $key,
                    'notify_url' => $notify_url,
                    'orderid'    => $orderid,
                    'orderuid'   => $orderuid,
                    'price'      => $price,
                    'return_url' => $return_url,
                    'uid'        => $uid,
                );
                $this->assign('data', $data);
                $this->assign('post_url', self::POST_URL);
                return $this->fetch();
            }
        }

    }

    /**
     * notify_url接收页面
     */
    public function pay_notify()
    {

        $paysapi_id = $_POST["paysapi_id"];
        $orderid    = $_POST["orderid"];
        $price      = $_POST["price"];
        $realprice  = $_POST["realprice"];
        $orderuid   = $_POST["orderuid"];
        $key        = $_POST["key"];

        file_put_contents(RUNTIME_PATH . '/paysapi_notify.log', json_encode($_REQUEST) . "\r\n", FILE_APPEND);

        //校验传入的参数是否格式正确，略
        $d = $payType = array();
        if ($orderid) {
            $out_trade_no = $orderid;
            //$res = Db::name('xy_recharge')->where('id',$oid)->update(['endtime'=>time(),'status'=>2]);

            //$d = M('recharge')->where(array('order_no'=>$out_trade_no))->find();
            //$payType = M('pay_type')->find($d['payment_type']);

        }
        $token = config('app.paysapi.token');
        $temps = md5($orderid . $orderuid . $paysapi_id . $price . $realprice . $token);

        if ($temps != $key) {
            return exit("key值不匹配");
        } else {
            //校验key成功
            $oid   = $orderid;
            $r     = db('xy_recharge')->where('id', $oid)->find();
            $res   = Db::name('xy_recharge')->where('id', $oid)->update(['endtime' => time(), 'status' => 2]);
            $oinfo = $r;
            $res1  = Db::name('xy_users')->where('id', $oinfo['uid'])->setInc('balance', $oinfo['num']);
            $res2  = Db::name('xy_balance_log')
                ->insert([
                    'uid'     => $oinfo['uid'],
                    'oid'     => $oid,
                    'num'     => $oinfo['num'],
                    'type'    => 1,
                    'status'  => 1,
                    'addtime' => time(),
                ]);
            /************* 发放推广奖励 *********/
            $uinfo = Db::name('xy_users')->field('id,active')->find($oinfo['uid']);
            if ($uinfo['active'] === 0) {
                Db::name('xy_users')->where('id', $uinfo['id'])->update(['active' => 1]);
                //将账号状态改为已发放推广奖励
                $userList = model('common/Users')->parent_user($uinfo['id'], 3);
                if ($userList) {
                    foreach ($userList as $v) {
                        if ($v['status'] === 1 && ($oinfo['num'] * config($v['lv'] . '_reward') != 0)) {
                            Db::name('xy_reward_log')
                                ->insert([
                                    'uid'     => $v['id'],
                                    'sid'     => $uinfo['id'],
                                    'oid'     => $oid,
                                    'num'     => $oinfo['num'] * config($v['lv'] . '_reward'),
                                    'lv'      => $v['lv'],
                                    'type'    => 1,
                                    'status'  => 1,
                                    'addtime' => time(),
                                ]);
                        }
                    }
                }
            }
            /************* 发放推广奖励 *********/
            die('SUCCESS');
        }
    }
}
