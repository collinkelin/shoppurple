<?php /*a:1:{s:65:"/www/wwwroot/45.118.248.72/application/index/view/user/login.html";i:1595239456;}*/ ?>
<html><head><meta charset="UTF-8"><title><?php echo lang('User login'); ?></title><meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"><meta content="yes" name="apple-mobile-web-app-capable"><meta content="black" name="apple-mobile-web-app-status-bar-style"><meta content="telephone=no" name="format-detection"><link rel="stylesheet" href="/public/css/style2.min.css?v=1595241070"><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595241070"></script><link rel="stylesheet" href="/public/css/ui.min.css?v=1595241070"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="/public/js/common.min.js?v=1595241070"></script><style type="text/css">
    .aui-flex-box input {
        padding-left: 1rem;
    }

    .aui-jop-chang img {
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
        border: 1px solid #adacac;
        border-radius: 10px;
        padding: 8px;
        margin-bottom: 20px;
    }
    </style></head><body style="background:#CCE098"><section class="aui-flexView"><section class="aui-scrollView" style="margin-top: 0;"><div class="aui-jop-chang"><img src="/public/img/logo_sm.png?v=1595241070" alt=""></div><div class="aui-jop-top"><div class="aui-jop-top-box" style="margin-top: 2rem;"><div class="aui-flex b-line"><div class="aui-form-item"><img src="/public/img/zhanghao.png" alt=""></div><div class="aui-flex-box"><input type="text" name="username" id="username" placeholder="<?php echo lang('Please enter an account number'); ?>"></div></div><div class="aui-flex b-line"><div class="aui-form-item"><!-- <img src="/statics/img/psd.png" alt=""> --><img src="/public/img/mima.png" alt=""></div><div class="aui-flex-box"><input type="password" name="pwd" id="pwd" placeholder="<?php echo lang('Please enter a password'); ?>"></div><div class="aui-psd"><a href="./forget.html" style="font-size: 12px;"><?php echo lang('Forgot your password'); ?></a></div></div><div class="aui-form-button" id="login"><button><?php echo lang('Login'); ?></button></div><div class="aui-register aui-register-a"><!-- <a style="display: inline-block;width:50%" href="http://new.qilin.ee/public/client/client/moban?id=223">下载App</a> --><a style="display: inline-block;width:100%;color:#739324" href="<?php echo url('register'); ?>"><?php echo lang('Register for an account'); ?></a></div></div></div></section><!-- <footer class="aui-footer-link"><a href="javascript:;" class="aui-tabBar-item aui-tabBar-item-active">登录即代表阅读并同意 <em>用户协议</em></a></footer> --><script type="text/javascript">
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