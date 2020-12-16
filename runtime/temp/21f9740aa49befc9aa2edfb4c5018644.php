<?php /*a:1:{s:69:"/www/wwwroot/www.oabuhps.cn/application/index/view/ctrl/set_site.html";i:1595485645;}*/ ?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><title><?php echo lang('Edit shipping address'); ?></title><link rel="stylesheet" href="/public/css/style.min.css?v=1595490317"><link rel="stylesheet" href="/public/css/ui.min.css?v=1595490317"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="/public/js/common.min.js?v=1595490317"></script><style>
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
            margin: 5rem auto 0;
            border-radius: 5px;
            background: #9374EB;
        }
    </style></head><body><header><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('Edit shipping address'); ?></span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><section><div class="container"><div class="form"><p class="form_title"><?php echo lang('Consignee name set'); ?></p><div class="input_box"><input type="text" placeholder="<?php echo lang('Please enter the consignee name'); ?>" id="name"></div></div><div class="form"><p class="form_title"><?php echo lang('Area'); ?>:</p><!-- <div class="area" id="area_s" style="color: #777777;"><?php echo lang('Please select a shipping address'); ?></div> --><div class="input_box"><input type="text" placeholder="<?php echo lang('Please enter the shipping address'); ?>" id="area"></div></div><div class="form"><p class="form_title"><?php echo lang('Detailed address'); ?>:</p><div class="input_box"><input type="text" placeholder="<?php echo lang('Please fill in the detailed address'); ?>" id="site"></div></div><div class="form"><p class="form_title"><?php echo lang('cellphone number'); ?>:</p><div class="input_box"><input type="number" placeholder="<?php echo lang('Please enter a mobile number'); ?>" id="phone"></div></div><div class="btn"><?php echo lang('confirm'); ?></div></div></section></body><!-- <script src="/public/js/city.js?v=1595490317"></script> --><script src="/public/js/picker.min.js"></script><!-- <script src="/public/js/index.js"></script> --><script src="/public/js/ui.js?v=1595490317"></script><script>
var submit = true,
    site_id = sessionStorage.getItem("site_id");
$(function() {
    $.ajax({
        url: urlPost('my/edit_address'),
        type: "GET",
        dataType: "JSON",
        data: { id: site_id },
        success: function(res) {
            if (res.code == 0) {
                $("#name").val(res.data.name);
                $("#area_s").html(res.data.area);
                $("#area").val(res.data.area);
                $("#site").val(res.data.address);
                $('#phone').val(res.data.tel)
            }
        },
        error: function(err) { console.log(err) }
    })
})

// 提交事件
$(".btn").click(function() {
    var name = $("#name").val(),
        area = $('#area').val(),
        site = $('#site').val(),
        phone = $('#phone').val();
    if (name == "") {
        QS_toast.show(`<?php echo lang('Please enter the consignee name'); ?>`, true)
    } else if (area == "") {
        QS_toast.show(`<?php echo lang('Please select a shipping address'); ?>`, true)
    // } else if (site == "") {
    //     QS_toast.show(`<?php echo lang('Please fill in the detailed address'); ?>`, true)
    } else if (phone == "") {
        QS_toast.show(`<?php echo lang('Please enter a mobile number'); ?>`, true)
    } else {
        var data = { id: site_id, name: name, tel: phone, address: site, area: area }
        if (submit) {
            submit = false;
            $.ajax({
                url: urlPost('my/edit_address'),
                type: "POST",
                dataType: "JSON",
                data: data,
                success: function(res) {
                    console.log(res)
                    if (res.code == 0) {
                        QS_toast.show(res.info, true)
                    } else {
                        QS_toast.show(res.info, true)
                    }
                    submit = true;
                },
                error: function(err) { console.log(err) }
            })
        }
    }
})
</script></html>