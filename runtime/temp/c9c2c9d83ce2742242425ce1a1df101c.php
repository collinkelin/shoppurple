<?php /*a:3:{s:67:"/www/wwwroot/www.oabuhps.cn/application/index/view/order/index.html";i:1595437393;s:60:"/www/wwwroot/www.oabuhps.cn/application/index/view/main.html";i:1593514237;s:68:"/www/wwwroot/www.oabuhps.cn/application/index/view/public/floor.html";i:1595435444;}*/ ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="Robots" content="noindex,nofollow"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><title><?php echo sysconf('site_name'); ?></title><link rel="stylesheet" type="text/css" href="/public/css/style.min.css?v=1595492186"><link rel="stylesheet" type="text/css" href="/public/css/ui.min.css?v=1595492186"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><link rel="stylesheet" type="text/css" href="/res/common/css/hui.min.css?v=1595492186"><style>
body {
    background: #fff;
}

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
    background-color: #C9B9F7;
    color: #9374EB;
    border-bottom: 1px solid #C9B9F7;
}

nav>p {
    width: 33%;
    text-align: center;
}

.cont>div {
    display: none;
}

.list {
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
    background: #eeeeee;
}

.info_data {
    max-width: 55%;
    margin: 0 0 0 1rem;
    display: flex;
    justify-content: space-between;
    flex-direction: column;
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
.money {
    font-size: 12px
}
</style></head><body><header class="hui-header"><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('Acquisition List'); ?></span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><section><div class="container mescroll" style="padding: 3rem 0;" id="mescroll"><nav><p data-type="1" class="nav_active"><?php echo lang('Pending'); ?></p><p data-type="2"><?php echo lang('Frozen'); ?></p><p data-type="3"><?php echo lang('Completed'); ?></p></nav><div class="cont"><div style="display: block;"><ul class="list"></ul></div></div></div></section><script type="text/html" id="list-tpl">
{{ for(var key in it.data) { }}
<li id="{{= it.data[key].id }}" data-url="{{= it.data[key].infourl }}"><div class="order_num">{{= it.data[key].id }}</div><div class="order_info"><div class="info_img"><img src="{{= it.data[key].goods_pic }}" alt=""></div><div class="info_data"><p class="info_name">{{= it.data[key].goods_name }}</p><p class="info_store">{{= it.data[key].shop_name }}</p><p class="info_store"><?php echo lang('Order timed out'); ?>:{{= timeTransform(it.data[key].endtime) }}</p></div><div class="info_money"><p class="money" style="margin-bottom: .5rem;">￥{{= milliFormat(it.data[key].goods_price) }}</p><p class="money" style="margin-bottom: .5rem;color:#00d44b">￥{{= milliFormat(it.data[key].commission) }}</p><p>x<span class="info_num">{{= it.data[key].goods_count }}</span></p></div></div></li>
{{  } }}
</script><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="https://cdn.jsdelivr.net/npm/dot@1.1.3/doT.min.js"type="text/javascript" ></script><script src="https://cdn.jsdelivr.net/npm/cleave.js@1.5.10/dist/cleave.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595492186"></script><script src="/public/js/common.min.js?v=1595492186"></script><script src="/public/js/city.js"></script><script src="/public/js/picker.min.js"></script><style>
@font-face {
    font-family: "iconfont";
    src: url('/res/common/fonts/font_1463191_xdm42ti8gyp.eot?t=1571290693237');
    /* IE9 */
    src: url('/res/common/fonts/font_1463191_xdm42ti8gyp.eot?t=1571290693237#iefix') format('embedded-opentype'),
        /* IE6-IE8 */
        url('data:application/x-font-woff2;charset=utf-8;base64,d09GMgABAAAAAAaIAAsAAAAAC+gAAAY8AAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHEIGVgCDQAqKVIkNATYCJAMYCw4ABCAFhG0HZhtJChFVpOuS/UiwcYuL9VYXVdQkvcl/Hjf9c/OSQBNaQtGasU7EYeatT5T/5xo2p5tDoe1UFL7pRPmmNEAAAdmttXxg+8JEmEP5FlqPfiELR7nJ5h7G1+kqVSErZJfW1JYdCodx7+rNXYlIIcgKWV2hKhQOuMBuY1thN6PlVKLz0kkveNuBAEhIRjWQo12XPtCCB3cdAaCRH7sGQmuzgg/hErQG0edRnmglBGi5JdxdACvUv6c3qENagIPAwO3UY5hzCFo9x/NlTI2oKGYq1PzcAK5PAQxANQA8QBN9vaPB9Gw1MEjvNI82wCetBQcur1rz8+7Pl0UigGFStkgt8WSdrC3/8DgwCACJ4LQ60LvkBOA5RAIIeG4igAeemwnggOfdCWDA82VWCDCKObaaALoHcD1Ar7YcAwcNePCV2tuyM7qKskanswoP6KL0Cm+bIXkyy0/bt0qrmmj9ec/ZRK7F7qdnn1bO8Zl3PZNDoVyf18Gzp308wSq5pRV7TphzmHtPyS5ANvl8JX6rRSb3Hqt/W5yDrKqJ6wNpKFl+vUFUTltC7a89S1NN28wVCcFg7tOnfdRD06Tt5+PJXWqRvF4gcDbzaSImnbDe6vKXmIf6oO6UJC5wdph87FESVJ8s56hm17A8bIhcDT3nLKYNT2aQ25Ll+V5A3ZUl79iT4CYq3213kxQK5T17llPPTVt3wk1BbjfJ0e72q6by09LWs+q5rB1nEkpk+ar2ZcUZDWkr61BPqdtf4lO9ErlIxtpEe27u+OsLhQZliK2Tg8GBfnepxxQ46Tkdr1q8at+l5RMPqrqiKmIYCtMU5wWD05T4ZL+capuupwynuXLy/NvKTH282y1dbd5R2EJN9BM3P6Tz7CbLKqSTIPF6QSLZt8vMe3dKMX5qobsn1XJ/xVkSJndZOTZeuviNtf7sjoo2Z1D9/0mtKCC32vdpaNjEE73i26+nXB8/tgIlL8V+LWYv51r2TDxz/969GkNxeszoxXpHqkn/FzHtntg9WkbD9Jlh5Z+4RcSiThtOR/Hop9T9JvVf25RQ6pkleir1FOVVwjeTnL54atPaYGgtXn5M1R4Rh0BtCgraoC3yC6hNxh7Uu6guDu+KCesiB48fjOjCMUvCC8JlKRd71fr+3A+nFscHvjl2Uh9tiO51Mabs6urjPWKFsLBIXCxMFr/WGXRV149aN6pKtBL9tThMXMCWDAuxaLIqYNWYle2vP93NdrHXnxi2W5cdYQqkZtdazICR3JJaQFrCott7lnH89Nu7ExYagNQyIzAywk8AsVkD17k/UgaQwWmzPJzSbMrDlV3ziMQZtoNbUie3Xq7r+m/yyOwcnTT9svTO8s78JZaQ0bv36Eqpup0nW+dz4/yB8RxDMtKRwvX7x1cH/K/HU7/Zs/tJKjo5qJ/5jrNm5/KO2Ltm168vGgV/cZnuGuqPaJVi17b+tjMi8wto8hQunyuYPKmAmKrghnHbBCcVFpGD2hYXt0VbnELUlhyuieZ5Gc0yTfyht28PMh4R0icajLVrxf8gs3UPHqyTVIwQTseQqYPGP/7e1XaI65tfcuAcO9c1pI2th96pVFOcerviUJF4d1Y1e7Vie5NZ+bMa2RuFHfpZYbx8fY4y68QwZgIAIm9YATNH/omovJapQOQC281a09VBtpVVAQDWi/vz4FqEFhXLBvXXN/tNK//fEp/eT3FSV3rpEaJDbNmhZOY1IaSI8qPg1OJXCxGHKk15uiaI8EIDbJHSIycIRCgYi6/y0ptjlwIELcwmcNAgCRi0yCTz9GogoMjqqcUTvSVIqnp9WmbElmhRAKr4BhDFD3BiXABG8Uvm6X8gsPhXiWC2BEluxyVl9uLhzKgELegnREODExxy0Mrf0E+dYqtC5X0hp5iAKi+dhVcckPO4I918LeLAMfXwwnbDriMYmRo0kgeR8VQUzu+dckP97KAthhSBLKA9gcggAycPFwfX779B3qSjcEjft/gXxJI4PqjkyhjkV+0Qq29dapMbrybkdkBsZaQHXsiNOj5LwOg/r4EMkQsp0qOTgga5uNq8fH0/lUcAJNybiXHEiCeBRNJAfFRDEzcp0PTErUw6KsoOd4zachKzo42Dt2rIntEEHLwn0TKbAQ==') format('woff2'), url('/res/common/fonts/font_1463191_xdm42ti8gyp.woff?t=1571290693237') format('woff'), url('/res/common/fonts/font_1463191_xdm42ti8gyp.ttf?t=1571290693237') format('truetype'),
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
    padding: 0px 0px 0 0;
    background-color: #eee;
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
</style><footer><div id="hui-footer" style="    overflow: initial;"><a href="<?php echo url('index/home'); ?>" id="nav-home" style="background:#EEEEEE"><?php if(request()->CONTROLLER() == 'Index'): ?><img class="news_img_icon" src="/public/new_img/首页选中.png"><?php else: ?><img class="news_img_icon" src="/public/new_img/首页1.png"><?php endif; ?><div class="hui-footer-text"><?php echo lang('Home btn'); ?></div></a><a href="<?php echo url('order/index'); ?>" id="nav-news" style="background:#EEEEEE" ><?php if(request()->CONTROLLER() == 'Order'): ?><img class="news_img_icon" src="/public/new_img/记录选中.png"><?php else: ?><img class="news_img_icon" src="/public/new_img/记录.png"><?php endif; ?><div class="hui-footer-text"><?php echo lang('Record'); ?></div></a><div style="width:15%;height:58px;text-align:center;background:#eee;border:1px solid #ccc;margin-top:-10px;border-radius:10px">
            &nbsp;
        </div><a href="<?php echo url('support/index'); ?>" id="nav-forum" style="background:#EEEEEE"><?php if(request()->CONTROLLER() == 'Support'): ?><img class="news_img_icon" src="/public/new_img/客服选中.png"><?php else: ?><img class="news_img_icon" src="/public/new_img/客服.png"><?php endif; ?><div class="hui-footer-text"><?php echo lang('Customer Service'); ?></div></a><a href="<?php echo url('my/index'); ?>" id="nav-my" style="background:#EEEEEE"><?php if(request()->CONTROLLER() == 'My'): ?><img class="news_img_icon" src="/public/new_img/我的选中.png"><?php else: ?><img class="news_img_icon" src="/public/new_img/我的.png"><?php endif; ?><div class="hui-footer-text"><?php echo lang('My'); ?></div></a></div><style>
        .logos{
            position: absolute;
            z-index: 50;
            top: -12px;
            left: -2px;
        }
    </style><a href="<?php echo url('rot_order/index'); ?>" id="footer-logo"><?php if(request()->CONTROLLER() == 'RotOrder'): ?><img class="logos" src="/public/new_img/d2.png" style="height:30px;width:30px;top:-28px;left:18px"><p style="position: absolute;top:-18px;left:17px;color:#8c1bab">抢单</p><?php else: ?><img class="logos" src="/public/new_img/d1.png" style="height:30px;width:30px;top:-28px;left:18px"><p style="position: absolute;top:-18px;left:17px">抢单</p><?php endif; ?><!-- <div id="footer-logo2"> --><!-- <img src="/statics/img/qd-1.png" style=""> --><!-- </div> --></a></footer><script src="/public/js/common.min.js?v=1595492186"></script><script>
$(".floor li").click(function() {
    $(this).addClass('floor-active').siblings().removeClass('floor-active')
})
</script><script>
var page = 1,
    fpage = 1,
    mpage = 1,
    fcont = 0,
    mcont = 0;
var type = 1;
$(function() {
    $('#hui-footer a').eq(1).addClass("floor-active")
    type = $('nav>p').eq(0).data('type');
    wait(page, type);
});

$(window).scroll(function() {
    var scroH = $(document).scrollTop();
    var viewH = $(window).height();
    var contentH = $(document).height();
    if (scroH > 100) {

    }
    if (contentH - (scroH + viewH) <= 10) {
        page++;
        wait(page, type);
    }
    if (contentH = (scroH + viewH)) {

    }
});

// tab
$('nav>p').click(function() {
    var _ind = $(this).index();
    type = $(this).data('type');
    page = 1;
    $(this).addClass("nav_active").siblings().removeClass("nav_active");
    $(".list").html('');
    wait(page, type);
});

$(".list").on('click', 'li', function(e) {
    console.log($(this));
    location.href = $(this).data('url');
});

function wait(page, type) {
    $.ajax({
        url: urlPost("order/order_list"),
        type: "POST",
        dataType: "JSON",
        data: { page: page, type: type },
        success: function(res) {
            console.log(res);
            if (res.code == 0) {
                var list = res.data;
                if (page != 1 && list.length == 0) {
                    QS_toast.show(`<?php echo lang('No more data'); ?>..`, true)
                }
                if (page == 1 && list.length == 0) {
                    $(".list").append(`<li class="no-data"><?php echo lang('No data'); ?>...</li>`)
                }
                var tpl1 = doT.template($("#list-tpl").html());
                $('.list').append(tpl1(res));
            }
        },
        error: function(err) { console.log(err) }
    })
}
</script></body></html>