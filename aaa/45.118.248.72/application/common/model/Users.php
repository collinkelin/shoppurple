<?php

namespace app\common\model;

use app\common\model\Deposit as DepositModel;
use app\common\model\Recharge as RechargeModel;
use app\common\model\UserError as UserErrorModel;
use app\common\validation\Validations;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use think\Db;
use think\Image;
use think\Model;
use util\Time;

class Users extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'xy_users';

    // 默认主键
    protected $pk = 'id';

    protected $today;

    protected $observerClass = 'app\common\event\Users';

    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }

    public function getRechargeDaysAttr($value, $data)
    {
        // recharge_num
        $rmap = [
            ['uid', '=', $data['id']],
            ['status', '=', 2],
            ['is_vip', '=', 0],
            ['addtime', '>', Time::today()[0]],
        ];
        $sum = RechargeModel::where($rmap)->sum('num');
        if (empty($sum)) {
            $sum = 0;
        }
        return $sum;
    }

    public function getDepositDaysAttr($value, $data)
    {
        // deposit_num
        $rmap = [
            ['uid', '=', $data['id']],
            ['status', '=', 2],
            ['addtime', '>', Time::today()[0]],
        ];
        $sum = DepositModel::where($rmap)->sum('num');
        if (empty($sum)) {
            $sum = 0;
        }
        return $sum;
    }

    /**
     * 添加会员
     *
     * @param string $username
     * @param string $is_mobile
     * @param string $pwd
     * @param int    $parent_id
     * @param string $token
     * @return array
     */
    public function add_users($data)
    {
        if ($data['is_mobile'] == 0) {
            try {
                // 验证（如果验证不通过，会抛出异常）
                $Validations = new Validations();
                $verify      = $Validations::validate($data, [
                    // 此处验证规则
                    // 参数 account 应该为 email
                    'username' => 'Required|Email|Alias:' . lang('username', [], '', 3),
                    'pwd'      => 'Required|StrLenGeLe:6,20|Alias:' . lang('pwd', [], '', 3),
                ]);
            } catch (\Exception $e) {
                return ['code' => 1, 'info' => $e->getMessage()];
            }
        } else {
            if (!extract_phone_number($data['username'], config('system.mobile_area'))) {
                return ['code' => 1, 'info' => lang('Mobile number format is incorrect')];
            }
            try {
                // 验证（如果验证不通过，会抛出异常）
                $Validations = new Validations();
                $verify      = $Validations::validate($data, [
                    // 此处验证规则
                    'pwd' => 'Required|StrLenGeLe:6,20|Alias:' . lang('pwd', [], '', 3),
                ]);
            } catch (\Exception $e) {
                return ['code' => 1, 'info' => $e->getMessage()];
            }
        }
        $tmp = Db::table($this->table)->where(['username' => $data['username']])->count();
        if ($tmp) {
            return ['code' => 1, 'info' => lang('Account already exists')];
        }
        if (isset($data['__token__'])) {
            unset($data['__token__']);
        }
        // 开启事务
        Db::startTrans();
        // 生成盐
        $salt = rand(0, 99999);
        // 生成邀请码
        $invite_code         = self::create_invite_code();
        $data['pwd']         = sha1($data['pwd'] . $salt . config('pwd_str'));
        $data['salt']        = $salt;
        $data['addtime']     = time();
        $data['invite_code'] = $invite_code;
        if (isset($data['pwd2']) && !empty($data['pwd2'])) {
            // 生成盐
            $salt2         = rand(0, 99999);
            $data['pwd2']  = sha1($data['pwd2'] . $salt2 . config('pwd_str'));
            $data['salt2'] = $salt2;
        }
        $res   = Db::table($this->table)->insertGetId($data);
        $res2  = true;
        $puser = [];
        $uv    = true;
        if (isset($data['parent_id']) && !empty($data['parent_id'])) {
            $update = [
                'childs'            => Db::raw('childs+1'),
                // 'level'             => $puser['level'] ?? 0,
                'deal_reward_count' => Db::raw('deal_reward_count+' . config('deal_reward_count')),
            ];
            $res2 = self::where('id', $data['parent_id'])->update($update);
            $uv   = true;
            // $ups  = self::set_vips($data['parent_id']);
            // if (!empty($ups)) {
            //     if (count($ups) == 1) {
            //         $uv = self::save($ups[0]);
            //     } else {
            //         $uv = self::saveAll($ups);
            //     }
            // }
        }
        // 生成二维码
        self::create_qrcode($invite_code, $res);
        if ($res && $res2 && $uv) {
            // 提交事务
            Db::commit();
            return ['code' => 0, 'info' => lang('Successful operation')];
        } else {
            // 回滚事务
            Db::rollback();
        }
        return ['code' => 1, 'info' => lang('operation failed')];
    }

    /**
     * 编辑用户
     *
     * @param int       $id
     * @param string    $username
     * @param string    $is_mobile
     * @param string    $pwd
     * @param int       $parent_id
     * @param string    $token
     * @return array
     */
    public function edit_users($data)
    {
        $tmp = Db::table($this->table)->where(['username' => $data['username']])->where('id', '<>', $data['id'])->count();
        if ($tmp) {
            return ['code' => 1, 'info' => lang('Account already exists')];
        }
        foreach ($data as $key => $value) {
            if (empty($value) && $value != 0) {
                unset($data[$key]);
            }
        }
        if ($data['parent_id']) {
            $data['parent_id'] = Db::table($this->table)->where('id', $data['parent_id'])->value('id');
            if (!$data['parent_id']) {
                return ['code' => 1, 'info' => lang('Superior does not exist')];
            }
        }
        $rule = [
            // 此处验证规则
            // 参数 account 应该为 email
            'username' => 'Email',
        ];
        if (!empty($data['pwd'])) {
            $rule['pwd'] = 'StrLenGeLe:6,20|Alias:' . lang('pwd', [], '', 3);
        }
        if (!empty($data['pwd2'])) {
            $rule['pwd2'] = 'StrLenGeLe:6,20|Alias:' . lang('pwd2', [], '', 3);
        }
        if (empty($data['is_mobile']) || $data['is_mobile'] == 0) {
            try {
                // 验证（如果验证不通过，会抛出异常）
                $Validations = new Validations();
                $verify      = $Validations::validate($data, $rule, true);
            } catch (\Exception $e) {
                return ['code' => 1, 'info' => $e->getMessage()];
            }
        } else {
            if (!extract_phone_number($data['username'], config('system.mobile_area'))) {
                return ['code' => 1, 'info' => lang('Mobile number format is incorrect')];
            }
            try {
                // 验证（如果验证不通过，会抛出异常）
                $Validations = new Validations();
                $verify      = $Validations::validate($data, [
                    // 此处验证规则
                    'pwd' => 'StrLenGeLe:6,20',
                ]);
            } catch (\Exception $e) {
                return ['code' => 1, 'info' => $e->getMessage()];
            }
        }

        if (!empty($data['pwd'])) {
            // 生成盐
            $salt         = rand(0, 99999);
            $data['pwd']  = sha1($data['pwd'] . $salt . config('pwd_str'));
            $data['salt'] = $salt;
        }
        if (!empty($data['pwd2'])) {
            $salt2         = rand(0, 99999);
            $data['pwd2']  = sha1($data['pwd2'] . $salt2 . config('pwd_str'));
            $data['salt2'] = $salt2;
        }
        $res = Db::table($this->table)->where('id', $data['id'])->strict(false)->update($data);
        if ($res) {
            return ['code' => 0, 'info' => lang('Successful operation')];
        } else {
            return ['code' => 1, 'info' => lang('operation failed')];
        }
    }

    public function edit_users_status($id, $status)
    {
        $status = intval($status);
        $id     = intval($id);

        if (!in_array($status, [1, 2])) {
            return ['code' => 1, 'info' => lang('Parameter error')];
        }

        if ($status == 2) {
            // 查看有无未完成的订单
            // if ($num > 0) {
            //     $this->error('该用户尚有未完成的支付订单！');
            // }
        }

        $res = Db::table($this->table)->where('id', $id)->update(['status' => $status]);
        if ($res !== false) {
            return ['code' => 0, 'info' => lang('Successful operation')];
        } else {
            return ['code' => 1, 'info' => lang('operation failed')];
        }

    }

    //生成邀请码
    public static function create_invite_code()
    {
        $str      = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $rand_str = substr(str_shuffle($str), 0, 6);
        $num      = Db::table('xy_users')->where('invite_code', $rand_str)->count();
        if ($num)
        // return $this->create_invite_code();
        {
            return self::create_invite_code();
        } else {
            return $rand_str;
        }
    }

    //生成用户二维码
    public static function create_qrcode($invite_code, $user_id)
    {
        $time = time();
        $dir  = './upload/qrcode/user/' . $user_id . '/' . $time . '.png';
        if (file_exists($dir)) {
            return;
        }

        $qrCode = new QrCode(SITE_URL . url('@index/user/register/invite_code/' . $invite_code));
        // 设置二维码大小
        $qrCode->setSize(sysconf('share_qrcode_size'));
        $qrCode->setWriterByName('png');
        $qrCode->setEncoding('UTF-8');
        // 纠错级别
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
        // 设置前景色
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        // 设置背景色
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        // $qrCode->setLabel('Scan the code', 16, \Env::get('root_path') . 'public/public/fz.TTF', LabelAlignment::CENTER());
        // $qrCode->setLogoPath(__DIR__ . '/../assets/images/symfony.png');
        // $qrCode->setLogoSize(150, 200);
        // 设置验证结果
        $qrCode->setValidateResult(false);
        // 设置圆块大小
        $qrCode->setRoundBlockSize(false);
        // 设置边界
        $qrCode->setMargin(10);
        $dir = './upload/qrcode/user/' . $user_id;
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $qrCode->writeFile($dir . '/' . $invite_code . '.png');

        $qr     = \Env::get('root_path') . 'public/upload/qrcode/user/' . $user_id . '/' . $invite_code . '.png';
        $bgimg1 = \Env::get('root_path') . 'public/public/img/user_share.png';

        $qrcode = Image::open($qr);
        // 返回二维码的宽度
        $qrwidth = $qrcode->width();
        $image   = Image::open($bgimg1);
        // 返回图片的宽度
        $width = $image->width();
        // 左 上
        $image->water($qr, [sysconf('share_qrcode_left'), sysconf('share_qrcode_top')])
            ->text(sysconf('share_title'),
                \Env::get('root_path') . 'public/public/A-OTF-ShinGoPr5-Heavy-2.otf',
                sysconf('share_title_size'),
                '#FFFFFF',
                Image::WATER_CENTER,
                [sysconf('share_title_left'), sysconf('share_title_top')]
            )
            ->text($invite_code,
                \Env::get('root_path') . 'public/public/fz.TTF',
                sysconf('share_invite_size'),
                '#000000',
                Image::WATER_CENTER,
                // 左 上
                [sysconf('share_invite_left'), sysconf('share_invite_top')]
            )
            ->save(\Env::get('root_path') . 'public/upload/qrcode/user/' . $user_id . '/' . $time . '.png');
        $res = Db::table('xy_users')->where('id', $user_id)->data(['invite_pic' => '/upload/qrcode/user/' . $user_id . '/' . $time . '.png'])->update();
    }

    /**
     * 重置密码
     */
    public function reset_pwd($data, $type = 1)
    {
        if ($data['is_mobile'] == 0) {
            try {
                // 验证（如果验证不通过，会抛出异常）
                $Validations = new Validations();
                $verify      = $Validations::validate($data, [
                    // 此处验证规则
                    // 参数 account 应该为 email
                    'username' => 'Required|Email|Alias:' . lang('username', [], '', 3),
                    'pwd'      => 'Required|StrLenGeLe:6,20|Alias:' . lang('pwd', [], '', 3),
                ]);
            } catch (\Exception $e) {
                return ['code' => 1, 'info' => $e->getMessage()];
            }
        } else {
            if (!extract_phone_number($data['username'], config('system.mobile_area'))) {
                return ['code' => 1, 'info' => lang('Mobile number format is incorrect')];
            }
            try {
                // 验证（如果验证不通过，会抛出异常）
                $Validations = new Validations();
                $verify      = $Validations::validate($data, [
                    // 此处验证规则
                    'pwd' => 'Required|StrLenGeLe:6,20|Alias:' . lang('pwd', [], '', 3),
                ]);
            } catch (\Exception $e) {
                return ['code' => 1, 'info' => $e->getMessage()];
            }
        }

        $user_id = Db::table($this->table)->where(['username' => $data['username']])->value('id');
        if (!$user_id) {
            return ['code' => 1, 'info' => lang('User does not exist')];
        }

        $salt = mt_rand(0, 99999);
        if ($type == 1) {
            $data = [
                'pwd'  => sha1($data['pwd'] . $salt . config('pwd_str')),
                'salt' => $salt,
            ];
        } elseif ($type == 2) {
            $data = [
                'pwd2'  => sha1($data['pwd'] . $salt . config('pwd_str')),
                'salt2' => $salt,
            ];
        }

        $res = Db::table($this->table)->where('id', $user_id)->data($data)->update();

        if ($res) {
            return ['code' => 0, 'info' => lang('Successful operation')];
        } else {
            return ['code' => 1, 'info' => lang('operation failed')];
        }
    }

    // 获取上级会员
    public function parent_user($uid, $num = 1, $lv = 1)
    {
        $data = [];
        $user = self::where('id', $uid)->find();
        do {
            if (!empty($user['parent_id'])) {
                $map = [
                    'id'     => $user['parent_id'],
                    'status' => 1,
                ];
                $user = self::where($map)->find();
                if (!empty($user)) {
                    $data[] = [
                        'id'     => $user['id'],
                        'pid'    => $user['parent_id'],
                        'lv'     => $lv,
                        'status' => $user['status'],
                    ];
                    $lv++;
                    $num--;
                }
            }
        } while (!empty($user['parent_id']) && $num >= 1);
        return $data;
    }

    // 获取用户的所有下级ID
    public function get_child($members, $mid, $level = 0)
    {
        $arr = array();
        foreach ($data as $key => $v) {
            if ($v['pid'] == $mid) {
                // pid为0的是顶级分类
                $v['level'] = $level + 1;
                $arr[]      = $v;
                $arr        = array_merge($arr, get_downline($data, $v['id'], $level + 1));
            }
        }
        return $arr;
    }

    /**
     * [get_childs 获取下属成员]
     * @param  integer $uid   [用户ID]
     * @param  integer $child [指定下线ID]
     * @param  integer $lv    [获取层级]
     * @return [type]         [description]
     */
    public function get_childs($uid = 0, $lv = 0)
    {
        $users = [];
        $map   = [
            ['id', '=', $uid],
        ];
        $user = self::where($map)->field('id,child_level,parent_first,childs')->find();
        if (!empty($user['childs'])) {
            $us = self::where('parent_id', $user['id'])->field('id,child_level,parent_first,childs')->select();
            if (!empty($us)) {
                foreach ($us as $key => $u) {
                    $users[] = $u;
                    if (!empty($u['childs']) && $u['child_level'] <= $user['child_level'] + $lv) {
                        $uu = get_childs($u['id'], $lv--);
                    }
                }
            }
            do {
                if (!empty($user['parent_id'])) {
                    $map = [
                        'id'     => $user['parent_id'],
                        'status' => 1,
                    ];
                    $user = self::where($map)->find();
                    if (!empty($user)) {
                        $data[] = [
                            'id'     => $user['id'],
                            'pid'    => $user['parent_id'],
                            'lv'     => $lv,
                            'status' => $user['status'],
                        ];
                        $lv++;
                        $num--;
                    }
                }
            } while (!empty($user['parent_id']) && $num >= 1);
        }
        return $users;
    }

    /**
     * [set_vips 设置自己及所有上线VIP等级]
     * @param  integer $uid [用户ID]
     * @param  integer $set [是否直接更新]
     * @return [type]       [description]
     */
    public function set_vips($uid, $set = 0)
    {
        $UsersModel = new Users;
        $up         = [];
        $map        = [
            ['id', '=', $uid],
        ];
        $user = self::where($map)->field('id,level,base_level,parent_id')->find();
        $u    = $UsersModel->set_vip($uid);
        if ($u['level'] > $user['level']) {
            $up[] = $u;
            unset($u);
        }
        if (!empty($user['parent_id']) && $user['parent_id'] > 0) {
            $parent_id = $user['parent_id'];
            do {
                $map = [
                    ['id', '=', $parent_id],
                ];
                $user = self::where($map)->field('id,level,base_level,parent_id')->find();
                if (!empty($user)) {
                    if (!empty($user['parent_id'])) {
                        $parent_id = $user['parent_id'];
                    }
                    $u = $UsersModel->set_vip($user['id']);
                    if ($u['level'] > $user['level']) {
                        $up[] = $u;
                        unset($u);
                    }
                }
            } while (!empty($user) && $user['parent_id'] > 0);
        }
        if ($set > 0) {
            return $UsersModel->saveAll($up);
        } else {
            return $up;
        }
    }

    /**
     * [set_vip 设置指定用户的VIP等级]
     * @param  integer $uid [用户ID]
     * @return [type]       [description]
     * 返回设置好VIP等级的用户数组
     */
    public function set_vip($uid)
    {
        $map = [
            ['id', '=', $uid],
            ['status', '=', 1],
        ];
        $user = self::where($map)->field('id,level,base_level')->find();
        if (!empty($user)) {
            $level  = $user['level'];
            $levels = get_levels();
            foreach ($levels as $key => $value) {
                $extended = $value['extended'];
                if (!empty($extended)) {
                    $m    = '`parent_id` = ' . $uid;
                    $cmap = [
                        ['parent_id', '=', $uid],
                    ];
                    if (!empty($extended['member_recharge'])) {
                        $m .= ' AND `recharge_total` >= ' . $extended['member_recharge'];
                        $cmap[] = ['recharge_total', '>=', $extended['member_recharge']];
                    }
                    if (!empty($extended['member_money'])) {
                        $m .= ' AND (`balance` + `freeze_balance`) >= ' . $extended['member_money'];
                        $cmap[] = ['balance+freeze_balance', '>=', $extended['member_money']];
                        // $cmap[] = [Db::raw('balance+freeze_balance'), '>=', $extended['member_money']];
                    }
                    $childs = self::whereRaw($m)->count();
                    // $childs = self::where($cmap)->fetchSql(true)->count();
                    if ($childs >= $extended['member_num'] && $value['level'] != $level) {
                        $user['level'] = $value['level'];
                    }
                }
            }
            if ($user['level'] < $user['base_level']) {
                $user['level'] = $user['base_level'];
            }
            unset($levels);
            if ($user['level'] != $level) {
                return $user;
            }
        }
        return [];
    }

    /**
     * [get_parents 获取指定用户的所有上线]
     * @param  integer $uid [用户ID]
     * @return [type]       [description]
     */
    public function get_parents($uid = 0)
    {
        $users     = [];
        $parent_id = $uid;
        do {
            $info = db($this->table)->where('id', $parent_id)->find();
            if (!empty($info)) {
                $users[] = $info;
                if (!empty($info['parent_id'])) {
                    $parent_id = $info['parent_id'];
                }
            }
        } while (!empty($info) && $info['parent_id'] > 0);
        return $users;
    }

    /**
     * [unfreeze 解冻账号]
     * @param  array  $user [用户信息]
     * @return [type]       [description]
     */
    public function unfreeze($user = [])
    {
        $map = [
            ['uid', '=', $user['id']],
            ['type', '=', 3],
        ];
        $error = UserErrorModel::where($map)->order('addtime DESC')->find();
        if (!empty($error) || $error['addtime'] <= time() - config('deal_feedze')) {
            $data = [
                'deal_status' => 1,
            ];
            self::where('id', $user['id'])->update($data);
            $error = [
                'uid'  => $user['id'],
                'oid'  => '-',
                'type' => 1,
            ];
            UserErrorModel::create($error);
            return $user;
        }
        return [];
    }
}
