<?php

// +----------------------------------------------------------------------
// | 路由设置
// +----------------------------------------------------------------------

return [
    // pathinfo分隔符
    'pathinfo_depr'         => '/',
    // URL普通方式参数 用于自动生成
    'url_common_param'      => true,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'        => 1,
    // 是否开启路由延迟解析
    'url_lazy_route'        => false,
    // 是否强制使用路由
    'url_route_must'        => false,
    // 合并路由规则
    'route_rule_merge'      => false,
    // 路由是否完全匹配
    'route_complete_match'  => false,
    // 使用注解路由
    'route_annotation'      => false,
    // 默认的路由变量规则
    'default_route_pattern' => '\w+',
    // 是否开启路由缓存
    'route_check_cache'     => false,
    // 路由缓存的Key自定义设置（闭包），默认为当前URL和请求类型的md5
    'route_check_cache_key' => '',
    // 路由缓存的设置
    'route_cache_option'    => [],
];
