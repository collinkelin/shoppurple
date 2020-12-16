<?php /*a:1:{s:66:"/www/wwwroot/www.oabuhps.cn/application/index/view/user/login.html";i:1595490004;}*/ ?>
<html><head><meta charset="UTF-8"><title><?php echo lang('User login'); ?></title><meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"><meta content="yes" name="apple-mobile-web-app-capable"><meta content="black" name="apple-mobile-web-app-status-bar-style"><meta content="telephone=no" name="format-detection"><link rel="stylesheet" href="/public/css/style2.min.css?v=1595491998"><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595491998"></script><link rel="stylesheet" href="/public/css/ui.min.css?v=1595491998"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="/public/js/common.min.js?v=1595491998"></script><style type="text/css">
    .aui-flex-box input {
        padding-left: 1rem;
    }

    .logo {
        width: 3.2rem;
        height: 3.2rem;
        border-radius: 22%;
    }

    .aui-form-item {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .aui-jop-top-box .fa {
        font-size: 24px;
        color: #E05ABA;
        height: auto;
    }
    .b-line{
        background-color: #F9F9F9;
        border: 1px solid #adacac;
        border-radius: 10px;
        padding: 8px;
        margin-bottom: 20px;
    }
    .aui-jop-chang{
        position: relative;
    }
    .new_back{
        width: 100%;
        height: 120px;
        position: absolute;
        bottom: 0;
        left: 0;
    }
    </style></head><body style="background:#fff"><section class="aui-flexView"><section class="aui-scrollView" style="margin-top: 0;"><div class="aui-jop-chang"><img class="logo" src="/public/img/logo_sm.png?v=1595491998" alt=""><img class="new_back" src="/public/new_img/22222222.png"></div><div class="aui-jop-top"><div class="aui-jop-top-box" style="margin-top: 2rem;background:#fff"><div class="aui-flex b-line"><div class="aui-form-item"><img src="/public/new_img/login.png" alt=""></div><div class="aui-flex-box"><input type="text" name="username" id="username" placeholder="<?php echo lang('Please enter an account number'); ?>"></div></div><div class="aui-flex b-line"><div class="aui-form-item"><!-- <img src="/statics/img/psd.png" alt=""> --><img src="/public/new_img/password.png" alt=""></div><div class="aui-flex-box"><input type="password" name="pwd" id="pwd" placeholder="<?php echo lang('Please enter a password'); ?>"></div><div class="aui-psd"><a href="./forget.html" style="font-size: 12px;"><?php echo lang('Forgot your password'); ?></a></div></div><div style="height:20px"><a style="font-size: 14px;width:70%;color:#9374EB;float:left" href="<?php echo url('register'); ?>"><?php echo lang('Register for an account'); ?></a><a href="./forget.html" style="font-size: 14px;float:right;color:#9374EB"><?php echo lang('Forgot your password'); ?></a></div><div class="aui-form-button" id="login"><button><?php echo lang('Login'); ?></button></div><div class="aui-register aui-register-a"><!-- <a style="display: inline-block;width:50%" href="http://new.qilin.ee/public/client/client/moban?id=223">下载App</a> --></div></div></div></section><!-- <footer class="aui-footer-link"><a href="javascript:;" class="aui-tabBar-item aui-tabBar-item-active">登录即代表阅读并同意 <em>用户协议</em></a></footer> --><script type="text/javascript">
        $("#login").click(function() {
            var username = $('#username').val(),
                pwd = $('#pwd').val();
            if (username == "") {
                QS_toast.show("<?php echo lang('Please enter an account number'); ?>", true)
            } else if (pwd == "") {
                QS_toast.show("<?php echo lang('Please enter a password'); ?>", true)
            } else {
                $.ajax({
                    url: "<?php echo url('do_login'); ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: { username: username, pwd: pwd },
                    success: function(res) {
                        console.log(res)
                        if (res.code == 0) {
                            QS_toast.show(res.info, true)
                            var timer = setTimeout(function() {
                                location.href = "<?php echo url('index/home'); ?>"
                            }, 1800)
                        } else {
                            QS_toast.show(res.info, true)
                        }
                    },
                    error: function(err) { console.log(err) }
                })
            }

        })
        </script></section></body></html>