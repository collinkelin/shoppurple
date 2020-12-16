<?php

namespace app\index\controller;

use app\common\model\Convey as ConveyModel;
use app\common\model\Users as UsersModel;
use library\Controller;
use think\facade\Cookie;
use think\facade\Request;
use think\facade\Session;
use util\Time;
use \think\facade\Lang;

/**
 * 验证登录控制器
 */
class Base extends Controller
{
    protected $rule = ['__token__' => 'token'];
    // 无效token
    protected $msg  = ['__token__' => 'Invalid token!'];
    protected $user = [];

    public function __construct()
    {
        $sys_config = sys_config();
        if (empty($sys_config['site_switch']) || $sys_config['site_switch'] == 0) {
            $host = \think\facade\Request::host();
            if (empty(config('site_admin_domain')) || config('site_admin_domain') != $host) {
                $this->assign([
                    'bulletin' => $sys_config['site_switch_bulletin'],
                    'title'    => $sys_config['site_name'],
                ]);
                return $this->fetch('public/maintain');
            }
        }
        // // 设置允许的语言，从数据库读取
        // Lang::setAllowLangList(lang_list(false)['allow']);
        // // 设置当前语言，数据库的默认语言
        // Lang::range(lang_list(false)['default']);

        // 无效token
        $this->msg = ['__token__' => lang('Invalid token!')];
        // parent::__construct();
        $uid = session('user_id');
        if (!$uid && request()->isPost()) {
            // 请先登录
            $this->error(lang('please login first'));
        }
        if (!$uid) {
            $this->redirect('User/login');
        }
        $map = [
            ['id', '=', $uid],
            ['status', '=', 1],
        ];
        $this->user = UsersModel::where($map)->find();
        if (empty($this->user)) {
            // 删除（当前作用域）
            Session::delete('user_id');
            Session::clear();
            Cookie::clear();
            $this->redirect('User/login');
        }
        $this->assign('user', $this->user);
    }

    /**
     * 空操作 用于显示错误页面
     */
    public function _empty($name)
    {
        return $this->fetch($name);
    }

    // 图片上传为base64为的图片
    public function upload_base64($type, $img)
    {
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img, $result)) {
            // 得到图片的后缀
            $type_img = $result[2];
            // 上传 的文件目录
            $App       = new \think\App();
            $new_files = $App->getRootPath() . 'upload' . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m-d') . DIRECTORY_SEPARATOR;
            if (!file_exists($new_files)) {
                // 检查是否有该文件夹，如果没有就创建，并给予最高权限
                // 服务器给文件夹权限
                mkdir($new_files, 0777, true);
            }
            $new_files = check_pic($new_files, ".{$type_img}");
            if (file_put_contents($new_files, base64_decode(str_replace($result[1], '', $img)))) {
                // 上传成功后  得到信息
                $filenames = str_replace('\\', '/', $new_files);
                $file_name = substr($filenames, strripos($filenames, "/upload"));
                return $file_name;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 检查交易状态
     */
    public function check_deal($uid = 0)
    {
        if ($uid == 0) {
            $uid = session('user_id');
        }
        $user = UsersModel::where('id', $uid)->field('id,deal_status,status,balance,level,deal_count,deal_time,deal_reward_count dc,task,special')->find();
        if (empty($user)) {
            return [
                'code' => 1,
                //  用户不存在
                'info' => lang('User does not exist'),
            ];
        }
        if ($user['status'] == 2) {
            // 该账户已被禁用
            return [
                'code' => 1,
                // 该账户已被禁用
                'info' => lang('The account has been disabled'),
            ];
        }
        if ($user['deal_status'] == 0) {
            // 该账户交易功能已被冻结
            return [
                'code' => 1,
                // 该账户交易功能已被冻结
                'info' => lang('The account transaction function has been frozen'),
            ];
        }
        if ($user['deal_status'] == 3) {
            // 该账户存在未完成订单，无法继续抢单！
            return [
                'code' => 1,
                // 该账户存在未完成订单，无法继续抢单！
                'info' => lang('There are outstanding orders in this account, and you cannot continue to grab orders!'),
                'url'  => url('order/index'),
            ];
        }
        if ($user['balance'] < config('deal_min_balance')) {
            // 余额低于%s，无法继续交易
            return [
                'code' => 1,
                // 余额低于%s，无法继续交易
                'info' => lang('Insufficient balance to trade', [config('deal_min_balance')]),
                'url'  => url('recharge/recharge_before'),
            ];
        }
        $today = Time::today();
        $map   = [
            ['uid', '=', $user['id']],
            // ['status', 'in', [0, 1, 3, 5]],
            ['special', '=', 0],
            ['addtime', 'between', $today],
        ];
        // 统计当天完成交易的订单
        $count = ConveyModel::where($map)->count();
        if ($count > 0) {
            // 交易次数限制
            $level  = $user['level'];
            $ulevel = get_levels($level);
            if ($count >= $ulevel['order_num'] && !empty($ulevel['order_num'])) {
                return [
                    'code' => 1,
                    // 当前会员等级交易次数不足!
                    'info' => lang('Insufficient number of current member level transactions'),
                ];
            }
        } else {
            // 重置最后交易时间
            $up = [
                'deal_time'  => strtotime(date('Y-m-d')),
                'deal_count' => 0,
            ];
            UsersModel::where('id', $user['id'])->update($up);
        }
        return false;
    }
}
