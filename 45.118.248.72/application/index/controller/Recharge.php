<?php

namespace app\index\controller;

use app\common\model\Pay as PayModel;
use app\common\model\PayInfo as PayInfoModel;
use app\common\model\Recharge as RechargeModel;
use think\Controller;
use think\Db;
use think\Request;
use util\Time;

class Recharge extends Base
{

    /**
     * 获取充值订单
     */
    public function get_recharge_order()
    {
        $uid  = session('user_id');
        $page = input('post.page/d', 1);
        $num  = input('post.num/d', 10);
        $info = db('xy_recharge')
            ->where('uid', $uid)
            ->order('addtime desc')
            ->limit((($page - 1) * $num), $num)
            ->select();
        if (!$info) {
            return json(['code' => 1, 'info' => lang('No data')]);
        }

        return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $info]);
    }

    //升级vip
    public function recharge_dovip()
    {
        if (input('level') && (input('type') || input('type') == 0)) {
            $level = input('level', 1);
            $type  = input('type', 0);
            $pic   = input('pic/s', '');
            $uid   = $this->user['id'];

            $uinfo = db('xy_users')->field('pwd,salt,tel,username,balance')->find($uid);
            if (!$level) {
                // 参数错误
                return json(['code' => 1, 'info' => lang('Parameter error')]);
            }
            $level = get_levels($level);
            if (empty($level['num'])) {
                return json(['code' => 1, 'info' => lang('Submission failed')]);
            }
            $map = [
                ['uid', '=', $uid],
                ['is_vip', '=', 1],
                ['status', '=', 1],
            ];
            $r = RechargeModel::where($map)->find();
            if (!empty($r)) {
                return json(['code' => 1, 'info' => lang('VIP upgrade has unprocessed orders')]);
            }
            $pay = [];
            if ($type != 0) {
                $pay = db('xy_pay')->where('id', $type)->find();
                if (empty($pay)) {
                    return json(['code' => 1, 'info' => lang('Submission failed')]);
                }
                $payinfo = db('xy_pay_info')->where(['payid' => $type, 'default' => 1, 'status' => 1])->find();
                if (empty($payinfo)) {
                    $payinfo = db('xy_pay_info')->where(['payid' => $type, 'status' => 1])->orderRaw('rand()')->find();
                    if (empty($payinfo)) {
                        return json(['code' => 1, 'info' => lang('Submission failed'), 'pram' => input('')]);
                    }
                }
            } else {
                if ($uinfo['balance'] < $level['num']) {
                    return json(['code' => 1, 'info' => lang('Insufficient available balance'), 'pram' => input('')]);
                }
            }
            $id  = getSn('SY');
            $res = db('xy_recharge')
                ->insert([
                    'id'        => $id,
                    'uid'       => $uid,
                    'tel'       => $uinfo['tel'],
                    'real_name' => $uinfo['username'],
                    'num'       => $level['num'],
                    'addtime'   => time(),
                    'pay_name'  => $type,
                    'is_vip'    => 1,
                    'level'     => $level['level'],
                    'pic'       => $pic,
                ]);
            if ($res) {
                if (!empty($pay)) {
                    switch ($pay['name2']) {
                        case 'bipay':
                            $pay['redirect'] = url('/index/Api/bipay') . '?oid=' . $id;
                            break;
                        case 'paysapi':
                            $pay['redirect'] = url('/index/Api/pay') . '?oid=' . $id;
                            break;
                        default:
                            $pay['payinfo'] = $payinfo;
                            break;
                    }
                }
                if ($type == 0) {
                    $pay['name']    = lang('Balance payment');
                    $pay['name2']   = 'balance';
                    $pay['balance'] = $uinfo['balance'];
                    $pay['payinfo'] = ['qrcode' => ''];
                }
                $pay['id']  = $id;
                $pay['num'] = $level['num'];
                return json(['code' => 0, 'info' => $pay]);
            } else {
                // 提交失败，请稍后再试
                return json(['code' => 1, 'info' => lang('Submission failed')]);
            }
        }
        // 请求成功
        return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => []]);
    }

    /**
     * [recharge_before 充值渠道选择]
     * @return [type] [description]
     */
    public function recharge_before()
    {
        $pay = db('xy_pay')->where(['status' => 1, 'recharge' => 1])->select();
        $this->assign('list', $pay);
        return $this->fetch();
    }

    public function recharge()
    {
        $para = input('');
        if (!isset($para['payid']) || empty($para['payid'])) {
            return json(['code' => 1, 'info' => lang('Parameter error')]);
        }
        $map = [
            ['id', '=', $para['payid']],
            ['status', '=', 1],
        ];
        $pay = PayModel::where($map)->find()->toArray();
        if (empty($pay)) {
            return json(['code' => 1, 'info' => lang('The current way is temporarily unable to recharge'), 'url' => url('recharge_before')]);
        }
        if (request()->isPost()) {
            $map = [
                ['uid', '=', $this->user['id']],
                ['is_vip', '=', 0],
                ['status', '=', 1],
            ];
            $r = RechargeModel::where($map)->find();
            if (!empty($r)) {
                return json(['code' => 1, 'info' => lang('There are unprocessed orders for recharge'), 'url' => url('recharge_admin')]);
            }
            if (!isset($para['num']) || empty($para['num'])) {
                return json(['code' => 1, 'info' => lang('Parameter error')]);
            }
            if (!empty($pay['min']) && $para['num'] < $pay['min']) {
                return json(['code' => 1, 'info' => lang('Recharge cannot be less than', [$pay['min']])]);
            }
            if (!empty($pay['max']) && $para['num'] > $pay['max']) {
                return json(['code' => 1, 'info' => lang('Recharge cannot be greater than', [$pay['max']])]);
            }
            $map = [
                ['status', '=', 1],
                ['payid', '=', $para['payid']],
                ['min', 'between', [0, $para['num']]],
                ['max', 'notbetween', [1, $para['num'] - 1]],
            ];
            $payinfo = PayInfoModel::where($map)->find();
            if (empty($payinfo)) {
                return json(['code' => 1, 'info' => lang('The current way is temporarily unable to recharge'), 'url' => url('recharge_before')]);
            }
            return json(['code' => 0, 'info' => lang('Successful operation'), 'url' => url('dorecharge', [
                'payid'     => $pay['id'],
                'infoid'    => $payinfo['id'],
                'num'       => $para['num'],
                'real_name' => $para['real_name'],
                'tel'       => $para['tel'],
            ])]);
        } else {
            return view('recharge', ['pay' => $pay]);
        }
    }

    public function dorecharge($payid, $infoid, $num, $real_name, $tel)
    {
        $para = [
            'payid'     => $payid,
            'infoid'    => $infoid,
            'num'       => $num,
            'real_name' => $real_name,
            'tel'       => $tel,
        ];
        if (empty($para['payid']) || empty($para['infoid']) || empty($para['num']) || empty($para['real_name']) || empty($para['tel'])) {
            return json(['code' => 1, 'info' => lang('Parameter error')]);
        }
        $map = [
            ['id', '=', $para['payid']],
            ['status', 'in', [1, 3]],
        ];
        $pay = PayModel::where($map)->find()->toArray();
        if (empty($pay)) {
            return json(['code' => 1, 'info' => lang('The current way is temporarily unable to recharge'), 'url' => url('recharge_before')]);
        }
        if (!isset($para['num']) || empty($para['num'])) {
            return json(['code' => 1, 'info' => lang('Parameter error')]);
        }
        if (!empty($pay['min']) && $para['num'] < $pay['min']) {
            return json(['code' => 1, 'info' => lang('Recharge cannot be less than', [$pay['min']])]);
        }
        if (!empty($pay['max']) && $para['num'] > $pay['max']) {
            return json(['code' => 1, 'info' => lang('Recharge cannot be greater than', [$pay['max']])]);
        }
        if (request()->isPost()) {
            $time = time();
            $map  = [
                ['status', '=', 1],
                ['payid', '=', $para['payid']],
                ['min', 'between', [0, $para['num']]],
                ['max', 'notbetween', [1, $para['num'] - 1]],
            ];
            $payinfo = PayInfoModel::where($map)->order('usage_time ASC')->find();
            if (empty($payinfo)) {
                return json(['code' => 1, 'info' => lang('The current way is temporarily unable to recharge'), 'url' => url('recharge_before')]);
            }
            $rand                       = rand(1000000, 9999999);
            $payinfo['nominee']         = $rand;
            $pay['nominee_description'] = sprintf($pay['nominee_description'], $rand, $rand);
            if (!empty($pay['time_start']) && !empty($pay['time_end'])) {
                $se = Time::startEnd($pay['time_start'], $pay['time_end']);
                if ($time < $se[0] || $time > $se[1]) {
                    return json(['code' => 1, 'info' => lang('Recharge time', $pay), 'url' => url('recharge_before')]);
                }
            }
            return json(['code' => 0, 'info' => lang('Successful operation'), 'data' => $para, 'payinfo' => $payinfo, 'pay' => $pay]);
        } else {
            return view('dorecharge', ['pay' => $pay]);
        }
    }

    /**
     * [recharge_do 用户账号充值]
     * @return [type] [description]
     */
    public function recharge_do()
    {
        $payid     = input('payid/d', 0);
        $infoid    = input('infoid/d', 0);
        $num       = input('num/d', 0);
        $real_name = input('real_name/s', '');
        $tel       = input('tel/s', '');
        $uid       = $this->user['id'];
        if (empty($payid) || empty($infoid)) {
            return json(['code' => 1, 'info' => lang('Parameter error')]);
        }
        $time = time();
        $map  = [
            ['id', '=', $payid],
            ['status', '=', 1],
        ];
        $pay = PayModel::where($map)->find()->toArray();
        if (empty($pay)) {
            return json(['code' => 1, 'info' => lang('The current way is temporarily unable to recharge'), 'url' => url('recharge_before')]);
        }
        $map = [
            ['status', '=', 1],
            ['payid', '=', $payid],
        ];
        $payinfo = PayInfoModel::where($map)->order('usage_time ASC')->find();
        if (empty($payinfo)) {
            return json(['code' => 1, 'info' => lang('The current way is temporarily unable to recharge'), 'url' => url('recharge_before')]);
        }
        $payinfo['usage_time'] = $time;
        if (!empty($pay['time_start']) && !empty($pay['time_end'])) {
            $se = Time::startEnd($pay['time_start'], $pay['time_end']);
            if ($time < $se[0] || $time > $se[1]) {
                return json(['code' => 1, 'info' => lang('Recharge time', $pay), 'url' => url('recharge_before')]);
            }
        }
        $map = [
            ['uid', '=', $uid],
            ['is_vip', '=', 0],
            ['status', '=', 1],
        ];
        $r = RechargeModel::where($map)->find();
        if (!empty($r)) {
            return json(['code' => 1, 'info' => lang('There are unprocessed orders for recharge')]);
        }
        if (request()->isPost()) {
            $nominee = input('nominee');
            if (!$nominee || !$num || !$tel || !$real_name) {
                return json(['code' => 1, 'info' => lang('Parameter error')]);
            }

            if (!extract_phone_number($tel, config('system.mobile_area'))) {
                return json(['code' => 1, 'info' => lang('Mobile number format is incorrect')]);
            }

            $time  = time();
            $uinfo = db('xy_users')->field('pwd,salt,tel,username')->find($uid);
            if (!$num) {
                return json(['code' => 1, 'info' => lang('Parameter error')]);
            }
            if (!empty($pay['min']) && $num < $pay['min']) {
                return json(['code' => 1, 'info' => lang('Recharge cannot be less than', [$pay['min']])]);
            }
            if (!empty($pay['max']) && $num > $pay['max']) {
                return json(['code' => 1, 'info' => lang('Recharge cannot be greater than', [$pay['max']])]);
            }
            if (!empty($pay['time_start']) && !empty($pay['time_end'])) {
                $se = Time::startEnd($pay['time_start'], $pay['time_end']);
                if ($time < $se[0] || $time > $se[1]) {
                    return json(['code' => 1, 'info' => lang('Recharge time', $pay)]);
                }
            }

            $id    = getSn('SY');
            $rdata = [
                'id'         => $id,
                'uid'        => $uid,
                'tel'        => $tel,
                'real_name'  => $real_name,
                'num'        => $num,
                'nominee'    => $nominee,
                'pay_name'   => $payid,
                'payinfo_id' => $infoid,
            ];
            // $res = db('xy_recharge')
            //     ->insert($rdata);
            $res = RechargeModel::create($rdata);
            if ($res) {
                $pay['id']  = $id;
                $pay['num'] = $num;
                $payinfo->save();
                if ($pay['name2'] == 'bipay') {
                    $pay['redirect'] = url('/index/Api/bipay') . '?oid=' . $id;
                }
                if ($pay['name2'] == 'paysapi') {
                    $pay['redirect'] = url('/index/Api/pay') . '?oid=' . $id;
                }
                return json(['code' => 0, 'info' => lang('Successful operation')]);
            } else {
                return json(['code' => 1, 'info' => lang('Submission failed')]);
            }

        }
        return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => ['pay' => $pay ?? [], 'info' => $payinfo ?? []]]);
    }

    public function recharge_admin()
    {
        return view('');
    }

    public function info($id = '')
    {
        if (empty($id)) {
            $id = input('id/s', '');
        }
        $uid = $this->user['id'];
        if (empty($id)) {
            return json(['code' => 1, 'info' => lang('Parameter error')]);
        }
        $map = [
            ['uid', '=', $uid],
            ['id', '=', $id],
        ];
        $info = RechargeModel::where($map)->find();
        if (empty($info)) {
            return json(['code' => 1, 'info' => lang('Order does not exist')]);
        }
        if ($info['status'] == 2 || ($info['status'] == 3 && empty($info['description']))) {
            if (request()->isAjax()) {
                return json(['code' => 1, 'info' => lang('The order has been processed')]);
            } else {
                $this->redirect('recharge_admin');
            }
        }
        $info['payinfo'] = [];
        $info['pay']     = [];
        $map             = [
            ['id', '=', $info['pay_name']],
            ['status', '=', 1],
        ];
        $pay                        = PayModel::where($map)->find()->toArray();
        $pay['nominee_description'] = sprintf($pay['nominee_description'], $info['nominee'], $info['nominee']);
        $map                        = [
            ['status', '=', 1],
            ['id', '=', $info['payinfo_id']],
        ];
        $payinfo = PayInfoModel::where($map)->order('usage_time ASC')->find();
        if (request()->isPost()) {
            return json(['code' => 0, 'info' => lang('Request succeeded'), 'data' => $info, 'url' => url('info', ['id' => $id])]);
        } else {
            return view('recharge_info', ['info' => $info, 'pay' => $pay, 'payinfo' => $payinfo]);
        }
    }

    public function update()
    {
        $id  = input('id/s', '');
        $num = input('num/d', 0);
        $pic = input('pic/s', '');
        $uid = $this->user['id'];
        $map = [
            ['uid', '=', $uid],
            ['id', '=', $id],
        ];
        $info = RechargeModel::where($map)->find();
        if (empty($info)) {
            return json(['code' => 1, 'info' => lang('Order does not exist')]);
        }
        if ($info['status'] == 2 || ($info['status'] == 3 && empty($info['description']))) {
            return json(['code' => 1, 'info' => lang('The order has been processed')]);
        }
        if (empty($id) || empty($pic)) {
            return json(['code' => 1, 'info' => lang('Parameter error')]);
        }
        if (is_image_base64($pic)) {
            $pic = '/' . $this->upload_base64('certificate', $pic);
        } else {
            //调用图片上传的方法
            return json(['code' => 1, 'info' => lang('Picture format error')]);
        }
        $info->pic    = $pic;
        $info->tip    = 0;
        $info->status = 1;
        if (!empty($num)) {
            $info->num = $num;
        }
        if ($info->save()) {
            return json(['code' => 0, 'info' => lang('Successful operation')]);
        } else {
            return json(['code' => 1, 'info' => lang('Submission failed')]);
        }
    }

    public function recharge2()
    {
        $payid = input('id/d');
        $time  = time();
        $map   = [
            ['id', '=', $payid],
            ['status', '=', 1],
        ];
        $pay  = PayModel::where($map)->find()->toArray();
        $info = PayInfoModel::where(['status' => 1, 'payid' => $payid])->find();

        return view('', ['id' => $payid, 'title' => $pay['name'], 'pay' => $pay, 'info' => $info]);
    }

    //三方支付
    public function recharge3()
    {

        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'wx';
        $pay  = db('xy_pay')->where('status', 1)->select();
        $this->assign('title', $type == 'wx' ? '微信支付' : '支付宝支付');
        $this->assign('pay', $pay);
        $this->assign('type', $type);
        return $this->fetch();
    }
}
