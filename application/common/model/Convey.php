<?php

namespace app\common\model;

use app\common\model\BalanceLog as BalanceLogModel;
use app\common\model\GoodsCate as GoodsCateModel;
use app\common\model\GoodsList as GoodsListModel;
use app\common\model\MemberAddress as MemberAddressModel;
use app\common\model\Message as MessageModel;
use app\common\model\RewardLog as RewardLogModel;
use app\common\model\UserError as UserErrorModel;
use app\common\model\Users as UsersModel;
use think\Db;
use think\Model;
use util\Time;

class Convey extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'xy_convey';

    // 默认主键
    protected $pk = 'id';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'addtime';
    protected $updateTime = 'endtime';

    public function getStatusTextAttr($value, $data)
    {
        $status = [0 => '等待付款', 1 => '完成付款', 2 => '用户取消', 3 => '强制付款', 4 => '系统取消', 5 => '订单冻结'];
        return $status[$value];
    }

    /**
     * 创建订单
     *
     * @param int $uid
     * @return array
     */
    public function create_order($uid, $cid = 1)
    {
        $user = UsersModel::where('id', $uid)->field('id,deal_status,balance,level,task,deal_special_count,special')->find();
        if ($user['deal_status'] != 2) {
            return [
                'code' => 1,
                // 抢单已终止
                'info' => lang('Stop order has ended'),
                'url'  => url('order/index'),
            ];
        }
        if ($user['special'] > 0) {
            $today = Time::today();
            $map   = [
                ['uid', '=', $user['id']],
                ['status', 'in', [0, 5]],
                // ['addtime', 'between', $today],
            ];
            // 统计当天完成交易的订单
            $count = self::where($map)->count();
            if ($count > 0) {
                return [
                    'code' => 1,
                    'info' => lang('The order is not completed, please wait'),
                ];
            }
        }
        $add_id = MemberAddressModel::where('uid', $user['id'])->where('is_default', 1)->value('id'); //获取收款地址信息s
        if (!$add_id) {
            return [
                'code' => 1,
                // 还没有设置收货地址
                'info' => lang('No shipping address has been set'),
                'url'  => url('Ctrl/receive_site'),
            ];
        }

        $min  = $user['balance'] * config('deal_min_num') / 100;
        $max  = $user['balance'] * config('deal_max_num') / 100;
        $bili = config('vip_1_commission');

        $level  = $user['level'];
        $ulevel = get_levels($level);
        if ($user['balance'] < $ulevel['num_min']) {
            return [
                'code' => 5,
                // 会员等级余额不足
                'info' => lang('Insufficient membership level balance'),
                'url'  => url('recharge/recharge_before'),
            ];
        }
        if (!empty($ulevel) && !empty($ulevel['extended'])) {
            if (!empty($ulevel['extended']['match_proportion'])) {
                $min = ($user['balance'] * $ulevel['extended']['match_min']) ?? $min;
                $max = ($user['balance'] * $ulevel['extended']['match_max']) ?? $max;
            } else {
                $min = $ulevel['extended']['match_min'] ?? $min;
                $max = $ulevel['extended']['match_max'] ?? $max;
            }
            if (!empty($ulevel['extended']['commission_max'])) {
                $bili = rand($ulevel['extended']['commission_min'] * 100000, $ulevel['extended']['commission_max'] * 100000) / 100000;
            } else {
                $bili = $ulevel['extended']['commission_min'];
            }
        }
        $special = 0;
        $map     = [
            ['uid', '=', $user['id']],
            ['status', '=', 1],
            ['special', '>', 0],
        ];
        $count = Db::name('xy_convey')
            ->where($map)
            ->count();
        if (empty($count)) {
            $count = 0;
        }
        // 从用户中取得分配金额
        if ($user['task'] >= 1 && $user['special'] > 0 && $user['task'] > $count + 1) {
            $match = get_convey_match($count + 1);
            if (!empty($match)) {
                // $money = $user['balance'];
                // if ($money < $match['initial']) {
                $money = $match['initial'];
                // }
                $min  = ($money * $match['min']) ?? $min;
                $max  = ($money * $match['max']) ?? $max;
                $bili = $match['proportion'];
            }
            $special = $user['special'];
        }
        do {
            $goods = $this->rand_order($min, $max, $cid);
            if ($goods['code'] > 0) {
                return $goods;
            }
        } while ($goods['num'] < $min || $goods['num'] > $max);

        $id = getSn('UB');
        Db::startTrans();
        $upd = [
            'deal_status' => 3,
            'deal_time'   => strtotime(date('Y-m-d')),
            'deal_count'  => Db::raw('deal_count+1'),
            // 'deal_count'  => ['inc', 1],
        ];
        // 通过商品id查找 佣金比例
        $cate       = GoodsCateModel::where('id', $goods['cid'])->find();
        $paid       = $goods['num'];
        $difference = 0;
        if ($goods['num'] > $user['balance']) {
            if ($user['task'] == 0) {
                return [
                    'code' => 1,
                    // 可用余额不足
                    'info' => lang('Insufficient available balance'),
                ];
            } else {
                $paid                  = $user['balance'];
                $difference            = $goods['num'] - $user['balance'];
                $upd['balance']        = 0;
                $upd['freeze_balance'] = Db::raw('freeze_balance+' . $paid);
            }
        }
        if ($user['special'] > 0) {
            $upd['balance']        = Db::raw('balance-' . $paid);
            $upd['freeze_balance'] = Db::raw('freeze_balance+' . ($paid));
        } else {
            $paid       = 0;
            $difference = $goods['num'];
        }
        // 将账户状态改为交易中
        $res  = UsersModel::where('id', $user['id'])->update($upd);
        $time = time();
        $data = [
            'id'          => $id,
            'uid'         => $user['id'],
            'num'         => $goods['num'],
            'add_id'      => $add_id,
            'goods_id'    => $goods['id'],
            'goods_count' => $goods['count'],
            // 交易佣金按照会员等级
            'commission'  => $goods['num'] * $bili,
            'special'     => $special,
            'paid'        => $paid,
            'difference'  => $difference,
        ];
        $res1 = self::create($data);
        if ($res && $res1) {
            Db::commit();
            return [
                'code' => 0,
                // 抢单成功
                'info' => lang('Successful orders'),
                'url'  => url('order/order_info', ['id' => $id]),
                'oid'  => $id,
            ];
        } else {
            Db::rollback();
            return [
                'code' => 1,
                // 抢单失败，请稍后再试！
                'info' => lang('Rush order failure'),
            ];
        }
    }

    /**
     * 随机生成订单
     */
    private function rand_order($min, $max, $cid)
    {
        // 随机交易额
        $num = mt_rand($min, $max);
        $mi  = $num / 10;
        $mp  = [
            ['cid', '=', $cid],
        ];
        $ma = GoodsListModel::where($mp)->order('goods_price DESC')->cache(true)->value('goods_price');
        if ($mi > $ma) {
            $mi = $ma;
        }
        $map = [
            ['cid', '=', $cid],
            ['goods_price', 'between', [$mi, $num]],
        ];
        $goods = Db::name('xy_goods_list')
            ->orderRaw('rand()')
            ->where($map)
            ->find();
        if (!$goods) {
            return [
                'code' => 1,
                // 抢单失败, 该分类库存不足!
                'info' => lang('the inventory of this category is insufficient'),
                'url'  => url('rot_order/index'),
            ];
        }
        $count = floor($num / $goods['goods_price']); //
        $money = $count * $goods['goods_price'];
        if ($money < $min || $money > $max) {
            self::rand_order($min, $max, $cid);
        }
        return [
            'code'  => 0,
            'count' => $count,
            'id'    => $goods['id'],
            'num'   => $money,
            'cid'   => $goods['cid'],
        ];
    }

    /**
     * 处理订单
     *
     * @param string $oid      订单号
     * @param int    $back     是否后台操作
     * @param int    $status   操作      1会员确认付款 2会员取消订单 3后台强制付款 4后台强制取消
     * @param int    $uid      用户ID    传参则进行用户判断
     * @param int    $add_id   收货地址
     * @return array
     */
    public function do_order($oid, $back = 0, $status, $uid = '', $add_id = '')
    {
        $info = self::where('id', $oid)->find();
        if (!$info) {
            return [
                'code' => 1,
                // 订单号不存在
                'info' => lang('Order number does not exist'),
            ];
        }
        if ($uid && $info['uid'] != $uid) {
            return [
                'code' => 1,
                // 参数错误
                'info' => lang('Parameter error'),
            ];
        }
        if ($info['status'] != 0) {
            return [
                'code' => 5,
                // 该订单已处理！请刷新页面
                'info' => lang('The order has been processed'),
                'url'  => url('order/index'),
            ];
        }
        // TODO 判断余额是否足够
        $user  = UsersModel::where('id', $info['uid'])->find();
        $money = $info['num'];
        if ($info['paid'] > 0) {
            $money = $info['num'] - $info['paid'];
        }
        if ($info['difference'] > 0) {
            $money = $info['difference'];
        }
        Db::startTrans();
        if (in_array($status, [1, 3])) {
            // 确认付款
            if ($user['balance'] < $money) {
                Db::rollback();
                return [
                    'code' => 5,
                    // 账户可用余额不足,还差:%s
                    'info' => lang('Insufficient available account balance', ['difference' => $money]),
                    'url'  => url('recharge/recharge_before'),
                    'data' => [
                        'balance' => $user['balance'],
                        'money'   => $money,
                    ],
                ];
            }
            $up = [
                'balance'        => Db::raw('balance-' . $money),
                'freeze_balance' => Db::raw('freeze_balance+' . ($money + $info['commission'])),
                'deal_status'    => 1,
                'status'         => 1,
            ];
            if ($user['task'] > $user['deal_special_count'] + 1) {
                $up['deal_special_count'] = Db::raw('deal_special_count+1');
            } else {
                $up['task']               = 0;
                $up['special']            = 0;
                $up['deal_special_count'] = 0;
            }
            $res1        = UsersModel::where('id', $user['id'])->update($up);
            $balance_log = [
                'uid'    => $info['uid'],
                'oid'    => $oid,
                'num'    => $info['num'],
                'type'   => 2,
                'status' => 2,
            ];
            $res2 = BalanceLogModel::create($balance_log);
            if ($status == 3) {
                $msg = [
                    'uid'     => $info['uid'],
                    'type'    => 2,
                    'title'   => lang('system notification'),
                    'content' => lang('Payment has been forced by the system', [$oid]),
                ];
                MessageModel::create($msg);
            }
            $tmp = [
                'endtime'    => time() + config('deal_feedze'),
                'status'     => 5,
                'paid'       => 0,
                'difference' => 0,
            ];
            $res = self::where('id', $oid)->update($tmp);
            // 系统通知
            if ($res && $res1 && $res2) {
                Db::commit();
                $url = url('rot_order/index');
                if ($info['special'] > 0) {
                    $ress = self::order_settlement($info);
                    $uu   = UsersModel::where('id', $info['uid'])->update(['deal_status' => 2]);
                    if ($ress && $back === 0) {
                        $corder = self::create_order($info['uid']);
                        $url    = url('order/order_info', ['id' => $corder['oid']]);
                    }
                }
                return [
                    'code' => 0,
                    'info' => lang('Successful operation'),
                    'url'  => $url,
                ];
            } else {
                Db::rollback();
                return [
                    'code' => 1,
                    'info' => lang('operation failed'),
                ];
            }
        } elseif (in_array($status, [2, 4])) {
            $tmp = [
                'endtime'    => time(),
                'status'     => $status,
                'paid'       => 0,
                'difference' => 0,
            ];
            $res = self::where('id', $oid)->update($tmp);
            $uup = [
                'deal_status' => 1,
            ];
            if ($info['paid'] > 0) {
                $uup = [
                    'deal_status'    => 1,
                    'balance'        => Db::raw('balance+' . $info['paid']),
                    'freeze_balance' => Db::raw('freeze_balance-' . $info['paid']),
                ];
            }
            if ($user['task'] >= 1 && $user['special'] > 0) {
                $uup['task']               = 0;
                $uup['special']            = 0;
                $uup['deal_special_count'] = 0;
            }
            $res1 = UsersModel::where('id', $info['uid'])->update($uup);
            if ($status == 4) {
                $msg = [
                    'uid'     => $info['uid'],
                    'type'    => 2,
                    'title'   => lang('system notification'),
                    'content' => lang('Cancelled by the system', [$oid]),
                ];
                MessageModel::create($msg);
            }
            // 系统通知
            if ($res && $res1 !== false) {
                Db::commit();
                return [
                    'code' => 0,
                    'info' => lang('Successful operation'),
                    'url'  => url('order/index'),
                ];
            } else {
                Db::rollback();
                return [
                    'code' => 1,
                    'info' => lang('operation failed'),
                    'data' => $res1,
                ];
            }
        }
    }

    public function cancel($oid, $status)
    {
        $info = self::where('id', $oid)->find();
        if (!$info) {
            return [
                'code' => 1,
                // 订单号不存在
                'info' => lang('Order number does not exist'),
            ];
        }
        if ($info['status'] != 0) {
            return [
                'code' => 5,
                // 该订单已处理！请刷新页面
                'info' => lang('The order has been processed'),
                'url'  => url('order/index'),
            ];
        }
        // TODO 判断余额是否足够
        $user = UsersModel::where('id', $info['uid'])->find();
        Db::startTrans();
        $tmp = [
            'endtime'    => time(),
            'status'     => $status,
            'paid'       => 0,
            'difference' => 0,
        ];
        $res = self::where('id', $oid)->update($tmp);
        $uup = [
            'deal_status' => 1,
        ];
        if ($info['paid'] > 0) {
            $uup = [
                'deal_status'    => 1,
                'balance'        => Db::raw('balance+' . $info['paid']),
                'freeze_balance' => Db::raw('freeze_balance-' . $info['paid']),
            ];
        }
        $res1 = UsersModel::where('id', $info['uid'])->update($uup);
        if ($status == 4) {
            $msg = [
                'uid'     => $info['uid'],
                'type'    => 2,
                'title'   => lang('system notification'),
                'content' => lang('Cancelled by the system', [$oid]),
            ];
            MessageModel::create($msg);
        }
        // 系统通知
        if ($res && $res1 !== false) {
            Db::commit();
            return [
                'code' => 0,
                'info' => lang('Successful operation'),
                'url'  => url('order/index'),
            ];
        } else {
            Db::rollback();
            return [
                'code' => 1,
                'info' => lang('operation failed'),
                'data' => $res1,
            ];
        }
    }

    /**
     * [order_settlement 结算订单]
     * @param  array  $info [订单详情]
     * @return [type]       [description]
     */
    public function order_settlement($info)
    {
        return Db::transaction(function () use ($info) {
            $balance_logs = [];
            $reward_logs  = [];
            $users        = [];
            $convey       = [
                // 将订单状态改为已处理
                'status'   => 1,
                // 将订单状态改为已返回佣金
                'c_status' => 1,
            ];

            $user    = UsersModel::where('id', $info['uid'])->find();
            $income  = $info['num'] + $info['commission']; // 冻结商品金额 + 佣金
            $users[] = [
                'id'             => $user['id'],
                'balance'        => Db::raw('balance+' . $income),
                'freeze_balance' => Db::raw('freeze_balance-' . $income),
                'deal_status'    => 1,
            ];

            $map = [
                ['oid', '=', $info['id']],
            ];
            $ob = BalanceLogModel::where($map)->update(['status' => 1]);

            $reward_logs[] = [
                'oid'     => $info['id'],
                'uid'     => $info['uid'],
                'num'     => $info['num'],
                'addtime' => time(),
                'type'    => 2,
            ];
            /************* 发放交易奖励 *********/
            $userList = (new UsersModel)->parent_user($info['uid'], 5);
            if (!empty($userList)) {
                foreach ($userList as $value) {
                    $mapu = [
                        ['id', '=', $value['id']],
                        ['status', '=', 1],
                    ];
                    $puser = UsersModel::where($mapu)->find();
                    if (!empty($puser)) {
                        $income = $info['commission'] * config($value['lv'] . '_d_reward'); // 佣金
                        if ($income > 0) {
                            $reward_logs[] = [
                                'uid'    => $value['id'],
                                'sid'    => $value['pid'],
                                'oid'    => $info['id'],
                                'num'    => $income,
                                'lv'     => $value['lv'],
                                'type'   => 2,
                                'status' => 1,
                            ];
                            $users[] = [
                                'id'      => $puser['id'],
                                'balance' => Db::raw('balance+' . $income), // 余额 + 佣金
                            ];
                            $balance_logs[] = [
                                'uid'    => $value['id'],
                                'sid'    => 0,
                                'oid'    => $info['id'],
                                'num'    => $income,
                                'type'   => 6,
                                'status' => 1,
                            ];
                        }
                    }
                }
            }
            /************* 发放交易奖励 *********/
            $oc  = self::where('id', $info['id'])->update($convey);
            $or  = (new RewardLogModel)->saveAll($reward_logs);
            $obs = true;
            if (!empty($balance_logs)) {
                $obs = (new BalanceLogModel)->saveAll($balance_logs);
            }
            $ou = (new UsersModel)->saveAll($users);
            return [
                'id'             => $info['id'],
                'username'       => $user['username'],
                'num'            => $info['num'],
                'commission'     => $info['commission'],
                'balance'        => $user['balance'],
                'freeze_balance' => $user['freeze_balance'],
            ];
        });
    }

    /**
     * [cancel_order 强制取消订单并冻结账户]
     * @param  array  $info [订单详情]
     * @return [type]       [description]
     */
    public function order_cancel($info = [])
    {
        return Db::transaction(function () use ($info) {
            $convey = [
                'status'  => 4,
                'endtime' => time(),
            ];
            $oc   = self::where('id', $info['id'])->update($convey);
            $user = UsersModel::where('id', $info['uid'])->field('deal_error,deal_status')->find();
            // 记录违规信息
            if ($user['deal_status'] != 0) {
                if ($user['deal_error'] < (int) config('deal_error')) {
                    $data = [
                        'deal_status' => 1,
                        'deal_error'  => Db::raw('deal_error+1'),
                    ];
                    UsersModel::where('id', $info['uid'])->update($data);
                    $error = [
                        'uid'  => $info['uid'],
                        'oid'  => $info['id'],
                        'type' => 2,
                    ];
                    UserErrorModel::create($error);
                } elseif ($user['deal_error'] >= (int) config('deal_error')) {
                    $data = [
                        'deal_status' => 1,
                        'deal_error'  => 0,
                    ];
                    UsersModel::where('id', $info['uid'])->update($data);
                    $error = [
                        'uid'  => $info['uid'],
                        'oid'  => $info['id'],
                        'type' => 3,
                    ];
                    UserErrorModel::create($error);
                    // 记录交易冻结信息
                }
            }
            return [
                'id'         => $info['id'],
                'num'        => $info['num'],
                'commission' => $info['commission'],
            ];
        });
    }
}
