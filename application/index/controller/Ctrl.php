<?php

namespace app\index\controller;

use app\common\model\Bankinfo as BankinfoModel;
use app\common\model\Convey as ConveyModel;
use app\common\model\Deposit as DepositModel;
use app\common\model\Pay as PayModel;
use app\common\model\Users as UsersModel;
use think\Controller;
use think\Db;
use think\Request;
use util\Time;

class Ctrl extends Base
{
    //钱包页面
    public function wallet()
    {
        $uid      = $this->user['id'];
        $shouyi   = [];
        $chongzhi = [];
        $tixian   = [];
        $balance  = '';
        $balanceT = 0;

        if ($uid > 0) {
            $balance  = db('xy_users')->where('id', $uid)->value('balance');
            $balanceT = db('xy_convey')->where('uid', $uid)->where('status', 1)->sum('commission');
            //收益
            $today  = Time::today();
            $shouyi = db('xy_convey')
                ->where('uid', $uid)
                ->where('addtime', '>', $today[0])
                ->order('addtime DESC')
                ->select();
            //充值
            $chongzhi = db('xy_recharge')
                ->where('uid', $uid)
                // ->where('addtime', '>', $today[0])
                // ->where('status', 2)
                ->limit(20)
                ->order('addtime DESC')
                ->select();
            //提现
            $tixian = db('xy_deposit')
                ->where('uid', $uid)
                // ->where('addtime', '>', $today[0])
                // ->where('status', 1)
                ->limit(20)
                ->order('addtime DESC')
                ->select();
        }
        $this->assign('balance', $balance);
        $this->assign('balance_shouru', $balanceT);
        $this->assign('shouyi', $shouyi);
        $this->assign('chongzhi', $chongzhi);
        $this->assign('tixian', $tixian);
        return $this->fetch();
    }

    public function vip()
    {
        $list = [];
        $pay  = db('xy_pay')->where(['status' => 1, 'recharge' => 1])->select();
        if (sys_config('vip_type') == 0) {
            $list[] =
                [
                'id'       => 0,
                'ico_type' => 1,
                'ico'      => '<i class="fa fa-money" style="color: #A406A2;" aria-hidden="true"></i>',
                'name'     => lang('Balance payment'),
            ];
        } else {
            foreach ($pay as $key => $value) {
                $list[] = $value;
            }
        }

        $member_level = get_levels();
        $info         = db('xy_users')->where('id', $this->user['id'])->find();
        $level_name   = $member_level[0]['name'];
        $order_num    = $member_level[0]['order_num'];
        $level        = get_levels($info['level']);
        if (!empty($info['level'])) {
            $level_name = $level['name'];
        }
        if (!empty($info['order_num'])) {
            $order_num = $level['order_num'];
        }

        return view('', ['member_level' => $member_level, 'info' => $info, 'level_name' => $level_name, 'order_num' => $order_num, 'list' => $list]);
    }

    //钱包页面
    public function bank()
    {
        $uid     = $this->user['id'];
        $balance = UsersModel::where('id', $uid)->value('balance');
        $this->assign('balance', $balance);
        $map = [
            ['uid', '=', $uid],
            ['status', '=', 2],
        ];
        $balanceT = ConveyModel::where($map)->sum('commission');
        $this->assign('balance_shouru', $balanceT);
        return $this->fetch();
    }

    //获取提现订单接口
    public function get_deposit()
    {
        $info = db('xy_deposit')->where('uid', $this->user['id'])->order('addtime DESC')->limit(15)->select();
        if ($info) {
            return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $info]);
        }
        return json(['code' => 1, 'info' => lang('No data')]);
    }

    public function deposit_before()
    {
        $pay = db('xy_pay')->where(['recharge' => 0, 'status' => 1])->select();
        $this->assign('pay', $pay);
        return $this->fetch();
    }

    public function deposit($id)
    {
        $this->assign('id', $id);
        return $this->fetch();
    }

    public function deposit_sco($id)
    {
        $id = intval($id);
        if (empty($id)) {
            return json(['code' => 1, 'info' => lang('Submission failed')]);
        }
        $info = db('xy_pay')->find($id);
        if (empty($info)) {
            return json(['code' => 1, 'info' => lang('Submission failed')]);
        }
        $user = db('xy_users')->where('id', $this->user['id'])->find();
        return view('', ['info' => $info, 'user' => $user, 'id' => $id]);
    }

    public function deposit_wx($id)
    {
        $user = db('xy_users')->where('id', $this->user['id'])->find();
        $this->assign('title', '微信提现');

        $this->assign('type', 'wx');
        $this->assign('user', $user);
        return $this->fetch();
    }

    public function deposit_zfb($id)
    {

        $user = db('xy_users')->where('id', $this->user['id'])->find();
        $this->assign('title', '支付宝提现');

        $this->assign('type', 'zfb');
        $this->assign('user', $user);
        return $this->fetch('deposit_zfb');
    }

    //提现接口
    public function do_deposit()
    {
        $payid = input('pay_id/d', 0);
        $today = Time::today();
        if (empty($payid)) {
            return json(['code' => 1, 'info' => lang('Parameter error')]);
        }
        $time = time();
        $map  = [
            ['id', '=', $payid],
            ['status', '=', 1],
        ];
        $pay = PayModel::where($map)->find()->toArray();
        if (empty($pay)) {
            return json(['code' => 1, 'info' => lang('Cannot withdraw in the current way'), 'url' => url('deposit_before')]);
        }
        $map = [
            ['uid', '=', $this->user['id']],
            ['status', '=', 1],
        ];
        if ($pay && $pay['withdrawal_user']) {
            $bankinfo = BankinfoModel::where($map)->find();
            if (!$bankinfo) {
                return json(['code' => 1, 'info' => lang('No bank card information has been added'), 'url' => url('bank')]);
            }
            $bankinfo['pay'] = $pay ?? [];
        }

        if (!empty($pay['time_start']) && !empty($pay['time_end'])) {
            $se = Time::startEnd($pay['time_start'], $pay['time_end']);
            if ($time < $se[0] || $time > $se[1]) {
                return json(['code' => 1, 'info' => lang('Withdrawal time', $pay), 'url' => url('deposit_before')]);
            }
        }
        $map = [
            ['uid', '=', $this->user['id']],
            ['status', '=', 1],
            ['addtime', 'between', $today],
        ];
        $r = DepositModel::where($map)->find();
        if (!empty($r)) {
            return json(['code' => 1, 'info' => lang('Withdrawal exists for unprocessed orders'), 'url' => url('deposit_admin')]);
        }

        if (request()->isPost()) {
            $uid      = $this->user['id'];
            $num      = input('post.num/d', 0);
            $bkid     = input('post.bk_id/d', 0);
            $payid    = input('post.pay_id/d', 0);
            $type     = input('post.type/s', '');
            $token    = input('post.token', '');
            $data     = ['__token__' => $token];
            $validate = \Validate::make($this->rule, $this->msg);
            if (!$validate->check($data)) {
                return json(['code' => 1, 'info' => $validate->getError()]);
            }

            $pic  = input('pic/s', '');
            $pic2 = input('pic2/s', '');
            if (is_image_base64($pic)) {
                // ID card
                $pic = '/' . $this->upload_base64('7310da7e7015ec7cd1e83f324c7c030d', $pic);
            }
            if (is_image_base64($pic2)) {
                // ID card
                $pic2 = '/' . $this->upload_base64('7310da7e7015ec7cd1e83f324c7c030d', $pic2);
            }
            $uinfo = Db::name('xy_users')->field('deal_time,balance,level')->find($uid); //获取用户今日已充值金额
            //提现限制
            $level    = $uinfo['level'];
            $ulevel   = get_levels($level);
            $withdraw = $ulevel['extended'];
            if (!empty($withdraw) && ($num < $withdraw['withdraw_min'] || $num > $withdraw['withdraw_max'])) {
                return ['code' => 1, 'info' => lang('Current withdrawal limit', $withdraw)];
            }
            if (!empty($pay) && ($num < $pay['min'] || $num > $pay['max'])) {
                return ['code' => 1, 'info' => lang('Current withdrawal limit pay', $pay)];
            }
            // if ($payid == 9) {
            //     $n = $num / 1000;
            //     if (floor($n) != $n) {
            //         return ['code' => 1, 'info' => lang('Withdrawal multiple of 1000 can be withdrawn')];
            //     }
            // }

            if ($num > $uinfo['balance']) {
                return json(['code' => 1, 'info' => lang('Insufficient balance')]);
            }
            // 手续费
            $handling_fee = 0;
            if (!empty($pay['handling_fee'])) {
                if ((!empty($pay['handling_fee_limit']) && $num <= $pay['handling_fee_limit']) || $pay['handling_fee_limit'] === 0) {
                    if ($pay['handling_fee_type'] > 0) {
                        $handling_fee = $num * $pay['handling_fee'] / 100;
                    } else {
                        $handling_fee = $pay['handling_fee'];
                    }
                }
            }
            // 实际到账金额
            $arrival = $num - $handling_fee;

            if ($uinfo['deal_time'] == strtotime(date('Y-m-d'))) {
                //提现次数限制
                $tixianCi = db('xy_deposit')->where('uid', $uid)->where('status', 2)->where('addtime', 'between', Time::today())->count();
                if (!empty($withdraw) && ($tixianCi + 1 > $withdraw['withdraw_num'])) {
                    return ['code' => 1, 'info' => lang('Current withdrawals')];
                }

            } else {
                //重置最后交易时间
                Db::name('xy_users')->where('id', $uid)->update(['deal_time' => strtotime(date('Y-m-d')), 'deal_count' => 0]);
            }
            $id = getSn('CO');
            try {
                Db::startTrans();
                $insert = [
                    'id'           => $id,
                    'uid'          => $uid,
                    'bk_id'        => $bkid,
                    'pay_id'       => $payid,
                    'num'          => $num,
                    'type'         => $type,
                    'arrival'      => $arrival,
                    'handling_fee' => $handling_fee,
                    'pic'          => $pic,
                    'pic2'         => $pic2,
                ];
                $res  = DepositModel::create($insert);
                $res1 = UsersModel::where('id', $this->user['id'])->update(['balance' => Db::raw('balance-' . $num)]);
                // $res1 = Db::name('xy_users')->where('id', $this->user['id'])->setDec('balance', $num);
                if ($res && $res1) {
                    Db::commit();
                    return json(['code' => 0, 'info' => lang('Successful operation')]);
                } else {
                    Db::rollback();
                    return json(['code' => 1, 'info' => lang('operation failed')]);
                }
            } catch (\Exception $e) {
                Db::rollback();
                return json(['code' => 1, 'info' => lang('Please check your account balance')]);
            }
        }
        return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $bankinfo ?? [], 'url' => url('deposit_admin')]);
    }

    //////get请求获取参数，post请求写入数据，post请求传人bkid则更新数据//////////
    public function do_bankinfo()
    {
        if (request()->isPost()) {
            $token    = input('post.token', '');
            $data     = ['__token__' => $token];
            $validate = \Validate::make($this->rule, $this->msg);
            if (!$validate->check($data)) {
                return json(['code' => 1, 'info' => $validate->getError()]);
            }

            $bank_name     = input('post.bank_name/s', '');
            $branch_name   = input('post.branch_name/s', '');
            $branch_number = input('post.branch_number/s', '');
            $card_number   = input('post.card_number/s', '');
            $name_e        = input('post.name_e/s', '');
            $name          = input('post.name/s', '');
            $tel           = input('post.tel/s', '');
            $status        = input('post.default/d', 0);
            $bkid          = input('post.bkid/d', 0); //是否为更新数据

            if (!$name) {
                return json(['code' => 1, 'info' => lang('Please enter account name')]);
            }

            if (mb_strlen($name) > 30) {
                return json(['code' => 1, 'info' => lang('Maximum length of account holder name')]);
            }

            if (!$bank_name) {
                return json(['code' => 1, 'info' => lang('Please enter the account bank')]);
            }

            if (!$branch_name) {
                return json(['code' => 1, 'info' => lang('Please enter a branch name')]);
            }

            if (!$branch_number) {
                return json(['code' => 1, 'info' => lang('Please enter a branch number')]);
            }

            if (!$card_number) {
                return json(['code' => 1, 'info' => lang('Please enter your bank card number')]);
            }

            if (!$tel) {
                return json(['code' => 1, 'info' => lang('Mobile number is required')]);
            }

            if ($bkid) {
                $cardn = Db::table('xy_bankinfo')->where('id', '<>', $bkid)->where('card_number', $card_number)->count();
            } else {
                $cardn = Db::table('xy_bankinfo')->where('card_number', $card_number)->count();
            }

            if ($cardn) {
                return json(['code' => 1, 'info' => lang('Bank card number already exists')]);
            }

            $data = [
                'uid'           => $this->user['id'],
                'bank_name'     => $bank_name,
                'branch_name'   => $branch_name,
                'branch_number' => $branch_number,
                'card_number'   => $card_number,
                'name_e'        => $name_e,
                'name'          => $name,
                'status'        => $status,
                'tel'           => $tel,
            ];
            if ($status) {
                Db::table('xy_bankinfo')->where(['uid' => $this->user['id']])->update(['status' => 0]);
                $data['status'] = 1;
            }

            if ($bkid) {
                $res = Db::table('xy_bankinfo')->where('id', $bkid)->where('uid', $this->user['id'])->update($data);
            } else {
                $res = Db::table('xy_bankinfo')->insert($data);
            }

            if ($res !== false) {
                return json(['code' => 0, 'info' => lang('Successful operation')]);
            } else {
                return json(['code' => 1, 'info' => lang('operation failed')]);
            }
        }
        $bkid  = input('id/d', 0); //是否为更新数据
        $where = ['uid' => $this->user['id']];
        if ($bkid !== 0) {
            $where['id'] = $bkid;
        }

        $info = db('xy_bankinfo')->where($where)->select();
        if (!$info) {
            return json(['code' => 1, 'info' => lang('No data')]);
        }

        return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $info]);
    }

    //切换银行卡状态
    public function edit_bankinfo_status()
    {
        $id = input('post.id/d', 0);

        Db::table('bankinfo')->where(['uid' => $this->user['id']])->update(['status' => 0]);
        $res = Db::table('bankinfo')->where(['id' => $id, 'uid' => $this->user['id']])->update(['status' => 1]);
        if ($res !== false) {
            return json(['code' => 0, 'info' => lang('Successful operation')]);
        } else {
            return json(['code' => 1, 'info' => lang('operation failed')]);
        }

    }

    //获取下级会员
    public function bot_user()
    {
        if (request()->isPost()) {
            $uid      = input('post.id/d', 0);
            $token    = ['__token__' => input('post.token', '')];
            $validate = \Validate::make($this->rule, $this->msg);
            if (!$validate->check($token)) {
                return json(['code' => 1, 'info' => $validate->getError()]);
            }

        } else {
            $uid = $this->user['id'];
        }
        $page = input('page/d', 1);
        $num  = input('num/d', 10);
        $data = db('xy_users')->where('parent_id', $uid)->field('id,username,headpic,addtime,childs,tel')->limit(($page - 1) * $num, $num)->order('addtime desc')->select();
        if (!$data) {
            return json(['code' => 1, 'info' => lang('No data')]);
        }
        foreach ($data as $key => $value) {
            if (empty($value['nickname'])) {
                $data[$key]['nickname'] = hiddenCharacter($value['username']);
            }
            $data[$key]['tel'] = hiddenCharacter($value['tel']);
        }

        return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $data]);
    }

    //修改密码
    public function set_pwd()
    {
        if (!request()->isPost()) {
            return json(['code' => 1, 'info' => lang('Error failed')]);
        }

        $o_pwd = input('old_pwd/s', '');
        $pwd   = input('new_pwd/s', '');
        $type  = input('type/d', 1);
        $info  = db('xy_users')->field('username,pwd,salt,tel,is_mobile')->find($this->user['id']);
        if ($info['pwd'] != sha1($o_pwd . $info['salt'] . config('pwd_str'))) {
            return json(['code' => 1, 'info' => lang('wrong password')]);
        }
        $data = [
            'username'  => $info['username'],
            'pwd'       => $pwd,
            'is_mobile' => $info['is_mobile'],
        ];
        $res = model('common/Users')->reset_pwd($data, $type);
        return json($res);
    }

    //我的下级
    public function get_user()
    {
        $uid   = $this->user['id'];
        $type  = input('post.type/d', 1);
        $page  = input('page/d', 1);
        $num   = input('num/d', 10);
        $uinfo = db('xy_users')->field('*')->find($this->user['id']);
        $other = [];
        if ($type == 1) {
            $uid  = $this->user['id'];
            $data = UsersModel::where('parent_id', $uid)
                ->field('id,username,headpic,addtime,childs,tel')
                ->limit(($page - 1) * $num, $num)
                ->order('addtime desc')
                ->select();

            //总的收入  总的充值
            $ids1              = db('xy_users')->where('parent_id', $uid)->field('id')->column('id');
            $cond              = implode(',', $ids1);
            $cond              = !empty($cond) ? $cond              = " uid in ($cond)" : ' uid=-1';
            $other             = [];
            $other['chongzhi'] = db('xy_recharge')->where($cond)->where('status', 2)->sum('num');
            $other['tixian']   = db('xy_deposit')->where($cond)->where('status', 2)->sum('num');
            $other['xiaji']    = count($ids1);

            //var_dump($uinfo);die;

            $iskou = 0;
            foreach ($data as &$datum) {
                $datum['addtime']                            = date('Y/m/d H:i', $datum['addtime']);
                empty($datum['headpic']) ? $datum['headpic'] = '/public/img/head.png' : '';
                //充值
                $datum['chongzhi'] = db('xy_recharge')->where('uid', $datum['id'])->where('status', 2)->sum('num');
                //提现
                $datum['tixian'] = db('xy_deposit')->where('uid', $datum['id'])->where('status', 2)->sum('num');

                if ($uinfo['kouchu_balance_uid'] == $datum['id']) {
                    $datum['chongzhi'] -= $uinfo['kouchu_balance'];
                    $iskou = 1;
                }
                if (empty($datum['nickname'])) {
                    $datum['nickname'] = hiddenCharacter($datum['username']);
                }

                if ($uinfo['show_tel2']) {
                    $datum['tel'] = hiddenCharacter($datum['tel'], '****', 3, 4);
                }
                if (!$uinfo['show_tel']) {
                    $datum['tel'] = lang('No permission');
                }
                if (!$uinfo['show_num']) {
                    $datum['childs'] = lang('No permission');
                }
                if (!$uinfo['show_cz']) {
                    $datum['chongzhi'] = lang('No permission');
                }
                if (!$uinfo['show_tx']) {
                    $datum['tixian'] = lang('No permission');
                }
            }

            $other['chongzhi'] -= $uinfo['kouchu_balance'];
            return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $data, 'other' => $other]);

        } else if ($type == 2) {
            $ids1 = db('xy_users')->where('parent_id', $uid)->field('id')->column('id');
            $cond = implode(',', $ids1);
            $cond = !empty($cond) ? $cond = " parent_id in ($cond)" : ' parent_id=-1';

            //获取二代ids
            $ids2              = db('xy_users')->where($cond)->field('id')->column('id');
            $cond2             = implode(',', $ids2);
            $cond2             = !empty($cond2) ? $cond2             = " uid in ($cond2)" : ' uid=-1';
            $other             = [];
            $other['chongzhi'] = db('xy_recharge')->where($cond2)->where('status', 2)->sum('num');
            $other['tixian']   = db('xy_deposit')->where($cond2)->where('status', 2)->sum('num');
            $other['xiaji']    = count($ids2);

            $data = db('xy_users')->where($cond)
                ->field('id,username,headpic,addtime,childs,tel')
                ->limit(($page - 1) * $num, $num)
                ->order('addtime desc')
                ->select();

            //总的收入  总的充值

            foreach ($data as &$datum) {
                empty($datum['headpic']) ? $datum['headpic'] = '/public/img/head.png' : '';
                $datum['addtime']                            = date('Y/m/d H:i', $datum['addtime']);
                //充值
                $datum['chongzhi'] = db('xy_recharge')->where('uid', $datum['id'])->where('status', 2)->sum('num');
                //提现
                $datum['tixian'] = db('xy_deposit')->where('uid', $datum['id'])->where('status', 2)->sum('num');
                if (empty($datum['nickname'])) {
                    $datum['nickname'] = hiddenCharacter($datum['username']);
                }

                if ($uinfo['show_tel2']) {
                    $datum['tel'] = hiddenCharacter($datum['tel'], '****', 3, 4);
                }
                if (!$uinfo['show_tel']) {
                    $datum['tel'] = lang('No permission');
                }
                if (!$uinfo['show_num']) {
                    $datum['childs'] = lang('No permission');
                }
                if (!$uinfo['show_cz']) {
                    $datum['chongzhi'] = lang('No permission');
                }
                if (!$uinfo['show_tx']) {
                    $datum['tixian'] = lang('No permission');
                }
            }

            return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $data, 'other' => $other]);

        } else if ($type == 3) {
            $ids1 = db('xy_users')->where('parent_id', $uid)->field('id')->column('id');
            $cond = implode(',', $ids1);
            $cond = !empty($cond) ? $cond = " parent_id in ($cond)" : ' parent_id=-1';
            $ids2 = db('xy_users')->where($cond)->field('id')->column('id');

            $cond2 = implode(',', $ids2);
            $cond2 = !empty($cond2) ? $cond2 = " parent_id in ($cond2)" : ' parent_id=-1';

            //获取三代的ids
            $ids22             = db('xy_users')->where($cond2)->field('id')->column('id');
            $cond22            = implode(',', $ids22);
            $cond22            = !empty($cond22) ? $cond22            = " uid in ($cond22)" : ' uid=-1';
            $other             = [];
            $other['chongzhi'] = db('xy_recharge')->where($cond22)->where('status', 2)->sum('num');
            $other['tixian']   = db('xy_deposit')->where($cond22)->where('status', 2)->sum('num');
            $other['xiaji']    = count($ids22);

            //获取四代ids
            $cond4 = implode(',', $ids22);
            $cond4 = !empty($cond4) ? $cond4 = " parent_id in ($cond4)" : ' parent_id=-1';
            $ids4  = db('xy_users')->where($cond4)->field('id')->column('id'); //四代ids

            //充值
            $cond44             = implode(',', $ids4);
            $cond44             = !empty($cond44) ? $cond44             = " uid in ($cond44)" : ' uid=-1';
            $other['chongzhi4'] = db('xy_recharge')->where($cond44)->where('status', 2)->sum('num');
            $other['tixian4']   = db('xy_deposit')->where($cond44)->where('status', 2)->sum('num');
            $other['xiaji4']    = count($ids4);

            //获取五代
            $cond5 = implode(',', $ids4);
            $cond5 = !empty($cond5) ? $cond5 = " parent_id in ($cond5)" : ' parent_id=-1';
            $ids5  = db('xy_users')->where($cond5)->field('id')->column('id'); //五代ids

            //充值
            $cond55             = implode(',', $ids5);
            $cond55             = !empty($cond55) ? $cond55             = " uid in ($cond55)" : ' uid=-1';
            $other['chongzhi5'] = db('xy_recharge')->where($cond55)->where('status', 2)->sum('num');
            $other['tixian5']   = db('xy_deposit')->where($cond55)->where('status', 2)->sum('num');
            $other['xiaji5']    = count($ids5);

            $other['chongzhi_all'] = $other['chongzhi'] + $other['chongzhi4'] + $other['chongzhi5'];
            $other['tixian_all']   = $other['tixian'] + $other['tixian4'] + $other['tixian5'];

            $data = db('xy_users')->where($cond2)
                ->field('id,username,headpic,addtime,childs,tel')
                ->limit(($page - 1) * $num, $num)
                ->order('addtime desc')
                ->select();

            //总的收入  总的充值

            foreach ($data as &$datum) {
                $datum['addtime']                            = date('Y/m/d H:i', $datum['addtime']);
                empty($datum['headpic']) ? $datum['headpic'] = '/public/img/head.png' : '';
                //充值
                $datum['chongzhi'] = db('xy_recharge')->where('uid', $datum['id'])->where('status', 2)->sum('num');
                //提现
                $datum['tixian'] = db('xy_deposit')->where('uid', $datum['id'])->where('status', 2)->sum('num');
                if (empty($datum['nickname'])) {
                    $datum['nickname'] = hiddenCharacter($datum['username']);
                }

                if ($uinfo['show_tel2']) {
                    $datum['tel'] = hiddenCharacter($datum['tel'], '****', 3, 4);
                }
                if (!$uinfo['show_tel']) {
                    $datum['tel'] = lang('No permission');
                }
                if (!$uinfo['show_num']) {
                    $datum['childs'] = lang('No permission');
                }
                if (!$uinfo['show_cz']) {
                    $datum['chongzhi'] = lang('No permission');
                }
                if (!$uinfo['show_tx']) {
                    $datum['tixian'] = lang('No permission');
                }
            }
            return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $data, 'other' => $other]);
        }

        return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $data]);
    }

    public function helps()
    {
        $list = Db::name('system_help')->order('sort DESC')->select();
        $this->assign('list', $list);
        return $this->fetch('help');
    }
}
