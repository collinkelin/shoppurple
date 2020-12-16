<?php

return [
    // 驱动方式
    'type'      => 'Memcached',
    // 缓存保存目录
    //'path'   => CACHE_PATH,
    // 缓存前缀
    'prefix'    => \think\facade\Request::host(),
    // 缓存有效期 0表示永久缓存
    'expire'    => 0,

    'host'      => '127.0.0.1',
    'port'      => 11211,
    'expire'    => 0,
    'timeout'   => 0, // 超时时间（单位：毫秒）
    'prefix'    => '',
    'username'  => '', //账号
    'password'  => '', //密码
    'option'    => [],
    'serialize' => true,
];
