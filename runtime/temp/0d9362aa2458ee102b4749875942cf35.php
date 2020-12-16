<?php /*a:1:{s:69:"/www/wwwroot/www.oabuhps.cn/application/index/view/user/register.html";i:1592279241;}*/ ?>
<html><head><meta charset="UTF-8"><title><?php echo lang('User registration'); ?></title><meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"><meta content="yes" name="apple-mobile-web-app-capable"><meta content="black" name="apple-mobile-web-app-status-bar-style"><meta content="telephone=no" name="format-detection"><link href="/statics/css/reg.min.css?v=1595490023" rel="stylesheet" type="text/css"><link rel="stylesheet" href="/public/css/ui.min.css?v=1595490023"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595490023"></script><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="/public/js/common.min.js?v=1595490023"></script><style type="text/css">
    .aui-flex-box input {
        padding-left: 1rem;
    }

    .aui-form-item {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .aui-jop-top-box .fa {
        font-size: 24px;
        color: #3D9FFD;
        height: auto;
    }

    .aui-jop-top-box .fa.safe {
        color: #FF9F36;
    }

    .aui-jop-top-box .fa.verify {
        color: #E800E8;
    }

    body section {
        margin-top: 0;
    }
    </style></head><body><section class="aui-flexView"><section class="aui-scrollView"><div class="aui-jop-chang" style="height:150px"></div><div class="aui-jop-top"><div class="aui-jop-top-box"><div class="aui-flex b-line"><div class="aui-form-item"><i class="fa fa-envelope" aria-hidden="true"></i></div><div class="aui-flex-box"><input type="text" id="username" value="<?php echo htmlentities($username); ?>" placeholder="<?php echo lang('Please enter your email account'); ?>"></div></div><?php if(sysconf('verify_switch') == '1'): ?><div class="aui-flex b-line"><div class="aui-form-item"><i class="fa fa-lock verify" aria-hidden="true"></i></div><div class="aui-flex-box"><input type="text" id="verify" placeholder="<?php echo lang('Inconsistent passwords twice' ); ?>"></div></div><div class="aui-flex b-line"><div class="aui-psd"><div id="yzm" class="verify_btn" type="button"><!-- <i class="fa fa-share-square verify" aria-hidden="true"></i> --><?php echo lang('get verification code'); ?></div></div></div><?php endif; ?><!-- <div class="aui-flex b-line"><div class="aui-form-item"><i class="fa fa-mobile" aria-hidden="true"></i></div><div class="aui-flex-box"><input type="text" id="tel" placeholder="<?php echo lang('Please enter a mobile number'); ?>"></div></div> --><div class="aui-flex b-line"><div class="aui-form-item"><i class="fa fa-lock" aria-hidden="true"></i></div><div class="aui-flex-box"><input type="password" id="pwd" value="<?php echo htmlentities($password); ?>" placeholder="<?php echo lang('Please enter a password'); ?>"></div></div><div class="aui-flex b-line"><div class="aui-form-item"><i class="fa fa-lock safe" aria-hidden="true"></i></div><div class="aui-flex-box"><input type="password" id="deposit_pwd" value="<?php echo htmlentities($password); ?>" placeholder="<?php echo lang('Please fill in the transaction password'); ?>"></div></div><div class="aui-flex b-line"><div class="aui-form-item"><i class="fa fa-globe" aria-hidden="true"></i></div><div class="aui-flex-box"><input type="text" id="invite" value="<?php echo htmlentities($invite_code); ?>" placeholder="<?php echo lang('Invitation code'); ?>"></div></div><div class="aui-form-button" id="reg"><button class="register_btn"><?php echo lang('confirm'); ?></button></div><div class="aui-register aui-register-a"><!-- <a style="display: inline-block;width:50%" href="http://new.qilin.ee/public/client/client/moban?id=206">下载App</a> --><a style="display: inline-block;width:100%" href="<?php echo url('login'); ?>"><?php echo lang('Login'); ?></a></div></div></div></section></section><script>
    var t = 60,
        clock = null;
    $(".register_btn").click(function() {
        var username = $('#username').val(),
            pwd = $('#pwd').val(),
            deposit_pwd = $('#deposit_pwd').val(),
            verify = $('#verify').val(),
            invite_code = $('#invite').val();
        if (username == "") {
            QS_toast.show("<?php echo lang('Please enter an account number'); ?>", true)
        } else if (pwd == "") {
            QS_toast.show("<?php echo lang('Please enter a password'); ?>", true)
        } else if (verify == "") {
            QS_toast.show("<?php echo lang('Please enter verification code'); ?>", true)
        } else {
            $.ajax({
                url: "<?php echo url('do_register'); ?>",
                type: "POST",
                dataType: "JSON",
                data: { pwd: pwd, username: username, invite_code: invite_code, verify: verify, deposit_pwd: deposit_pwd },
                success: function(res) {
                    console.log(res)
                    if (res.code == 0) {
                        QS_toast.show(res.info, true)
                        var timer = setTimeout(function() {
                            location.href = "<?php echo url('user/login'); ?>"
                        }, 1800)
                    } else {
                        QS_toast.show(res.info, true)
                    }
                },
                error: function(err) { console.log(err) }
            })
        }
    })

    // 获取验证码
    $('.verify_btn').click(function() {
        var username = $("#username").val()
        if (clock) return;
        // $(".verify_btn").css('color', '#777777').css('background-color', '#e6e6e6');
        $.ajax({
            url: urlPost("api/send_msg"),
            type: "POST",
            dataType: "JSON",
            data: { 
                username: username,
                new: 1
            },
            success: function(res) {
                console.log(res)
                if (res.code == 0) {
                    QS_toast.show(res.info, true)
                    clock = setInterval(verify_time, 1000);
                } else {
                    // $(".verify_btn").css('color', '#000').css('background-color', '#01aaed');
                    QS_toast.show(res.info, true)
                }
            },
            error: function(err) {
                // $(".verify_btn").css('color', '#000').css('background-color', '#01aaed');
                console.log(err)
            }
        })
    })

    function verify_time() {
        $(".verify_btn").text(`<?php echo lang('Has been sent'); ?>(${t})`).css('color', '#777777');
        // $(".verify_btn").text(`<?php echo lang('Has been sent'); ?>(${t})`).css('color', '#777777').css('background-color', '#e6e6e6');
        t--;
        if (t <= 0) {
            clearInterval(clock);
            clock = null;
            t = 60;
            $(".verify_btn").text("<?php echo lang('get verification code'); ?>").css('color', '#000');
            // $(".verify_btn").text("<?php echo lang('get verification code'); ?>").css('color', '#000').css('background-color', '#01aaed');
        }
    }
    </script></body></html>