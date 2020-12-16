<?php
namespace app\common\event;

use app\common\model\Users as UsersModel;
use think\Db;

class Users
{
    protected $money;

    // 新增后
    public function afterInsert($user)
    {
        // echo 0, $user->balance, '<br/>';
    }

    // 更新前
    public function beforeUpdate($user)
    {
        $u = UsersModel::where('id', $user['id'])->field('id,balance')->find();
        if (empty($this->money)) {
            $this->money = $u['balance'];
        }
    }

    // 更新后
    public function afterUpdate($user)
    {
        $u = UsersModel::where('id', $user['id'])->field('id,balance,freeze_balance,parent_id')->find();
        if ($u['freeze_balance'] < 0) {
            Db::query('UPDATE `xy_users` SET `balance` = balance+' . $u['freeze_balance'] . ', `freeze_balance` = freeze_balance-' . $u['freeze_balance'] . ' WHERE `id` = ' . $u['id']);
        }
        if (!empty($u['parent_id']) && $this->money != $u['balance']) {
            $up = (new UsersModel)->set_vip($u['parent_id']);
            if (!empty($up)) {
                // 更新VIP等级
                Db::query('UPDATE `xy_users` SET `level` = ' . $up['level'] . ' WHERE `id` = ' . $up['id']);
            }
        }
    }

    // // 写入前
    // public function beforeWrite($user)
    // {
    //     $u = UsersModel::where('id', $user['id'])->field('id,balance')->find();
    //     if (empty($this->money)) {
    //         $this->money = $u['balance'];
    //     }
    // }

    // // 写入后
    // public function afterWrite($user)
    // {
    //     $u = UsersModel::where('id', $user['id'])->field('id,balance')->find();
    //     if ($this->money != $u['balance']) {
    //         echo 4, json_encode($u);
    //     }
    // }
}
