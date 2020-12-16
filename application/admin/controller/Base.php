<?php

namespace app\admin\controller;

use library\Controller;

/**
 * 会员管理
 * Class Users
 * @package app\admin\controller
 */
class Base extends Controller
{

    public function initialize()
    {
        $host = \think\facade\Request::host();
        if (config('site_admin_domain') && config('site_admin_domain') != $host) {
            $this->redirect('@index');
        }
    }
}
