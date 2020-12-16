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

return [
    // 去除HTML空格换行
    'strip_space'        => true,
    // 开启模板编译缓存
    'tpl_cache'          => !config('app_debug'),
    // 定义模板替换字符串
    'tpl_replace_string' => [
        '__APP__'        => rtrim(url('@'), '\\/'),
        '__ROOT__'       => rtrim(dirname(request()->basefile()), '\\/'),
        '__PUBLIC__'     => rtrim(dirname(request()->basefile()), '\\/'),
        // local -> /res/npm cdn -> https://cdn.jsdelivr.net/npm
        '__NPM__'        => 'https://cdn.jsdelivr.net/npm',
        // local -> /res/gh cdn -> https://cdn.jsdelivr.net/gh
        '__GH__'         => 'https://cdn.jsdelivr.net/gh',
        '__RESPRIVATE__' => '/res/private',
        '__VER__'        => '?v=' . time(),
    ],
    // 模板引擎类型 支持 php think 支持扩展
    'type'               => 'Think',
    // 模板路径
    'view_path'          => '',
    // 模板后缀
    'view_suffix'        => 'html',
    // 模板文件名分隔符
    'view_depr'          => '/',
    // 模板引擎普通标签开始标记
    'tpl_begin'          => '{',
    // 模板引擎普通标签结束标记
    'tpl_end'            => '}',
    // 标签库标签开始标记
    'taglib_begin'       => '{',
    // 标签库标签结束标记
    'taglib_end'         => '}',
];
