<?php

namespace app\command;

use app\command\Common;
use app\common\model\Convey as ConveyModel;
use app\common\model\MessageAisle as MessageAisleModel;
use app\common\model\Message as MessageModel;
use app\common\model\MessageQueue as MessageQueueModel;
use app\common\model\Recharge as RechargeModel;
use app\common\model\Users as UsersModel;
use Swoole\Timer;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\Db;
use think\facade\Log;
use util\Time;

// 启动
// php think settlement
class Settlement extends Common
{
    protected function configure()
    {
        $this->setName('settlement')
            ->addArgument('action', Argument::OPTIONAL, "action  start|stop|restart")
            ->addArgument('type', Argument::OPTIONAL, "d -d")
            ->setDescription('the settlement command');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $output->writeln('settlement');
        global $argv;
        $action = trim($input->getArgument('action'));
        $type   = trim($input->getArgument('type')) ? '-d' : '';

        $argv[0] = 'settlement';
        $argv[1] = $action;
        $argv[2] = $type ? '-d' : '';

        $this->start($action);
    }

    private function start($action)
    {
        Timer::after(2000, function () {
            $this->settlement();
            $this->cancelMonitor();
        });
    }

    /**
     * [settlement 结算订单]
     * @return [type] [description]
     */
    private function settlement()
    {
        $map = [
            ['status', '=', 5],
            ['special', '=', 0],
            ['endtime', '<=', time()],
        ];
        $oinfo = ConveyModel::where($map)->order('addtime ASC')->select();
        if (!empty($oinfo)) {
            $ConveyModel = new ConveyModel;
            foreach ($oinfo as $v) {
                $re = $ConveyModel->order_settlement($v);
                if (!empty($re['id'])) {
                    $m   = memory_get_usage();
                    $log = '订单結算成功;訂單號:' . $re['id'] . ';用户:' . $re['username'] . ';订单金额:' . $re['num'] . ';佣金:' . $re['commission'] . ';总额:' . numFilter($re['num'] + $re['commission']) . ';余额:' . $re['balance'] . ';冻结:' . $re['freeze_balance'] . ';消耗內存:' . $m;
                    Log::write($log, 'order_settlement');
                    echo $log, PHP_EOL;
                }
            }
        }

        // 强制取消订单并冻结账户
        $timeout = time() - config('deal_timeout'); // 超时订单
        $map     = [
            ['status', '=', 0],
            ['special', '=', 0],
            ['addtime', '<=', $timeout],
        ];
        $oinfo = Db::name('xy_convey')->field('id,uid,num,commission')->where($map)->select();
        if (!empty($oinfo)) {
            $ConveyModel = new ConveyModel;
            foreach ($oinfo as $v) {
                $re = $ConveyModel->order_cancel($v);
                if (!empty($re['id'])) {
                    $m   = memory_get_usage();
                    $log = '订单强制取消成功;訂單號:' . $re['id'] . ';订单金额:' . $re['num'] . ';佣金:' . $re['commission'] . ';消耗內存:' . $m;
                    Log::write($log, 'order_cancel');
                    echo $log, PHP_EOL;
                }
            }
        }

        // 解冻账号
        $uinfo = Db::name('xy_users')->where('deal_status', 0)->field('id,username')->select();
        if (!empty($uinfo)) {
            $UsersModel = new UsersModel;
            foreach ($uinfo as $v) {
                $re = $UsersModel->unfreeze($v);
                if (!empty($re['id'])) {
                    $m   = memory_get_usage();
                    $log = '账号解封成功;账号:' . $re['username'] . ';消耗內存:' . $m;
                    Log::write($log, 'unfreeze');
                    echo $log, PHP_EOL;
                }
            }
        }
        $map = [
            ['freeze_balance', '>', 0],
            ['special', '=', 0],
            ['deal_status', '=', 1],
            ['status', '=', 1],
        ];
        $users = UsersModel::where($map)->field('id,username,balance,freeze_balance,recharge_total,deposit_total,deal_time,addtime')->select();
        if (!empty($users)) {
            foreach ($users as $key => $user) {
                $freeze_num = Db::table('xy_convey')->where('uid', $user['id'])->where('status', 5)->sum('num');
                $difference = Db::table('xy_convey')->where('uid', $user['id'])->sum('difference');
                if ($freeze_num == 0 && $difference == 0) {
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
                    if ($balance >= 0 && $freeze_balance >= 0) {
                        $res             = Db::table('xy_users')->where('id', $user['id'])->update(['balance' => $balance, 'freeze_balance' => $freeze_balance]);
                        $username        = $user['username'];
                        $balance1        = $user['balance'];
                        $freeze_balance1 = $user['freeze_balance'];
                        $addtime         = date('Y-m-d H:i:s', $user['addtime']);
                        $log             = "账号:$username;余额:$balance1;冻结:$freeze_balance1;注册时间:$addtime;充值:$recharge;提现(通过):$deposit;提现(处理中):$deposit_1;VIP花费:$vip;刷单收益:$commission;下线返利:$cash;处理后余额:$balance;处理后冻结余额:$freeze_balance;";
                        Log::write($log, 'auto_freeze');
                        echo $log, PHP_EOL;
                    }
                }
            }
        }
    }

    private function cancelMonitor()
    {
        $config = sys_config();
        $limit  = 50;
        $start  = 0;
        $time   = time();
        $tpl    = Db::name('system_message_tpl')->where(['name' => 'Order return', 'range' => getRange()])->cache(true)->find();
        if (!empty($config['recharge_timeout'])) {
            do {
                $map = [
                    ['type', '=', 1],
                    ['pic', '=', ''],
                    ['status', '=', 1],
                    ['is_vip', '=', 0],
                    ['addtime', '<>', 0],
                    ['addtime', '<', $time - $config['recharge_timeout']],
                ];
                $defrays = RechargeModel::where($map)
                    ->limit($start, $limit)
                    ->select();
                // 发送邮件
                foreach ($defrays as $key => $value) {
                    if ($value['endtime'] == $value['addtime']) {
                        $update = [
                            'id'     => $value['id'],
                            'status' => 3,
                            // 'address' => '',
                            // 'remarks' => 'Recharge order timeout, automatic revocation',
                        ];
                        echo "充值订单ID:{$value['id']} 超时，执行撤单", PHP_EOL;
                        RechargeModel::where(['id' => $update['id']])->update($update);
                        $msg = [
                            'uid'     => $value['uid'],
                            'type'    => 2,
                            'title'   => $tpl['title'],
                            'content' => sprintf($tpl['content'], $value['id']),
                        ];
                        MessageModel::create($msg);
                        echo "充值订单ID:{$value['id']} 超时，撤单成功", PHP_EOL;
                    }
                }
                $start += $limit;
            } while (count($defrays) == $limit);
        }

    }

    private function rechargeMonitor()
    {
        $config = getConfigs();
        $limit  = 50;
        $start  = 0;
        $time   = time();
        if (!empty($config['gain_auto_time'])) {
            do {
                // 获取待发送邮件
                $map = [
                    ['type', '=', 1],
                    ['status', '=', 1],
                    ['update_time', '<>', 0],
                    ['update_time', '<', $time - $config['gain_auto_time']],
                ];
                $defrays = DefrayModel::where($map)
                    ->limit($start, $limit)
                    ->select();
                // 发送邮件
                foreach ($defrays as $key => $value) {
                    if ($value['update_time'] > $value['create_time']) {
                        $update = [
                            'id'      => $value['id'],
                            'status'  => 0,
                            'address' => '',
                            'remarks' => 'Recharge order timeout, automatic revocation',
                        ];
                        echo date('Y-m-d H:i:s') . "\t充值订单ID:{$value['id']} 超时，执行撤单", PHP_EOL;
                        DefrayModel::update($update, ['id' => $update['id']]);
                    }
                }
                $start += $limit;
            } while (count($defrays) == $limit);
        }
    }

    private function SendMail()
    {
        $mapc = [
            ['type', '=', 0],
            ['status', '=', 1],
            ['default', '=', 1],
        ];
        $time   = time();
        $config = MessageAisleModel::where($mapc)->find();
        if (!empty($config)) {
            $limit = 50;
            $start = 0;
            do {
                // 获取待发送邮件
                $map = [
                    ['sms', '=', 0],
                    ['status', '=', 0],
                ];
                $mails = MessageQueueModel::where($map)
                    ->limit($start, $limit)
                    ->select();
                // 发送邮件
                foreach ($mails as $key => $mail) {
                    if ($mail['status'] != 1) {
                        $action = $config['action'];
                        // if (!function_exists($action)) {
                        echo json_encode($config), PHP_EOL;
                        $result = $action($config, $mail['to'], $mail['to'], $mail['title'], $mail['content']);
                        if ($result == true) {
                            echo date('Y-m-d H:i:s') . "\t向:{$mail['to']} 发送邮件: {$mail['title']}", PHP_EOL;
                            $data = [
                                'id'     => $mail['id'],
                                'status' => 1,
                            ];
                            MessageQueueModel::update($data, ['id' => $data['id']]);
                        } else {
                            var_dump($result);
                            echo PHP_EOL;
                        }
                        // }
                    }
                }
                $start += $limit;
            } while (!empty($mails[$limit]));
        }
        $mapq = [
            ['sms', '=', 0],
            ['expire_time', '<', $time],
        ];
        MessageQueueModel::where($mapq)->delete();

        Timer::after(5000, function () {
            $this->SendMail();
        });
    }
}
