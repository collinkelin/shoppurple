<?php /*a:3:{s:70:"/www/wwwroot/45.118.248.72/application/index/view/rot_order/index.html";i:1595181712;s:59:"/www/wwwroot/45.118.248.72/application/index/view/main.html";i:1593514237;s:67:"/www/wwwroot/45.118.248.72/application/index/view/public/floor.html";i:1595241314;}*/ ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="Robots" content="noindex,nofollow"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><title><?php echo sysconf('site_name'); ?></title><link rel="stylesheet" type="text/css" href="/public/css/style.min.css?v=1595241372"><link rel="stylesheet" type="text/css" href="/public/css/ui.min.css?v=1595241372"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><link rel="stylesheet" type="text/css" href="/res/common/css/hui.min.css?v=1595241372"><style type="text/css">
html {
    font-size: 20px !important;
}

#yongjin {
    position: relative;
    top: -5px;
    right: 50%;
    text-align: right;
    margin-right: -30px;
}

#yongjin span {
    display: inline-block;
    background: #FF5722;
    padding: 2px 5px;
    font-size: 12px;
    border-radius: 14px;
    color: #fff;
}

.loading {
    width: 2rem;
    height: 2rem;
    margin: auto;
    background-image: url(/public/img/load.png);
    background-size: 100%;
    background-repeat: no-repeat;
    animation: load 2s linear infinite;
}

.order-number {
    color: #fff;
}

@keyframes load {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
/* .hui-common-title-txt:after{
    content: attr(title);
} */
</style></head><body><header class="hui-header"><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span class="cate-name">--</span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><section style="padding-bottom: 30px;"><div class="hui-wrap" style="background-image: linear-gradient(to top,#FF6431,#F0454D);padding-top: 12px;  border-radius:10px 10px 10px 10px; width:95%;height:350px; margin-left:2.5%;margin-top:60px"><div id="yongjin"><span><?php echo lang('Commission'); ?><i class="order-number cate-bili"></i>%</span></div><div class="user-wallet"><ul><li><a href="<?php echo url('order/index'); ?>"><span><?php echo lang('Number of orders rushed today'); ?></span><h3><i class="order-number day_d_count">0</i></h3></a></li><li><a href="<?php echo url('order/index'); ?>"><span><?php echo lang('Freeze singular today'); ?></span><h3><i class="order-number day_l_count">0</i></h3></a></li></ul><ul><li><a href="<?php echo url('ctrl/wallet'); ?>"><span><?php echo lang('Available rush balance'); ?></span><h3><?php echo lang('symbol'); ?><i class="order-number price">0</i></h3></a></li><li><a href="<?php echo url('ctrl/wallet'); ?>"><span><?php echo lang('Current balance'); ?></span><h3><?php echo lang('symbol'); ?><i class="order-number price">0</i></h3></a></li></ul><ul><li><a href="<?php echo url('ctrl/wallet'); ?>"><span><?php echo lang('Total amount frozen in account'); ?></span><h3><?php echo lang('symbol'); ?><i class="order-number lock_deal">0</i></h3></a></li><li><a href="<?php echo url('ctrl/junior2'); ?>"><span><?php echo lang('Total team commission'); ?></span><h3><?php echo lang('symbol'); ?><i class="order-number team_num">0</i></h3></a></li></ul></div><div class="hui-common-title" style="margin-top:15px;"><!-- <div class="hui-common-title-line"></div>--><div class="hui-common-title-txt" style="width:50%" title=""><?php echo lang('Commission today'); ?><?php echo lang('symbol'); ?><i class="order-number day_deal">0</i></div><!--     <div class="hui-common-title-line"></div>--></div></div><div style="background:#dedede; padding:0px 15px; margin:10px;border-radius:10px" class="hui-flex"><div style="height:40px; width:35px;"><img src="/public/img/pmdgg.png"></div><div class="hui-scroll-news" style="height:35px;line-height:35px" id="scrollnew1"><?php if(is_array($conveys) || $conveys instanceof \think\Collection || $conveys instanceof \think\Paginator): $i = 0; $__LIST__ = $conveys;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="hui-scroll-news-items" style="line-height:35px"><?php echo htmlentities($vo['con']); ?></div><?php endforeach; endif; else: echo "" ;endif; ?></div></div><div style="padding:10px 28px;margin-top: 0rem;max-width: calc(750px - 56px);"><button type="button" class="hui-button hui-button-large self_btn" id="btn1" style="background-image: linear-gradient(to top,#E60816,#F8838B); color: #fff;" data-open="1"><?php echo lang('Enable automatic grab order'); ?></button></div><div class="hui-fooer-line"></div></section><input type="hidden" name="qd_time_intvel" value="10"><input type="hidden" name="type" value="1"><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="https://cdn.jsdelivr.net/npm/dot@1.1.3/doT.min.js"type="text/javascript" ></script><script src="https://cdn.jsdelivr.net/npm/cleave.js@1.5.10/dist/cleave.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595241372"></script><script src="/public/js/common.min.js?v=1595241372"></script><script src="/public/js/city.js"></script><script src="/public/js/picker.min.js"></script><script src="https://cdn.jsdelivr.net/gh/yaseng/jquery.barrager.js@master/dist/js/jquery.barrager.min.js"></script><script src="https://cdn.jsdelivr.net/gh/shenhai/HUI@master/js/hui.js" type="text/javascript" charset="utf-8"></script><script src="https://cdn.jsdelivr.net/npm/swiper@3.1.7/dist/js/swiper.min.js"></script><style>
@font-face {
    font-family: "iconfont";
    src: url('/res/common/fonts/font_1463191_xdm42ti8gyp.eot?t=1571290693237');
    /* IE9 */
    src: url('/res/common/fonts/font_1463191_xdm42ti8gyp.eot?t=1571290693237#iefix') format('embedded-opentype'),
        /* IE6-IE8 */
        url('data:application/x-font-woff2;charset=utf-8;base64,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') format('woff2'), url('/res/common/fonts/font_1463191_xdm42ti8gyp.woff?t=1571290693237') format('woff'), url('/res/common/fonts/font_1463191_xdm42ti8gyp.ttf?t=1571290693237') format('truetype'),
        /* chrome, firefox, opera, Safari, Android, iOS 4.2+ */
        url('/res/common/fonts/font_1463191_xdm42ti8gyp.svg?t=1571290693237#iconfont') format('svg');
    /* iOS 4.1- */
}

.iconfont {
    font-family: "iconfont" !important;
    font-size: 18px;
    font-style: normal;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.icon-dianji:before {
    content: "\e600";
}

.icon-shouye:before {
    content: "\e615";
}

.icon-tubiao-:before {
    content: "\e64f";
}

.icon-weibiaoti-:before {
    content: "\e614";
}

.icon-dingdan-yichenggong:before {
    content: "\e68d";
}

.hui-footer-text {
    padding-top: 0
}

.floor-active div {
    color: #8c1bab
}

footer {
    margin: auto;
    max-width: 750px;
}

footer #hui-footer {
    right: 0;
    max-width: 750px;
    height: 50px;
    margin: auto;
    padding: 0px 0px 2px 0;
}

footer #footer-logo {
    width: 60px;
    height: 60px;
}

footer #footer-logo2 {
    width: 50%;
    margin: auto;
    height: 54px;
    text-align: center;
    text-align: -webkit-center;
}

footer #footer-logo2 img {
    display: inline-block;
    margin: auto;
    margin-top: 7px;
    height: 45px;
    width: 100%;
}
#hui-footer{
    box-shadow:none;
}
#hui-footer a{
        width: 21%;
    border-top: 1px solid #ccc;
    background: #FCFCFC;
}
#hui-footer{
    background: unset;
}
#nav-news{
    border-top-right-radius: 25px;
}
#nav-forum{
    border-top-left-radius: 32px;
}
footer #footer-logo{
    width: 70px;
    height: 28px;
}
#footer-logo{
        border-radius: unset;
    background-image: unset;
        position: fixed;
    z-index: 11;
    left: 50%;
    bottom: 1px;
    text-align: center;
    transform: translateX(-50%);
    line-height: 60px;
    color: #FFF;
    font-size: 20px;
}
#footer-logo img{
    margin-top: 0;
    width: 98%;
    border-bottom-left-radius: 70px;
    border-bottom-right-radius: 70px;
    position: absolute;
    top: -43px;
        height: auto;
    display: block;
    border: 0;
}
.news_img_icon{
    display: block;
    height: 1rem;
    width: 1rem;
    margin: 0 auto;
    margin-top: .15rem;
    margin-bottom: .2rem;
}
</style><footer><div id="hui-footer"><a href="<?php echo url('index/home'); ?>" id="nav-home"><?php if(request()->CONTROLLER() == 'Index'): ?><img class="news_img_icon" src="/public/img/icon-首页选中2.png"><?php else: ?><img class="news_img_icon" src="/public/img/icon-首页选中.png"><?php endif; ?><div class="hui-footer-text"><?php echo lang('Home btn'); ?></div></a><a href="<?php echo url('order/index'); ?>" id="nav-news" ><?php if(request()->CONTROLLER() == 'Order'): ?><img class="news_img_icon" src="/public/img/icon-记录2.png"><?php else: ?><img class="news_img_icon" src="/public/img/icon-记录.png"><?php endif; ?><div class="hui-footer-text"><?php echo lang('Record'); ?></div></a><div style="width:15%;height:38px;text-align:center;background:#FCFCFC;border-radius: 50% 50% 0 0;margin-top:10px">
            &nbsp;
        </div><a href="<?php echo url('support/index'); ?>" id="nav-forum"><?php if(request()->CONTROLLER() == 'Support'): ?><img class="news_img_icon" src="/public/img/icon-客服2.png"><?php else: ?><img class="news_img_icon" src="/public/img/icon-客服.png"><?php endif; ?><div class="hui-footer-text"><?php echo lang('Customer Service'); ?></div></a><a href="<?php echo url('my/index'); ?>" id="nav-my"><?php if(request()->CONTROLLER() == 'My'): ?><img class="news_img_icon" src="/public/img/icon-我的选中2.png"><?php else: ?><img class="news_img_icon" src="/public/img/icon-我的选中.png"><?php endif; ?><div class="hui-footer-text"><?php echo lang('My'); ?></div></a></div><a href="<?php echo url('rot_order/index'); ?>" id="footer-logo"><img src="/public/img/qd2.png" style=""><!-- <div id="footer-logo2"> --><!-- <img src="/statics/img/qd-1.png" style=""> --><!-- </div> --></a></footer><script src="/public/js/common.min.js?v=1595241372"></script><script>
$(".floor li").click(function() {
    $(this).addClass('floor-active').siblings().removeClass('floor-active')
})
</script><script type="text/javascript">
var submit = true,
    status = false,
    timer = null,
    ajaxT = null,
    lay = 0;

$(function() {
    hui.scrollNews(scrollnew1);
    // hui.scrollNews(scrollnew2, 8000);
    get_data();
    setInterval(function() { get_data(); }, 3000);
})
var cid = 0;

function get_data() {
    $.ajax({
        url: urlPost("rot_order/index"),
        type: "POST",
        dataType: "JSON",
        // data: { type: cid },
        success: function(res) {
            console.log(res)
            var data = res.data;
            if (res.code == 0) {
                cid = data.cate.id;
                $('.cate-name').html(data.cate.name);
                $('.cate-bili').html(data.cate.bili * 100);
                $('.day_deal').html(data.day_deal);
                // $('.hui-common-title-txt').attr('title',data.day_deal);
                $('.lock_deal').html(data.lock_deal);
                $('.price').html(data.price);
                $('.day_d_count').html(`${data.day_d_count}(${data.day_d_count_c})`);
                $('.day_l_count').html(data.day_l_count);
                $('.team_num').html(data.team_num);
            }
        },
        error: function(err) { console.log(err) }
    })
}
$('.self_btn').click(function() {
    layer.open({
        content: `<?php echo lang('Scrapping orders'); ?>...<br/><div class="loading"></div>`,
        btn: ["<?php echo lang('Stop rushing'); ?>", ],
        shadeClose: false,
        yes: function(index) {
            lay = index;
            clearTimeout(ajaxT);
            QS_toast.show("<?php echo lang('Stop ordering'); ?>", true);
            layer.close(index)
        }
    });

    ajaxT = setTimeout(function() {
        $.ajax({
            url: "<?php echo url('submit_order'); ?>",
            type: "POST",
            dataType: "JSON",
            data: { cid: cid },
            success: function(res) {
                console.log(res)
                status = true;
                if (res.code == 0) {
                    QS_toast.show(res.info, true);
                    layer.close(lay)
                    var timer = setTimeout(function() {
                        location.href = res.url
                    }, 1800)
                } else {
                    if (res.code == 5) {
                        layer.open({
                            anim: 'up',
                            content: res.info,
                            btn: [`<?php echo lang('confirm'); ?>`, `<?php echo lang('cancel'); ?>`],
                            shadeClose: false,
                            yes: function(index) {
                                if (res.url)
                                    location.href = res.url
                                layer.close();
                            },
                            no: function() {
                                layer.close();
                            }
                        });
                    } else {
                        QS_toast.show(res.info, true);
                        if (res.url) {
                            var linkTime = setTimeout(function() {
                                location.href = res.url
                            }, 3000);
                        } else {
                            var timer = setInterval(function() {
                                location.href = location.href
                            }, 3000);
                        }
                    }
                    layer.close(lay)
                }
            },
            error: function(err) { console.log(err) }
        })
    }, 3000)
})
</script></body></html>