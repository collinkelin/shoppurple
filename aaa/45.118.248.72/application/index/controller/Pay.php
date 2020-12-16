<?php

namespace app\index\controller;

use app\common\model\Bankinfo as BankinfoModel;
use app\common\model\Pay as PayModel;
use think\Controller;
use think\Db;
use util\Time;

class Pay extends Base
{
    /**
     * 首页
     */
    public function index()
    {
        $this->info = db('xy_cs')->where('status', 1)->select();
        $this->assign('list', $this->info);

        $this->msg = db('xy_index_msg')->where('status', 1)->select();
        return $this->fetch();
    }

    public function check_rates()
    {
        $payid  = input('payid');
        $amount = input('amount');
        $time   = time();
        if (empty($payid) || empty($amount)) {
            return json(['code' => 1, 'info' => lang('Parameter error')]);
        }
        $map = [
            ['id', '=', $payid],
            ['status', '=', 1],
        ];
        $pay = PayModel::where($map)->find();
        if (empty($pay)) {
            return json(['code' => 1, 'info' => lang('Cannot withdraw in the current way'), 'url' => url('Ctrl/deposit_before')]);
        }
        if ($pay && $pay['withdrawal_user']) {
            $map = [
                ['uid', '=', session('user_id')],
                ['status', '=', 1],
            ];
            $bankinfo = BankinfoModel::where($map)->find();
            if (!$bankinfo) {
                return json(['code' => 1, 'info' => lang('No bank card information has been added'), 'url' => url('Ctrl/bank')]);
            }
        }
        if (!empty($pay['time_start']) && !empty($pay['time_end'])) {
            $se = Time::startEnd($pay['time_start'], $pay['time_end']);
            if ($time < $se[0] || $time > $se[1]) {
                return json(['code' => 1, 'info' => lang('Withdrawal time', $pay), 'url' => url('deposit_before')]);
            }
        }
        // 手续费
        $handling_fee = 0;
        if (!empty($pay['handling_fee'])) {
            // if ((!empty($pay['handling_fee_limit']) && $amount <= $pay['handling_fee_limit']) || $pay['handling_fee_limit'] === 0) {
            //     if ($pay['handling_fee_type'] > 0) {
            //         $handling_fee = $amount * $pay['handling_fee'] / 100;
            //     } else {
            //         $handling_fee = $pay['handling_fee'];
            //     }
            // }
        }
        $data = [
            'amount'       => $amount,
            'arrival'      => $amount - $handling_fee,
            'handling_fee' => $handling_fee,
        ];
        $result = [
            'code' => 0,
            'info' => lang('Handling fee for withdrawing cash', $data),
        ];
        return json($result);
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

    //---------------------------------------------------
    //支付
    //---------------------------------------------------

}
