<?php

namespace app\index\controller;

use app\common\model\Convey as ConveyModel;
use app\common\model\Message as MessageModel;
use app\common\model\Users as UsersModel;
use think\Controller;
use think\Db;
use util\Time;

/**
 * 下单控制器
 */
class RotOrder extends Base
{
    /**
     * 首页
     */
    public function index($mid = 0)
    {
        if (\request()->isPost()) {
            $where = [
                ['uid', '=', $this->user['id']],
                ['addtime', 'between', strtotime(date('Y-m-d')) . ',' . time()],
            ];
            // 用户当日收益
            $day_deal = Db::name('xy_convey')
                ->where($where)
                ->where('status', 'in', [1, 3, 5])
                ->sum('commission');
            // 用户当前冻结金额
            $lock_deal = Db::name('xy_users')
                ->where('id', $this->user['id'])
                ->sum('freeze_balance');
            // 用户当前余额
            $price = Db::name('xy_users')
                ->where('id', $this->user['id'])
                ->sum('balance');
            // 完成的订单数
            $day_d_count = Db::name('xy_convey')
                ->where($where)
                ->where('status', 'in', [0, 1, 3, 5])
                ->count('id');
            // 取消的订单数
            $day_d_count_c = Db::name('xy_convey')
                ->where($where)
                ->where('status', 'in', [2, 4])
                ->count('id');
            // 处理中的订单数
            $day_l_count = Db::name('xy_convey')
                ->where($where)
                ->where('status', 5)
                ->count('num');
            // 交易冻结单数
            $team_num = Db::name('xy_reward_log')
                ->where('uid', $this->user['id'])
                ->where('addtime', 'between', Time::today())
                ->where('status', 1)
                ->where('type', 2)
                ->sum('num');
            // 获取分类
            $type = input('type/d', 1);
            if (!empty($type)) {
                $cate = Db::name('xy_goods_cate')->find($type);
            } else {
                $cate = Db::name('xy_goods_cate')->orderRaw('rand()')->find();
            }
            $bili = $cate['bili'];
            // 从用户中取得分配金额
            if ($this->user['task'] >= 1 && $this->user['special'] > 0 && $this->user['task'] > $this->user['deal_special_count'] + 1) {
                $match = get_convey_match($this->user['deal_special_count'] + 1);
                if (!empty($match)) {
                    $bili = $match['proportion'];
                }
            }
            $cate['bili'] = $bili;
            if (!empty($cate)) {
                $data = [
                    'code' => 0,
                    'info' => lang('Successful operation'),
                    'data' => [
                        'day_deal'      => formatNumber($day_deal) ?? 0,
                        'lock_deal'     => formatNumber($lock_deal) ?? 0,
                        'price'         => formatNumber($price) ?? 0,
                        'day_d_count'   => $day_d_count ?? 0,
                        'day_d_count_c' => $day_d_count_c ?? 0,
                        'day_l_count'   => $day_l_count ?? 0,
                        'team_num'      => formatNumber($team_num) ?? 0,
                        'cate'          => $cate ?? [],
                    ],
                ];
            } else {
                $data = [
                    'code' => 1,
                    'info' => lang('No data'),
                ];
            }
            return json($data);
        }
        if (empty($mid)) {
            $mid = (int) input('mid');
        }
        if (!empty($mid)) {
            UsersModel::where('id', $this->user['id'])->update(['special' => $mid]);
            $msg = MessageModel::where('id', $mid)->find();
            if (!empty($msg)) {
                $msg->tip = 0;
                $mo       = $msg->save();
            }
        }
        $map = [
            ['xc.status', '=', 1],
            ['xc.special', '>', 0],
        ];
        $lists = db('xy_convey')
            ->alias('xc')
            ->leftJoin('xy_users u', 'u.id=xc.uid')
            ->where($map)
            ->limit(20)
            ->field('xc.uid,count(xc.uid) sum,u.username')
            ->order('sum DESC')
            ->group('xc.uid')
            ->select();
        $this->conveys = [];
        foreach ($lists as $key => $value) {
            $value['con']        = '用户' . hiddenCharacter($value['username']) . '完成次数' . $value['sum'];
            $this->conveys[$key] = $value;
        }
        return $this->fetch();
    }

    /**
     *提交抢单
     */
    public function submit_order()
    {
        $time = time();
        $tmp  = $this->check_deal();
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
                return json([
                    'code' => 1,
                    // 抢单时间段: {:time_start} - {:time_end}
                    'info' => lang('Grab order period', $vars),
                ]);
            }
        }
        $uid = $this->user['id'];
        // 获取收款地址信息
        $add_id = db('xy_member_address')->where('uid', $uid)->where('is_default', 1)->value('id');
        if (!$add_id) {
            return json([
                'code' => 1,
                // 还没有设置收货地址
                'info' => lang('No shipping address has been set'),
                'url'  => url('Ctrl/receive_site'),
            ]);
        }
        // 检查交易状态
        // $sleep = mt_rand(config('min_time'),config('max_time'));
        // 将账户状态改为等待交易
        $res = db('xy_users')->where('id', $uid)->update(['deal_status' => 2]);
        if ($res === false) {
            return json([
                'code' => 1,
                // 抢单失败，请稍后再试！
                'info' => lang('Rush order failure'),
            ]);
        }
        // 解决sleep造成的进程阻塞问题
        // session_write_close();
        // sleep($sleep);
        //
        $cid   = input('post.cid/d', 1);
        $count = db('xy_goods_list')->where('cid', '=', $cid)->count();
        if ($count < 1) {
            return json([
                'code' => 1,
                // 抢单失败，商品库存不足！
                'info' => lang('Out of stock'),
                'url'  => url('index'),
            ]);
        }
        // $res = model('common/Convey')->create_order($uid, $cid);
        $res = (new ConveyModel)->create_order($uid, $cid);
        return json($res);
    }

    /**
     * 停止抢单
     */
    public function stop_submit_order()
    {
        $uid = session('user_id');
        $res = db('xy_users')->where('id', $uid)->where('deal_status', 2)->update(['deal_status' => 1]);
        if ($res) {
            return json(['code' => 0, 'info' => lang('Successful operation')]);
        } else {
            return json(['code' => 1, 'info' => lang('operation failed')]);
        }
    }
}
