<?php /*a:2:{s:69:"/www/wwwroot/www.oabuhps.cn/application/index/view/ctrl/edit_pwd.html";i:1595483990;s:60:"/www/wwwroot/www.oabuhps.cn/application/index/view/main.html";i:1593514237;}*/ ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="Robots" content="noindex,nofollow"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><title><?php echo sysconf('site_name'); ?></title><link rel="stylesheet" type="text/css" href="/public/css/style.min.css?v=1595490278"><link rel="stylesheet" type="text/css" href="/public/css/ui.min.css?v=1595490278"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><style>
    .form {
            width: 90%;
            margin: .5rem auto;
        }

        .form .form_title {
            line-height: 1.5rem;
        }

        .input_box {
            border: 1px solid #e2dcdc;
            border-radius: 3px;
        }

        .input_box input {
            border: none;
            outline: none;
            width: 100%;
            text-indent: 10px;
        }

        .input_box input::placeholder {
            color: #a7a7a7;
        }

        .btn {
            margin: 2rem auto;
            width: 90%;
            height: 2rem;
            line-height: 2rem;
            border-radius: 7px;
            background:#9374EB;
            text-align: center;
            color: white;
        }
    </style></head><body><header><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('change Password'); ?></span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><section><div class="container"><div class="form"><p class="form_title"><?php echo lang('old password'); ?>:</p><div class="input_box"><input type="password" placeholder="<?php echo lang('Please enter the original password'); ?>" id="old"></div></div><div class="form"><p class="form_title"><?php echo lang('New password'); ?>:</p><div class="input_box"><input type="password" placeholder="<?php echo lang('Please enter a new password'); ?>" id="new"></div></div><div class="form"><p class="form_title"><?php echo lang('Confirm password'); ?>:</p><div class="input_box"><input type="password" placeholder="<?php echo lang('Enter new password again'); ?>" id="again"></div></div><div class="btn"><?php echo lang('confirm'); ?></div></div></section><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="https://cdn.jsdelivr.net/npm/dot@1.1.3/doT.min.js"type="text/javascript" ></script><script src="https://cdn.jsdelivr.net/npm/cleave.js@1.5.10/dist/cleave.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595490278"></script><script src="/public/js/common.min.js?v=1595490278"></script><script src="/public/js/city.js"></script><script src="/public/js/picker.min.js"></script><script>
var submit = true
$('.btn').click(function() {
    var old_pwd = $('#old').val(),
        new_pwd = $('#new').val(),
        again = $('#again').val();
    if (old_pwd == "") {
        QS_toast.show(`<?php echo lang('Please enter the original password'); ?>`, true)
    } else if (new_pwd == "") {
        QS_toast.show(`<?php echo lang('Please enter a new password'); ?>`, true)
    } else if (again == "") {
        QS_toast.show(`<?php echo lang('Enter new password again'); ?>`, true)
    } else if (again != new_pwd) {
        QS_toast.show(`<?php echo lang('Inconsistent passwords twice'); ?>`, true)
    } else {
        if (submit) {
            submit = false;
            $.ajax({
                url: urlPost("ctrl/set_pwd"),
                type: "POST",
                dataType: "JSON",
                data: { old_pwd: old_pwd, new_pwd: new_pwd },
                success: function(res) {
                    console.log(res)
                    if (res.code == 0) {
                        QS_toast.show(`<?php echo lang('Successfully modified'); ?>`, true);
                        var timer = setTimeout(function() {
                            history.back(-1)
                        }, 1500)
                    } else {
                        QS_toast.show(res.info, true);
                        submit = true;
                    }
                },
                error: function(err) {
                    console.log(err);
                    submit = true
                }
            })
        }
    }
})
</script></body></html>