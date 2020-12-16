<?php /*a:2:{s:63:"/www/wwwroot/45.118.248.72/application/index/view/my/index.html";i:1595224772;s:67:"/www/wwwroot/45.118.248.72/application/index/view/public/floor.html";i:1595241314;}*/ ?>
<html lang="en"><head><meta charset="UTF-8"><title><?php echo lang('Personal Center'); ?></title><meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"><meta content="yes" name="apple-mobile-web-app-capable"><meta content="black" name="apple-mobile-web-app-status-bar-style"><meta content="telephone=no" name="format-detection"><link rel="stylesheet" type="text/css" href="/res/common/css/hui.min.css?v=1595241398"><link rel="stylesheet" type="text/css" href="/public/css/style.min.css?v=1595241398&v2"><link rel="stylesheet" type="text/css" href="/statics/css/userstyle.min.css?v=1595241398"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="/static/plugs/layui/layui.all.js"></script><script src="/static/plugs/require/require.js"></script><!-- <script src="/static/admin.js?v=1595241398"></script> --><script src="/index/script/admin"></script><script src="/public/js/common.min.js?v=1595241398"></script></head><body><header><span><?php echo lang('My'); ?></span></header><div class="my_head_box"  style="position: relative;"><div class="my_infos"><img class="my_head_img" src="<?php echo htmlentities($info['headpic']); ?>" alt="" onerror="this.src='/public/img/head.png'"><div class="my_info_text"><?php echo htmlentities($info['username']); ?><p><?php echo lang('Invitation code'); ?>:<?php echo htmlentities($info['invite_code']); ?></p></div><div class="my_user_type"><img src="/public/img/vip.png"><span style="position: absolute;right:2px"><?php echo htmlentities($level['name']); ?></span><a href="/index/ctrl/vip.html" style="display:block"><div class="open_btns"><?php echo lang('Enable now'); ?></div></a></div></div><div class="my_score_box"><div class="my_score_box_item"><p><?php echo htmlentities(formatNumber($sell_y_num)); ?></p><?php echo lang('Profit'); ?></div><div class="my_score_box_item"><p><?php echo htmlentities(formatNumber($info['balance'])); ?></p><?php echo lang('Balance'); ?></div><div class="my_score_box_item"><p><?php echo htmlentities(formatNumber($info['freeze_balance'])); ?></p><?php echo lang('Freeze'); ?></div><div class="my_score_box_item" style="    padding-top: .7rem;  padding-left: .5rem;"><a href="<?php echo url('recharge/recharge_before'); ?>"><div class="score_btns">充值</div></a><a href="<?php echo url('ctrl/deposit_before'); ?>"><div class="score_btns">提现</div></a></div></div></div><div class="list_box"><div class="list_box_item"><a href="<?php echo url('ctrl/wallet'); ?>"><img src="/public/img/n_score.png"><p><?php echo lang('My Wallet'); ?></p></a></div><div class="list_box_item"><a href="<?php echo url('my/invite'); ?>"><img src="/public/img/n_qrcode.png"><p><?php echo lang('Invite friends and earn money together'); ?></p></a></div><div class="list_box_item"><a href="<?php echo url('ctrl/my_data'); ?>"><img src="/public/img/n_myinfo.png"><p><?php echo lang('Profile'); ?></p></a></div><div class="list_box_item"><a href="<?php echo url('ctrl/deposit_admin'); ?>"><img src="/public/img/n_with.png"><p><?php echo lang('Withdraw record'); ?></p></a></div><div class="list_box_item"><a href="<?php echo url('ctrl/junior2'); ?>"><img src="/public/img/n_teams.png"><p><?php echo lang('My team'); ?></p></a></div><div class="list_box_item"><a href="<?php echo url('ctrl/edit_pwd'); ?>"><img src="/public/img/n_epass.jpg"><p><?php echo lang('change Password'); ?></p></a></div><div class="list_box_item"><a href="<?php echo url('ctrl/edit_deposit_pwd'); ?>"><img src="/public/img/n_pass.png"><p><?php echo lang('transaction password'); ?></p></a></div><div class="list_box_item"><a href="<?php echo url('ctrl/team_bgk'); ?>"><img src="/public/img/n_team_m.png"><p><?php echo lang('Team commission'); ?></p></a></div><div class="list_box_item"><a href="<?php echo url('ctrl/bank'); ?>"><img src="/public/img/n_bank.png"><p><?php echo lang('Bank card binding'); ?></p></a></div><div class="list_box_item"><a href="<?php echo url('ctrl/receive_site'); ?>"><img src="/public/img/n_city.png"><p><?php echo lang('Address Management'); ?></p></a></div><div class="list_box_item"><a href="<?php echo url('ctrl/helps'); ?>"><img src="/public/img/n_help.png"><p><?php echo lang('Help center'); ?></p></a></div><div class="list_box_item"><a href="<?php echo url('recharge/recharge_admin'); ?>"><img src="/public/img/n_pay.png"><p><?php echo lang('Recharge Management'); ?></p></a></div></div><div class="logout_bvox"><a id="exit"><div class="logout_bnt"><?php echo lang('Exit'); ?></div></a></div><style>
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
        </div><a href="<?php echo url('support/index'); ?>" id="nav-forum"><?php if(request()->CONTROLLER() == 'Support'): ?><img class="news_img_icon" src="/public/img/icon-客服2.png"><?php else: ?><img class="news_img_icon" src="/public/img/icon-客服.png"><?php endif; ?><div class="hui-footer-text"><?php echo lang('Customer Service'); ?></div></a><a href="<?php echo url('my/index'); ?>" id="nav-my"><?php if(request()->CONTROLLER() == 'My'): ?><img class="news_img_icon" src="/public/img/icon-我的选中2.png"><?php else: ?><img class="news_img_icon" src="/public/img/icon-我的选中.png"><?php endif; ?><div class="hui-footer-text"><?php echo lang('My'); ?></div></a></div><a href="<?php echo url('rot_order/index'); ?>" id="footer-logo"><img src="/public/img/qd2.png" style=""><!-- <div id="footer-logo2"> --><!-- <img src="/statics/img/qd-1.png" style=""> --><!-- </div> --></a></footer><script src="/public/js/common.min.js?v=1595241398"></script><script>
$(".floor li").click(function() {
    $(this).addClass('floor-active').siblings().removeClass('floor-active')
})
</script><script>
    $(function() {
        $('#hui-footer a').eq(3).addClass("floor-active")
    })

    $("#exit").click(function() {
        layer.open({
            title: '',
            content: `<?php echo lang('Are you sure you want to log out'); ?>`,
            btn: [`<?php echo lang('confirm'); ?>`, `<?php echo lang('cancel'); ?>`],
            shadeClose: false,
            yes: function(index) {
                location.href = "<?php echo url('user/logout'); ?>"
            },
            no: function() {
                layer.close();
            }
        });
    })
    </script></body></html>