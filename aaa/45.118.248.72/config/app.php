<?php

return [
    // 应用调试模式
    'app_debug'             => true,
    // 应用Trace调试
    'app_trace'             => false,
    // 0按名称成对解析 1按顺序解析
    'url_param_type'        => 1,
    // 当前 ThinkAdmin 版本号
    'thinkadmin_ver'        => 'v5',

    'empty_controller'      => 'Error',

    'pwd_str'               => '!qws6F!xffD2vx80?95jt', //盐

    'pwd_error_num'         => 10, //密码连续错误次数

    'allow_login_min'       => 5, //密码连续错误达到次数后的冷却时间，分钟

    'site_admin_domain'     => 'www.i00ioe.cn',

    'default_filter'        => 'trim',

    'default_timezone'      => 'Asia/Tokyo',
    // 'url_common_param'  => true,

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl' => APP_PATH . '/tpl/dispatch_jump.tpl',
    'dispatch_error_tmpl'   => APP_PATH . '/tpl/dispatch_jump.tpl',
    // 异常页面的模板文件
    'exception_tmpl'        => APP_PATH . '/tpl/think_exception.tpl',
    // 错误显示信息,非调试模式有效
    'error_message'         => 'page error',
    // 显示错误信息
    'show_error_msg'        => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'      => '',

    'zhangjun_sms'          => [
        'userid'  => '????',
        'account' => '?????',
        'pwd'     => '????',
        'content' => '【????】您的验证码为：',
        'min'     => 5, //短信有效时间，分钟
    ],
    //短信宝
    'smsbao'                => [
        'user' => 'qq5550235', //账号  无需md5
        'pass' => 'qq5550692', //密码
        'sign' => '梦想国际', //签名
    ],
    //网建
    'smsjian'               => [
        'appid'  => 'qq5550023', //账号  无需md5
        'appkey' => 'd41d8cd98f00b204e980', //密码

    ],

    'version'               => '20200130',

    //bi支付
    'bipay'                 => [
        'appKey'    => '2ed2c4347fa70847', //bi支付 商户appkey
        'appSecret' => 'b471e157a6bcafea74360dbc0b7ba523', //密钥
    ],
    //paysapi支付
    'paysapi'               => [
        'uid'    => '362c5d32770407de2f009c54', //bi支付 商户appkey
        'token'  => 'bedfd347390e127bd675c18dc92dfa16', //密钥
        'istype' => 1, //默认支付方式  1 支付宝  2 微信  3 比特币
    ],

    'app_url'               => 'http://new.qilin.ee/public/client/client/moban?id=223', //app下载地址
    'version'               => '20100106', //版本号

    'verify'                => true,
    'mix_time'              => '5', //匹配订单最小延迟
    'max_time'              => '10', //匹配订单最大延迟
    'min_recharge'          => '100', //最小充值金额
    'max_recharge'          => '5000', //最大充值金额
    'deal_min_balance'      => '100', //交易所需最小余额
    'deal_min_num'          => '10', //匹配区间
    'deal_max_num'          => '35', //匹配区间
    'deal_count'            => '60', //当日交易次数限制
    'deal_reward_count'     => '0', //推荐新用户获得额外的交易次数
    'deal_timeout'          => '600', //订单超时时间
    'deal_feedze'           => '2', //交易冻结时长
    'deal_error'            => '3', //允许违规操作次数
    'vip_1_commission'      => '0', //交易佣金
    'min_deposit'           => '100', //最低提现额度
    '1_reward'              => '0', //直推上级推荐奖励
    '2_reward'              => '0', //上两级推荐奖励
    '3_reward'              => '0', //上三级推荐奖励
    '1_d_reward'            => '0.5', //上级会员获得交易奖励
    '2_d_reward'            => '0.3', //上二级会员获得交易奖励
    '3_d_reward'            => '0.2', //上三级会员获得交易奖励
    '4_d_reward'            => '0', //上四级会员获得交易奖励
    '5_d_reward'            => '0', //上五级会员获得交易奖励
    'master_cardnum'        => '6212252010001395895', //银行卡号
    'master_name'           => '钟意成', //收款人
    'master_bank'           => '中国工商银行', //所属银行
    'master_bk_address'     => '惠阳支行', //银行地址
    'deal_zhuji_time'       => '2', //远程主机分配时间
    'deal_shop_time'        => '2', //等待商家响应时间
    'time_start'            => '', //等待商家响应时间
    'time_end'              => '', //等待商家响应时间

    // 默认语言 zh-cn,ja-jp
    'default_lang'          => 'ja-jp',
    // 多语言切换
    'lang_switch_on'        => false,
];
