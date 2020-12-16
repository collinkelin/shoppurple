<?php /*a:2:{s:68:"/www/wwwroot/www.oabuhps.cn/application/index/view/ctrl/junior2.html";i:1595484485;s:60:"/www/wwwroot/www.oabuhps.cn/application/index/view/main.html";i:1593514237;}*/ ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="Robots" content="noindex,nofollow"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><title><?php echo sysconf('site_name'); ?></title><link rel="stylesheet" type="text/css" href="/public/css/style.min.css?v=1595490249"><link rel="stylesheet" type="text/css" href="/public/css/ui.min.css?v=1595490249"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><style>
    nav {
        position: fixed;
        top: 2rem;
        left: 0;
        right: 0;
        margin: auto;
        max-width: 750px;
        width: 100%;
        background: white;
        display: flex;
        height: 2rem;
        line-height: 2rem;
        justify-content: space-between;
        flex-direction: row;
        border-bottom: 1px solid #eeeeee;
    }

    .nav_active {
            color: #9374EB;
        background-color: #C9B9F7;
        border-bottom: 1px solid #9374EB;
    }

    nav>p {
        width: 33%;
        text-align: center;
    }

    .cont {
        margin-bottom: 75px;
    }

    .cont>div {
        display: none;
    }

    .list {
        height: 77vh;
        overflow-y: scroll;
    }

    .list>li {
        font-size: .5rem;
        border-bottom: .1rem solid rgb(248, 242, 242);
        padding: .5rem 1rem;
    }

    .order_info {
        margin-top: .5rem;
        display: flex;
    }

    .info_img {
        width: 3rem;
        height: 3rem;
        height: auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .info_img img {
        max-height: 60px;
        max-width: 60px;
    }

    .info_data {
        max-width: 55%;
        margin: 0 0 0 1rem;
    }

    .info_store,
    .money {
        color: #00bcd4;
    }

    .info_money {
        margin-left: auto;
        text-align: right;
    }

    .no-data {
        border: none !important;
        text-align: center;
    }

    .info_name,
    .order_num {
        color: #000;
        font-size: 13px
    }

    .info_name,
    .info_store,
    .money,
    .info_num {
        font-size: 12px
    }

    .info_data {
        display: inline-block
    }

    .info_data>p,
    .info_money>p {
        margin-bottom: 3px;
    }
    </style></head><body><header><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('My team'); ?></span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><section><div class="container mescroll" style="margin-top: 75px;" id="mescroll"><nav><p class="nav_active"><?php echo lang('Generation'); ?></p><p><?php echo lang('Second Generation'); ?></p><p><?php echo lang('Three generations'); ?></p></nav><div class="cont"><div style="display: block;"><ul class="list wait_list"></ul></div><div><ul class="list freeze_list"></ul></div><div><ul class="list make_list"></ul></div></div><div id="footer" style="position: fixed;bottom: 0;width: 100%;max-width: 750px;margin: auto;height:70px;border-top: 1px solid #ccc;background-color: #fff;"><div style="display: block;"><ul class="list wait_list2"><li id="" style="padding: 0 15px;border-bottom: 0"><div class="order_info"><div class="info_img"><span class="sticky-icon" style="font-size: 17px"><?php echo lang('statistics'); ?></span></div><div class="info_data"><p class="info_name"><?php echo lang('Recharge'); ?>: <span id="chongzhi2">0</span></p><p class="info_store"><?php echo lang('Withdraw'); ?>: <span id="tixian2">0</span></p></div><div class="info_money"><p class="money" style=""><?php echo lang('Recommended number of people'); ?>: <span id="num">0</span></p><p class="money" style="color:#00d44b"><?php echo lang('Recommended number of people'); ?>: <span id="num2">0</span></p></div></div></li></ul></div></div></div></section><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="https://cdn.jsdelivr.net/npm/dot@1.1.3/doT.min.js"type="text/javascript" ></script><script src="https://cdn.jsdelivr.net/npm/cleave.js@1.5.10/dist/cleave.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595490249"></script><script src="/public/js/common.min.js?v=1595490249"></script><script src="/public/js/city.js"></script><script src="/public/js/picker.min.js"></script><script>
    var page = 1,
        fpage = 1,
        mpage = 1,
        listHeight = $('.list').height(),
        fcont = 0,
        mcont = 0;
    $(function() {
        $('#hui-footer a').eq(1).addClass("floor-active")
        wait(page); // 待处理订单
    });

    // 待处理订单滚动加载
    $(".wait_list").scroll(function() {
        var nScrollHight = $(this)[0].scrollHeight;
        var nScrollTop = $(this)[0].scrollTop;
        if (nScrollTop + listHeight >= nScrollHight) {
            page++;
            wait(page);
        }
    });

    // 冻结订单滚动加载
    $(".freeze_list").scroll(function() {
        var nScrollHight = $(this)[0].scrollHeight;
        var nScrollTop = $(this)[0].scrollTop;
        if (nScrollTop + listHeight >= nScrollHight) {
            page++;
            freeze(page);
        }
    });

    // 完成订单滚动加载
    $(".make_list").scroll(function() {
        var nScrollHight = $(this)[0].scrollHeight;
        var nScrollTop = $(this)[0].scrollTop;
        if (nScrollTop + listHeight >= nScrollHight) {
            page++;
            make(page);
        }
    });

    // tab切换
    $('nav>p').click(function() {
        var _ind = $(this).index();
        $(this).addClass("nav_active").siblings().removeClass("nav_active");
        $(".cont>div").eq(_ind).show().siblings().hide();

        console.log(_ind);

        if (_ind == 1) {
            if (fcont == 0) {
                //fcont = 1;
                freeze(fpage); //冻结订单
            }
        } else if (_ind == 2) {
            if (mcont == 0) {
                //mcont = 1;
                make(mpage); //完成订单
            }
        } else if (_ind == 0) {
            if (mcont == 0) {
                wait(mpage); //完成订单
            }
        }
    });

    // 待处理订单请求
    function wait(page) {
        $.ajax({
            url: urlPost("ctrl/get_user"),
            type: "POST",
            dataType: "JSON",
            data: { page: page, type: 1 },
            success: function(res) {
                console.log(res);
                if (res.code == 0) {
                    var list = res.data;
                    $('#chongzhi2').text(milliFormat(res.other.chongzhi));
                    $('#tixian2').text(milliFormat(res.other.tixian));
                    $('#num').text(milliFormat(res.other.xiaji));
                    $('#num2').text(milliFormat(res.other.xiaji));
                    if (page != 1 && list.length == 0) {
                        QS_toast.show(`<?php echo lang('No more data'); ?>...`, true)
                    }
                    if (page == 1 && list.length == 0) {
                        $(".wait_list").append(`<li class="no-data"><?php echo lang('No data'); ?>...</li>`)
                    }
                    if (page == 1) {
                        $(".wait_list").html('');
                    }
                    list.map(function(val) {
                        $(".wait_list").append(`
                                <li id="${val.id}"><div class="order_info"><div class="info_img"><img src="${val.headpic}" alt=""></div><div class="info_data"><p class="info_name"><?php echo lang('nickname'); ?>:${val.nickname}</p><p class="info_store"><?php echo lang('Recharge'); ?>:${milliFormat(val.chongzhi)}</p><p class="info_store"><?php echo lang('Withdraw'); ?>:${milliFormat(val.tixian)}</p></div><div class="info_money"><p class="money" style=""><?php echo lang('Phone'); ?>:${val.tel}</p><p class="money" style="color:#00d44b"><?php echo lang('Recommended number of people'); ?>: ${milliFormat(val.childs)}</p><p><span class="info_num"><?php echo lang('Registration time'); ?>:${val.addtime}</span></p></div></div></li>
                            `)
                    })
                }
            },
            error: function(err) { console.log(err) }
        })
    }

    // 冻结订单请求
    function freeze(page) {
        $.ajax({
            url: urlPost("ctrl/get_user"),
            type: "POST",
            dataType: "JSON",
            data: { page: page, type: 2 },
            success: function(res) {
                console.log(res);
                $('#chongzhi2').text(milliFormat(res.other.chongzhi));
                $('#tixian2').text(milliFormat(res.other.tixian));
                $('#num').text(milliFormat(res.other.xiaji));
                $('#num2').text(milliFormat(res.other.xiaji));
                if (res.code == 0) {
                    var list = res.data;
                    if (page != 1 && list.length == 0) {
                        QS_toast.show(`<?php echo lang('No more data'); ?>...`, true)
                    }
                    if (page == 1 && list.length == 0) {
                        $(".freeze_list").append(`<li class="no-data"><?php echo lang('No data'); ?>...</li>`)
                    }
                    if (page == 1) {
                        $(".freeze_list").html('');
                    }
                    list.map(function(val) {
                        $(".freeze_list").append(`
                                <li id="${val.id}"><div class="order_info"><div class="info_img"><img src="${val.headpic}" alt=""></div><div class="info_data"><p class="info_name"><?php echo lang('nickname'); ?>:${val.nickname}</p><p class="info_store"><?php echo lang('Recharge'); ?>:${milliFormat(val.chongzhi)}</p><p class="info_store"><?php echo lang('Withdraw'); ?>:${milliFormat(val.tixian)}</p></div><div class="info_money"><p class="money" style=""><?php echo lang('Phone'); ?>:${val.tel}</p><p class="money" style="color:#00d44b"><?php echo lang('Recommended number of people'); ?>: ${milliFormat(val.childs)}</p><p><span class="info_num"><?php echo lang('Registration time'); ?>:${val.addtime}</span></p></div></div></li>
                            `)
                    })
                }
            },
            error: function(err) { console.log(err) }
        })
    }

    // 完成订单请求
    function make(page) {
        $.ajax({
            url: urlPost("ctrl/get_user"),
            type: "POST",
            dataType: "JSON",
            data: { page: page, type: 3 },
            success: function(res) {
                console.log(res);
                if (res.code == 0) {
                    var list = res.data;

                    $('#chongzhi2').text(milliFormat(res.other.chongzhi_all) + '(' + milliFormat(res.other.chongzhi) + '+' + milliFormat(res.other.chongzhi4) + '+' + milliFormat(res.other.chongzhi5) + ')');
                    $('#tixian2').text(milliFormat(res.other.tixian_all) + '(' + milliFormat(res.other.tixian) + '+' + milliFormat(res.other.tixian4) + '+' + milliFormat(res.other.tixian5) + ')');
                    $('#num').text(milliFormat(res.other.xiaji));
                    $('#num2').text(milliFormat(res.other.xiaji));

                    if (page != 1 && list.length == 0) {
                        QS_toast.show(`<?php echo lang('No more data'); ?>...`, true)
                    }
                    if (page == 1 && list.length == 0) {
                        $(".make_list").append(`<li class="no-data"><?php echo lang('No data'); ?>...</li>`)
                    }
                    if (page == 1) {
                        $(".make_list").html('');
                    }
                    list.map(function(val) {
                        $(".make_list").append(`
                                <li id="${val.id}"><div class="order_info"><div class="info_img"><img src="${val.headpic}" alt=""></div><div class="info_data"><p class="info_name"><?php echo lang('nickname'); ?>:${val.nickname}</p><p class="info_store"><?php echo lang('Recharge'); ?>:${milliFormat(val.chongzhi)}</p><p class="info_store"><?php echo lang('Withdraw'); ?>:${milliFormat(val.tixian)}</p></div><div class="info_money"><p class="money" style=""><?php echo lang('Phone'); ?>:${val.tel}</p><p class="money" style="color:#00d44b"><?php echo lang('Recommended number of people'); ?>: ${milliFormat(val.childs)}</p><p><span class="info_num"><?php echo lang('Registration time'); ?>:${val.addtime}</span></p></div></div></li>
                            `)
                    })
                }
            },
            error: function(err) { console.log(err) }
        })
    }
    </script></body></html>