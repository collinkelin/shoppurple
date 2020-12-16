<?php /*a:2:{s:68:"/www/wwwroot/45.118.248.72/application/index/view/support/index.html";i:1595217387;s:67:"/www/wwwroot/45.118.248.72/application/index/view/public/floor.html";i:1595241314;}*/ ?>
<html><head><meta charset="utf-8"><meta name="viewport" content="width = device-width, initial-scale = 1.0, maximum-scale = 1.0, user-scalable = 0"><title><?php echo lang('Support'); ?></title><link rel="stylesheet" type="text/css" href="/res/common/css/hui.min.css?v=1595241388"><link rel="stylesheet" type="text/css" href="/statics/css/userstyle.min.css?v=1595241388"><link rel="stylesheet" type="text/css" href="/statics/css/base.min.css?v=1595241388"><link rel="stylesheet" type="text/css" href="/statics/css/user.min.css?v=1595241388"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script><script src="/public/js/common.min.js?v=1595241388"></script><style>
        .btn{
            width: 50%;
            line-height: 1.4rem;
            font-size: .56rem;
            color: #fff;
            text-align: center;
            background-color: #7669fd;
            border-radius: .7rem;
            display: block;
            margin: 0 auto;
        }
        .kfcon{
            padding: 5px;
            border-radius: 5px;
            background-color: #fe6435;
            width: 100%;
            text-align: center;
            border: 1px solid #fe6435;
        }
        .kfcon .title{
            color: #ff8405;
            font-size: 15px;
        }
        .kfcon .qrcode{
            width: 200px;
            height: auto;
            margin: auto;
        }
        .transfer-save{
            width: 100%;
            overflow: hidden;
            /* position: absolute; */
            /* background-color: #7669fd; */
            left: 0;
            bottom: .5rem;
            margin-bottom: 10px;
        }

        .jq22-coll-title h2 {
            position: relative;
            font-size: 15px;
            font-weight: normal;
            color: #212121;
            padding-left: 0.5rem;
        }
        .jq22-coll-title h2:after {
            content: '';
            position: absolute;
            z-index: 0;
            top: 3px;
            left: 0;
            width: 3px;
            height: 15px;
            background: #e66c67;
            border-radius: 5px;
        }
        .jq22-coll-title {
            padding: 15px;
        }
    </style></head><body><header class="header"><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('Support'); ?></span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><section class="container" style="padding-bottom: 80px"><div class="no-content" style="padding:1rem;padding-bottom: 5px;"><img class="" src="/public/img/kefuaa.png" alt="" style="margin-left:25%;width: 50%;"><?php foreach($list as $key=>$vo): if(!(empty($vo['url']) || (($vo['url'] instanceof \think\Collection || $vo['url'] instanceof \think\Paginator ) && $vo['url']->isEmpty()))): ?><div class="transfer-save" style=""><?php if($vo['type'] == 1): ?><a href="<?php echo url('live'); ?>?id=<?php echo htmlentities($vo['id']); ?>" class="btn" style="" id="lxkf"><?php echo htmlentities($vo['title']); ?></a><?php else: ?><a href="<?php echo htmlentities($vo['url']); ?>" class="btn" style="" id="lxkf"><?php echo htmlentities($vo['title']); ?>:<?php echo htmlentities($vo['account']); ?></a><?php endif; ?></div><?php else: ?><p><div class="bg-yellow margin-top kfcon" style=""><p class="title"><?php echo htmlentities($vo['title']); ?>:<?php echo htmlentities($vo['account']); ?></p><?php if(!(empty($vo['qr_code']) || (($vo['qr_code'] instanceof \think\Collection || $vo['qr_code'] instanceof \think\Paginator ) && $vo['qr_code']->isEmpty()))): ?><p><img class="qrcode" src="<?php echo htmlentities($vo['qr_code']); ?>" alt=""></p><?php endif; ?></div></p><?php endif; ?><?php endforeach; ?></div><div class="jq22-coll-title"><h2><?php echo lang('Help documentation'); ?></h2></div><div class="article-box"><?php foreach($msg as $key=>$vo): ?><a href="<?php echo url('detail',array('id'=>$vo['id'])); ?>?id=<?php echo htmlentities($vo['id']); ?>" class="article-list"><h3 style="color:#6F6F6F"><?php echo htmlentities($vo['title']); ?></h3></a><?php endforeach; ?><input type="hidden" name="kefu" value=""></div></section><style>
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
        </div><a href="<?php echo url('support/index'); ?>" id="nav-forum"><?php if(request()->CONTROLLER() == 'Support'): ?><img class="news_img_icon" src="/public/img/icon-客服2.png"><?php else: ?><img class="news_img_icon" src="/public/img/icon-客服.png"><?php endif; ?><div class="hui-footer-text"><?php echo lang('Customer Service'); ?></div></a><a href="<?php echo url('my/index'); ?>" id="nav-my"><?php if(request()->CONTROLLER() == 'My'): ?><img class="news_img_icon" src="/public/img/icon-我的选中2.png"><?php else: ?><img class="news_img_icon" src="/public/img/icon-我的选中.png"><?php endif; ?><div class="hui-footer-text"><?php echo lang('My'); ?></div></a></div><a href="<?php echo url('rot_order/index'); ?>" id="footer-logo"><img src="/public/img/qd2.png" style=""><!-- <div id="footer-logo2"> --><!-- <img src="/statics/img/qd-1.png" style=""> --><!-- </div> --></a></footer><script src="/public/js/common.min.js?v=1595241388"></script><script>
$(".floor li").click(function() {
    $(this).addClass('floor-active').siblings().removeClass('floor-active')
})
</script><script type="text/javascript">
    $(function() {
        $('#hui-footer a').eq(2).addClass("floor-active")
    })
    </script></body></html>