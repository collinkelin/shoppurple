<?php

namespace app\admin\controller;

use app\common\model\BalanceLog as BalanceLogModel;
use app\common\model\Deposit as DepositModel;
use app\common\model\GoodsList as GoodsListModel;
use app\common\model\Lang as LangModel;
use app\common\model\Message as MessageModel;
use app\common\model\PayInfo as PayInfoModel;
use app\common\model\Recharge as RechargeModel;
use app\common\model\RewardLog as RewardLogModel;
use app\common\model\Users as UsersModel;
use library\Controller;
use think\Db;
use util\Time;

/**
 * 交易中心
 * Class Users
 * @package app\admin\controller
 */
class Deal extends Controller
{

    public function initialize()
    {
        $host = \think\facade\Request::host();
        if (config('site_admin_domain') && config('site_admin_domain') != $host) {
            $this->redirect('@index');
        }
    }

    /**
     * 交易控制
     * @auth true
     * @menu true
     */
    public function deal_console()
    {
        $this->title = '交易控制';
        if (request()->isPost()) {
            $deal_min_balance  = input('post.deal_min_balance/d', 0);
            $deal_timeout      = input('post.deal_timeout/d', 0);
            $deal_min_num      = input('post.deal_min_num/d', 0);
            $deal_max_num      = input('post.deal_max_num/d', 0);
            $deal_count        = input('post.deal_count/d', 0);
            $deal_reward_count = input('post.deal_reward_count/d', 0);
            $deal_feedze       = input('post.deal_feedze/d', 0);
            $deal_error        = input('post.deal_error/d', 0);
            $deal_commission   = input('post.deal_commission/f', 0);
            $_1reward          = input('post.1_reward/f', 0);
            $_2reward          = input('post.2_reward/f', 0);
            $_3reward          = input('post.3_reward/f', 0);
            $_1_d_reward       = input('post.1_d_reward/f', 0);
            $_2_d_reward       = input('post.2_d_reward/f', 0);
            $_3_d_reward       = input('post.3_d_reward/f', 0);
            $_4_d_reward       = input('post.4_d_reward/f', 0);
            $_5_d_reward       = input('post.5_d_reward/f', 0);
            $time_start        = input('post.time_start/s', '');
            $time_end          = input('post.time_end/s', '');

            //可以加上限制条件
            if ($deal_commission > 1 || $deal_commission < 0) {
                return $this->error('参数错误');
            }

            setconfig(['deal_min_balance'], [$deal_min_balance]);
            setconfig(['deal_timeout'], [$deal_timeout]);
            setconfig(['deal_min_num'], [$deal_min_num]);
            setconfig(['deal_max_num'], [$deal_max_num]);
            setconfig(['deal_reward_count'], [$deal_reward_count]);
            setconfig(['deal_count'], [$deal_count]);
            setconfig(['deal_feedze'], [$deal_feedze]);
            setconfig(['deal_error'], [$deal_error]);
            setconfig(['deal_commission'], [$deal_commission]);
            setconfig(['1_reward'], [$_1reward]);
            setconfig(['2_reward'], [$_2reward]);
            setconfig(['3_reward'], [$_3reward]);
            setconfig(['1_d_reward'], [$_1_d_reward]);
            setconfig(['2_d_reward'], [$_2_d_reward]);
            setconfig(['3_d_reward'], [$_3_d_reward]);
            setconfig(['4_d_reward'], [$_4_d_reward]);
            setconfig(['5_d_reward'], [$_5_d_reward]);
            setconfig(['vip_1_commission'], [input('post.vip_1_commission/f')]);
            setconfig(['vip_2_commission'], [input('post.vip_2_commission/f')]);
            setconfig(['vip_2_num'], [input('post.vip_2_num/f')]);
            setconfig(['vip_3_commission'], [input('post.vip_3_commission/f')]);
            setconfig(['vip_3_num'], [input('post.vip_3_num/f')]);
            // setconfig(['master_cardnum'], [input('post.master_cardnum')]);
            // setconfig(['master_name'], [input('post.master_name')]);
            // setconfig(['master_bank'], [input('post.master_bank')]);
            // setconfig(['master_bk_address'], [input('post.master_bk_address')]);
            setconfig(['deal_zhuji_time'], [input('post.deal_zhuji_time')]);
            setconfig(['deal_shop_time'], [input('post.deal_shop_time')]);
            setconfig(['time_start'], [$time_start]);
            setconfig(['time_end'], [$time_end]);
            // setconfig(['app_url'], [input('post.app_url')]);

            return $this->success('操作成功!');
        }

        //var_dump(config('master_name'));die;

        return $this->fetch();
    }

    /**
     * 商品管理
     *@auth true
     *@menu true
     */
    public function goods_list()
    {
        $this->title = '商品管理';

        $this->cate = db('xy_goods_cate')->order('addtime asc')->select();
        $where      = [];
        //var_dump($this->cate);die;
        $query = $this->_query('xy_goods_list');
        if (input('title/s', '')) {
            $where[] = ['goods_name', 'like', '%' . input('title/s', '') . '%'];
        }

        if (input('cid/d', '')) {
            $where[] = ['cid', '=', input('cid/d', '')];
        }

        //var_dump($where);die;
        $query->where($where)->order('addtime DESC')->page();

    }

    /**
     * 商品管理
     *@auth true
     *@menu true
     */
    public function goods_cate()
    {
        $this->title = '分类管理';
        $this->_query('xy_goods_cate')->page();
    }

    /**
     * 添加商品
     *@auth true
     *@menu true
     */
    public function add_goods()
    {
        if (\request()->isPost()) {
            $this->applyCsrfToken(); //验证令牌
            $shop_name   = input('post.shop_name/s', '');
            $goods_name  = input('post.goods_name/s', '');
            $goods_price = input('post.goods_price/f', 0);
            $goods_pic   = input('post.goods_pic/s', '');
            $goods_info  = input('post.goods_info/s', '');
            $cid         = input('post.cid/d', 1);
            $res         = (new GoodsListModel)->submit_goods($shop_name, $goods_name, $goods_price, $goods_pic, $goods_info, $cid);
            if ($res['code'] === 0) {
                return $this->success($res['info'], '/admin.html#/admin/deal/goods_list.html');
            } else {
                return $this->error($res['info']);
            }
        }
        $this->cate = db('xy_goods_cate')->order('addtime asc')->select();
        return $this->fetch();
    }

    /**
     * 添加商品
     *@auth true
     *@menu true
     */
    public function add_cate()
    {
        if (\request()->isPost()) {
            $this->applyCsrfToken(); //验证令牌
            $name = input('post.name/s', '');
            $bili = input('post.bili/s', '');
            $info = input('post.cate_info/s', '');
            $min  = input('post.min/s', '');
            $pic  = input('post.cate_pic/s', '');

            $res = $this->submit_cate($name, $bili, $info, $min, $pic, 0);
            if ($res['code'] === 0) {
                return $this->success($res['info'], '/admin.html#/admin/deal/goods_cate.html');
            } else {
                return $this->error($res['info']);
            }

        }
        return $this->fetch();
    }

    /**
     * 添加商品
     *
     * @param string $shop_name
     * @param string $goods_name
     * @param string $goods_price
     * @param string $goods_pic
     * @param string $goods_info
     * @param string $id 传参则更新数据,不传则写入数据
     * @return array
     */
    public function submit_cate($name, $bili, $info, $min, $pic, $id)
    {
        if (!$name) {
            return ['code' => 1, 'info' => ('请输入分类名称')];
        }

        if (!$bili) {
            return ['code' => 1, 'info' => ('请输入比例')];
        }

        $data = [
            'name'      => $name,
            'bili'      => $bili,
            'cate_info' => $info,
            'addtime'   => time(),
            'min'       => $min,
            'cate_pic'  => $pic,
        ];
        if (!$id) {
            $res = Db::table('xy_goods_cate')->insert($data);
        } else {
            $res = Db::table('xy_goods_cate')->where('id', $id)->update($data);
        }
        if ($res) {
            return ['code' => 0, 'info' => '操作成功!'];
        } else {
            return ['code' => 1, 'info' => '操作失败!'];
        }

    }

    /**
     * 编辑商品信息
     * @auth true
     * @menu true
     */
    public function edit_goods($id)
    {
        $id = (int) $id;
        if (\request()->isPost()) {
            $this->applyCsrfToken(); //验证令牌
            $shop_name   = input('post.shop_name/s', '');
            $goods_name  = input('post.goods_name/s', '');
            $goods_price = input('post.goods_price/f', 0);
            $goods_pic   = input('post.goods_pic/s', '');
            $goods_info  = input('post.goods_info/s', '');
            $id          = input('post.id/d', 0);
            $cid         = input('post.cid/d', 0);
            $res         = model('common/GoodsList')->submit_goods($shop_name, $goods_name, $goods_price, $goods_pic, $goods_info, $cid, $id);
            if ($res['code'] === 0) {
                return $this->success($res['info'], '/admin.html#/admin/deal/goods_list.html');
            } else {
                return $this->error($res['info']);
            }

        }
        $info       = db('xy_goods_list')->find($id);
        $this->cate = db('xy_goods_cate')->order('addtime asc')->select();
        $this->assign('cate', $this->cate);
        $this->assign('info', $info);
        return $this->fetch();
    }
    /**
     * 编辑商品信息
     * @auth true
     * @menu true
     */
    public function edit_cate($id)
    {
        $id = (int) $id;
        if (\request()->isPost()) {
            $this->applyCsrfToken(); //验证令牌
            $name = input('post.name/s', '');
            $bili = input('post.bili/s', '');
            $info = input('post.cate_info/s', '');
            $min  = input('post.min/s', '');
            $pic  = input('post.cate_pic/s', '');

            $res = $this->submit_cate($name, $bili, $info, $min, $pic, $id);
            if ($res['code'] === 0) {
                return $this->success($res['info'], '/admin.html#/admin/deal/goods_cate.html');
            } else {
                return $this->error($res['info']);
            }

        }
        $info = db('xy_goods_cate')->find($id);
        $this->assign('info', $info);
        return $this->fetch();
    }

    /**
     * 更改商品状态
     * @auth true
     */
    public function edit_goods_status()
    {
        $this->applyCsrfToken();
        $this->_form('xy_goods_list', 'form');
    }

    /**
     * 删除商品
     * @auth true
     */
    public function del_goods()
    {
        $para = input('');
        $this->applyCsrfToken();
        if (empty($para['id'])) {
            $this->error('参数错误');
        }
        $res = Db::table('xy_goods_list')->delete($para['id']);
        // $res   = Db::name('xy_goods_list')->where($map)->delete();
        if ($res) {
            $this->success('操作成功!');
        } else {
            $this->error('操作失败!');
        }
        // $this->_delete('xy_goods_list');
    }
    /**
     * 删除商品
     * @auth true
     */
    public function del_cate()
    {
        $this->applyCsrfToken();
        $para = input('');
        if (empty($para['id'])) {
            $this->error('参数错误');
        }
        $map   = [];
        $map[] = ['id', '=', $para['id']];
        $res   = Db::name('xy_goods_cate')->where($map)->delete();
        if ($res) {
            $this->success('操作成功!');
        } else {
            $this->error('操作失败!');
        }
    }

    /**
     * 充值管理
     * @auth true
     * @menu true
     */
    public function user_recharge()
    {
        $this->title = '充值管理';
        $param       = input('');
        $query       = $this->_query('xy_recharge')->alias('xr');
        $where       = [];
        if (input('oid/s', '')) {
            $where[] = ['xr.id', 'like', '%' . input('oid', '') . '%'];
        }

        if (input('tel/s', '')) {
            $where[] = ['xr.tel', 'like', '%' . input('tel', '') . '%'];
        }

        if (input('nominee/s', '')) {
            $where[] = ['xr.nominee', 'like', '%' . input('nominee', '') . '%'];
        }

        if (input('username/s', '')) {
            $where[] = ['u.username', 'like', '%' . input('username/s', '') . '%'];
        }

        if (input('parent/s', '')) {
            $where[] = ['up.username', 'like', '%' . input('parent/s', '') . '%'];
        }

        if (!empty($param['status'])) {
            $where[] = ['xr.status', '=', $param['status']];
        }

        if (input('addtime/s', '')) {
            $arr     = explode(' - ', input('addtime/s', ''));
            $where[] = ['xr.addtime', 'between', [strtotime($arr[0]), strtotime($arr[1] . ' 23:59:59')]];
        }
        $sort = 'xr.addtime DESC';
        if (!empty($param['sort'])) {
            $sort = 'xr.' . $param['sortKey'] . ' ' . $param['sort'];
        }
        $map          = $where;
        $map[]        = ['xr.status', '=', 2];
        $map[]        = ['xr.is_vip', '=', 0];
        $this->amount = Db::name('xy_recharge')->alias('xr')->leftJoin('xy_users u', 'u.id=xr.uid')->leftJoin('xy_users up', 'up.id=u.parent_first')->where($map)->sum('xr.num');
        $query->leftJoin('xy_users u', 'u.id=xr.uid')
            ->leftJoin('xy_users up', 'up.id=u.parent_first')
            ->leftJoin('xy_pay p', 'p.id=xr.pay_name')
            ->leftJoin('xy_pay_info pi', 'pi.id=xr.payinfo_id')
            ->field('xr.*,u.username,u.parent_first,u.parent_id,p.name pay_name,pi.bank_name,pi.card_number')
            ->where($where)
            ->order($sort)
            ->page();
    }

    public function viewpayinfo()
    {
        $payinfo_id = input('infoid');
        $map        = [
            ['id', '=', $payinfo_id],
            ['status', '=', 1],
        ];
        $pay = PayInfoModel::where($map)->find();
        return view('payinfo', ['info' => $pay]);
    }

    /**
     * 审核充值订单
     * @auth true
     */
    public function edit_recharge()
    {
        $para = input('');
        if (empty($para['id'])) {
            $this->error('参数错误');
        }
        $oinfo = RechargeModel::where('id', $para['id'])->find();
        if (empty($oinfo)) {
            $this->error('订单不存在');
        }
        if ($oinfo->status != 1) {
            $this->error('订单已处理!请不要重复处理!');
        }
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $today = Time::today();
            Db::startTrans();
            $oinfo->status = $para['status'];
            if (!empty($para['description'])) {
                $oinfo->description = $para['description'];
            } else {
                $oinfo->description = '';
            }
            $res  = $oinfo->save();
            $user = UsersModel::where('id', $oinfo['uid'])->find();
            $ou   = true;
            if ($para['status'] == 2) {
                if ($oinfo['is_vip']) {
                    $du = [
                        'level'      => $oinfo['level'],
                        'base_level' => $oinfo['level'],
                    ];
                    $res1 = UsersModel::where('id', $user['id'])->update($du);
                    if ($oinfo['pay_name'] == 0) {
                        if ($user['balance'] >= $oinfo['num']) {
                            $upvip = [
                                'balance' => Db::raw('balance-' . $oinfo['num']),
                            ];
                            $ou = UsersModel::where('id', $user['id'])->update($upvip);
                        } else {
                            Db::rollback();
                            $this->error('用户余额不足!');
                        }
                    }
                } else {
                    $up = [
                        'balance'        => Db::raw('balance+' . $oinfo['num']),
                        'recharge_total' => Db::raw('recharge_total+' . $oinfo['num']),
                    ];
                    $res1 = UsersModel::where('id', $oinfo['uid'])->update($up);
                }
                $data = [
                    'uid'    => $oinfo['uid'],
                    'oid'    => $oinfo['id'],
                    'num'    => $oinfo['num'],
                    'type'   => 1,
                    'status' => 1,
                ];
                $log = BalanceLogModel::where($data)->find();
                if (empty($log)) {
                    $res2 = BalanceLogModel::create($data);
                } else {
                    Db::rollback();
                    $this->error('操作失败!订单可能已经处理过了');
                }
            } elseif ($para['status'] == 3) {
                $tpl = Db::name('system_message_tpl')->where(['name' => 'Order return', 'range' => getRange()])->cache(true)->find();
                $msg = [
                    'uid'     => $oinfo['uid'],
                    'type'    => 2,
                    'title'   => $tpl['title'],
                    'content' => sprintf($tpl['content'], $oinfo['id']),
                ];
                $res1 = MessageModel::create($msg);
            }
            if ($res && $res1 && $ou) {
                Db::commit();
                if ($oinfo['is_vip'] == 0) {
                    // if ($para['status'] == 2) {
                    /************* 发放推广奖励 *********/
                    $uinfo = UsersModel::where('id', $oinfo['uid'])->field('id,active')->find();
                    if ($uinfo['active'] === 0) {
                        UsersModel::where('id', $uinfo['id'])->update(['active' => 1]);
                        //将账号状态改为已发放推广奖励
                        $userList = (new UsersModel)->parent_user($uinfo['id'], 3);
                        if ($userList) {
                            foreach ($userList as $v) {
                                if ($v['status'] === 1 && ($oinfo['num'] * config($v['lv'] . '_reward') != 0)) {
                                    $log = [
                                        'uid'    => $v['id'],
                                        'sid'    => $uinfo['id'],
                                        'oid'    => $oinfo['id'],
                                        'num'    => $oinfo['num'] * config($v['lv'] . '_reward'),
                                        'lv'     => $v['lv'],
                                        'type'   => 1,
                                        'status' => 1,
                                    ];
                                    RewardLogModel::create($log);
                                }
                            }
                        }
                    }
                    /************* 发放推广奖励 *********/
                    // }
                }
                $this->success('操作成功!');
            } else {
                Db::rollback();
                $this->error('操作失败!');
            }
        }
        $description = LangModel::where('position', 'recharge-refuse-info')->select();
        return view('refuse_info', ['description' => $description, 'info' => $oinfo, '_csrf_' => systoken('admin/deal/edit_recharge')]);
    }

    /**
     * 提现管理
     * @auth true
     * @menu true
     */
    public function deposit_list()
    {
        $this->title = '提现列表';
        $param       = input('');
        // sendMsg(2, 46, [], ['dsadsaldjsa']);
        $where = [];
        if (input('oid/s', '')) {
            $where[] = ['xd.id', 'like', '%' . input('oid', '') . '%'];
        }

        if (input('username/s', '')) {
            $where[] = ['u.username', 'like', '%' . input('username/s', '') . '%'];
        }

        if (input('parent/s', '')) {
            $where[] = ['up.username', 'like', '%' . input('parent/s', '') . '%'];
        }

        if (input('addtime/s', '')) {
            $arr     = explode(' - ', input('addtime/s', ''));
            $where[] = ['xd.addtime', 'between', [strtotime($arr[0]), strtotime($arr[1] . ' 23:59:59')]];
        }
        if (!empty($param['status'])) {
            $where[] = ['xd.status', '=', $param['status']];
        }

        $sort = 'xd.addtime DESC';
        if (!empty($param['sort'])) {
            $sort = 'xd.' . $param['sortKey'] . ' ' . $param['sort'];
        }
        $map = $where;
        if (input('name/s', '')) {
            $where[] = ['bk.name', 'like', '%' . input('name/s', '') . '%'];
        }

        if (input('tel/s', '')) {
            $where[] = ['bk.tel', 'like', '%' . input('tel/s', '') . '%'];
        }
        $map[]         = ['xd.status', '=', 2];
        $this->amount  = Db::name('xy_deposit')->alias('xd')->leftJoin('xy_users u', 'u.id=xd.uid')->leftJoin('xy_users up', 'up.id=u.parent_first')->where($map)->sum('xd.num');
        $this->arrival = Db::name('xy_deposit')->alias('xd')->leftJoin('xy_users u', 'u.id=xd.uid')->leftJoin('xy_users up', 'up.id=u.parent_first')->where($map)->sum('xd.arrival');
        $this->_query('xy_deposit')
            ->alias('xd')
            ->leftJoin('xy_pay p', 'p.id=xd.pay_id')
            ->leftJoin('xy_users u', 'u.id=xd.uid')
            ->leftJoin('xy_users up', 'up.id=u.parent_first')
            ->leftJoin('xy_bankinfo bk', 'bk.id=xd.bk_id')
            ->field('xd.*,u.username,u.wx_ewm,u.parent_first,u.parent_id,u.zfb_ewm,bk.bank_name,bk.branch_name,bk.branch_number,bk.card_number,bk.name_e,bk.name,bk.tel,u.id uid,p.name pname,p.type ptype')
            ->where($where)
            ->order($sort)
            ->page();
    }

    /**
     * [deposit_send 发送提现资料]
     * @return [type] [description]
     */
    public function deposit_send()
    {
        $id   = input('id');
        $info = Db::name('xy_deposit')->find($id);
        $tpls = Db::name('system_message_tpl')->where(['type' => 1, 'range' => getRange()])->order('title ASC')->select();
        return view('', ['info' => $info, 'tpls' => $tpls]);
    }

    public function do_send()
    {
        $this->applyCsrfToken();
        $today = Time::today();
        $id    = input('id');
        $tid   = input('tid');
        $msg   = input('msg');
        if (empty($id) || empty($tid) || empty($msg)) {
            $this->error('参数错误!');
        }
        $deposit = DepositModel::where('id', $id)->find();
        if (empty($deposit)) {
            $this->error('订单不存在');
        }
        if ($deposit->status != 1) {
            $this->error('订单已处理!请不要重复处理!');
        }
        $tpl = db('system_message_tpl')->where('id', $tid)->cache(true)->find();
        $map = [
            ['id', '=', $deposit['uid']],
        ];
        $user = UsersModel::where($map)->find();
        Db::startTrans();
        $res = Db::name('xy_message')->insert([
            'uid'     => $deposit['uid'],
            'type'    => 2,
            'title'   => $tpl['title'],
            'content' => sprintf($tpl['content'], $msg),
            'addtime' => time(),
        ]);
        $deposit->status = 2;
        $user['deposit_total'] += $deposit['num'];
        $ou = $user->save();
        $od = $deposit->save();
        if ($ou && $od) {
            Db::commit();
            $this->success('操作成功!');
        } else {
            Db::rollback();
            $this->error('操作失败!');
        }
    }

    /**
     * 处理提现订单
     * @auth true
     */
    public function do_deposit()
    {
        $para = input('');
        if (empty($para['id'])) {
            $this->error('参数错误');
        }
        $deposit = DepositModel::where('id', $para['id'])->find();
        if (empty($deposit)) {
            $this->error('订单不存在');
        }
        if ($deposit->status != 1) {
            $this->error('订单已处理!请不要重复处理!');
        }
        if (request()->isPost()) {
            $this->applyCsrfToken();
            $today             = Time::today();
            $deposit['status'] = $para['status'];
            $map               = [
                ['id', '=', $deposit['uid']],
            ];
            $user = UsersModel::where($map)->find();
            switch ($para['status']) {
                case 2:
                    $user['deposit_total'] = Db::raw('deposit_total+' . $deposit['num']);
                    break;
                case 3:
                    $user['balance'] = Db::raw('balance+' . $deposit['num']);
                    if (!empty($para['description'])) {
                        $deposit->description = $para['description'];
                    } else {
                        $deposit->description = '';
                    }
                    break;
            }
            Db::startTrans();
            $ou = $user->save();
            $od = $deposit->save();
            if ($ou && $od) {
                Db::commit();
                $this->success('操作成功!');
            } else {
                Db::rollback();
                $this->error('操作失败!');
            }
        }
        $description = LangModel::where('position', 'deposit-refuse-info')->select();
        return view('refuse_info', ['description' => $description, 'info' => $deposit, '_csrf_' => systoken('admin/deal/do_deposit')]);
    }

    /**
     * 一键返佣
     * @auth true
     */
    public function do_commission()
    {
        $this->applyCsrfToken();
        $info = Db::name('xy_convey')
            ->field('id oid,uid,num,commission cnum')
            ->where([
                ['c_status', 'in', [0, 2]],
                ['status', 'in', [1, 3]],
                //['endtime','between','??']    //时间限制
            ])
            ->select();
        if (!$info) {
            return $this->error('当前没有待返佣订单!');
        }

        try {
            foreach ($info as $k => $v) {
                Db::startTrans();
                $map = [
                    ['id', '=', $v['id']],
                    ['status', '=', 1],
                ];
                $up  = ['balance' => Db::raw('balance+' . ($v['num'] + $v['cnum']))];
                $res = UsersModel::where($map)->update($up);
                if ($res) {
                    $res1 = Db::name('xy_balance_log')->insert([
                        //记录返佣信息
                        'uid'     => $v['uid'],
                        'oid'     => $v['oid'],
                        'num'     => $v['num'] + $v['cnum'],
                        'type'    => 3,
                        'addtime' => time(),
                    ]);
                    Db::name('xy_convey')->where('id', $v['oid'])->update(['c_status' => 1]);
                } else {
                    $res1 = Db::name('xy_convey')->where('id', $v['oid'])->update(['c_status' => 2]); //记录账号异常
                }
                if ($res !== false && $res1) {
                    Db::commit();
                } else {
                    Db::rollback();
                }

            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        return $this->success('操作成功!');
    }

    /**
     * 交易流水
     * @auth true
     * @menu true
     */
    public function order_log()
    {
        $this->title = '交易流水';
        $this->_query('xy_balance_log')->page();
    }

    /**
     * 团队返佣
     * @auth true
     * @menu true
     */
    public function team_reward()
    {

    }
}
