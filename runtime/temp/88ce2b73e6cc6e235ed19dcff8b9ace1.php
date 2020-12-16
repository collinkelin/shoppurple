<?php /*a:2:{s:69:"/www/wwwroot/www.oabuhps.cn/application/index/view/ctrl/add_site.html";i:1595490237;s:60:"/www/wwwroot/www.oabuhps.cn/application/index/view/main.html";i:1593514237;}*/ ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="Robots" content="noindex,nofollow"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><title><?php echo sysconf('site_name'); ?></title><link rel="stylesheet" type="text/css" href="/public/css/style.min.css?v=1595490319"><link rel="stylesheet" type="text/css" href="/public/css/ui.min.css?v=1595490319"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><style>
    .form_container {
        border-top: 1px solid #f3f3f3;
        margin-top: 1rem;
    }

    .form {
        width: 90%;
        margin: .5rem auto;
    }

    .form .form_title {
        color: black;
        font-weight: 500;
    }

    .input_box {
        height: 100%;
        border: 1px solid #e2dcdc;
        border-radius: 3px;
    }

    .input_box input {
        border: none;
        outline: none;
        height: 100%;
        width: 100%;
        text-indent: 10px;
    }

    .input_box input::placeholder {
        color: #a7a7a7;
    }

    .btn {
        width: 90%;
        height: 2rem;
        line-height: 2rem;
        text-align: center;
        color: white;
        margin: 2rem auto 0;
        border-radius: 5px;
        background: #9374EB;
    }
</style></head><body><header><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('Add shipping address'); ?></span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><section><div class="container"><div class="form"><p class="form_title"><?php echo lang('Consignee name set'); ?></p><div class="input_box"><input type="text" placeholder="<?php echo lang('Please enter the consignee name'); ?>" id="name"></div></div><div class="form"><p class="form_title"><?php echo lang('Area'); ?>:</p><div class="input_box"><input type="text" placeholder="<?php echo lang('Please enter the shipping address'); ?>" id="area"></div></div><div class="form"><p class="form_title"><?php echo lang('Detailed address'); ?>:</p><div class="input_box"><input type="text" placeholder="<?php echo lang('Please fill in the detailed address'); ?>" id="site"></div></div><div class="form"><p class="form_title"><?php echo lang('cellphone number'); ?>:</p><div class="input_box"><input type="text" placeholder="<?php echo lang('Please enter a mobile number'); ?>" id="phone"></div></div><div class="btn"><?php echo lang('confirm'); ?></div></div></section><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="https://cdn.jsdelivr.net/npm/dot@1.1.3/doT.min.js"type="text/javascript" ></script><script src="https://cdn.jsdelivr.net/npm/cleave.js@1.5.10/dist/cleave.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595490319"></script><script src="/public/js/common.min.js?v=1595490319"></script><script src="/public/js/city.js"></script><script src="/public/js/picker.min.js"></script><script>
$(".btn").click(function() {
    var name = $("#name").val(),
        area = $('#area').val(),
        site = $('#site').val(),
        phone = $('#phone').val();
    if (name == "") {
        QS_toast.show(`<?php echo lang('Please enter the consignee name'); ?>`, true)
    } else if (area == "") {
        QS_toast.show(`<?php echo lang('Please enter the shipping address'); ?>`, true)
    // } else if (site == "") {
    //     QS_toast.show(`<?php echo lang('Please fill in the detailed address'); ?>`, true)
    } else if (phone == "") {
        QS_toast.show(`<?php echo lang('Please enter a mobile number'); ?>`, true)
    } else {
        var token = "<?php echo htmlentities(app('request')->token()); ?>";
        var data = { name: name, tel: phone, address: site, area: area, token: token }
        $.ajax({
            url: urlPost('my/add_address'),
            type: "POST",
            dataType: "JSON",
            data: data,
            success: function(res) {
                console.log(res)
                if (res.code == 0) {
                    QS_toast.show(res.info, true);
                    var timer = setTimeout(function() {
                        history.back(-1)
                    }, 1800)
                } else {
                    QS_toast.show(res.info, true)
                }
            },
            error: function(err) {
                console.log(err)
            }
        })
    }
})
</script></body></html>