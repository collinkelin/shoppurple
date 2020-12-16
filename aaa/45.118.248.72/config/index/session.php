<?php

// +----------------------------------------------------------------------
// | ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2019 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://demo.thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 代码仓库：https://gitee.com/zoujingli/ThinkAdmin
// | github 代码仓库：https://github.com/zoujingli/ThinkAdmin
// +----------------------------------------------------------------------

/* 配置会话参数 */
return [
    // session类型
    'type'       => 'Redis',
    // session过期时间
    'expire'     => 86400,
    // session前缀
    'prefix'     => \think\facade\Request::host() . '_index',
    // 是否自动开启
    'auto_start' => true,
    // // 是否使用use_trans_sid
    // 'use_trans_sid'  => '',
    // // 请求session_id变量名
    // 'var_session_id' => '',
    // // session_id
    // 'id'             => '',
    // // session_name
    // 'name'           => '',
    // // session保存路径
    // 'path'           => '',
    // // session cookie_domain
    // 'domain'         => '',
    // // 是否使用cookie
    // 'use_cookies'    => '',
    // // session_cache_limiter
    // 'cache_limiter'  => '',
    // // session_cache_expire
    // 'cache_expire'   => '',
    // // 安全选项
    // 'secure'         => '',
    // // 使用httponly
    // 'httponly'       => '',

    /* redis */
    // redis主机
    'host'       => '127.0.0.1',
    // redis端口
    'port'       => 6379,
    // 密码
    'password'   => '123456',
];
