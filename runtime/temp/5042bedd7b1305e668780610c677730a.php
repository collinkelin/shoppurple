<?php /*a:2:{s:73:"/www/wwwroot/www.oabuhps.cn/application/index/view/recharge/recharge.html";i:1595490093;s:60:"/www/wwwroot/www.oabuhps.cn/application/index/view/main.html";i:1593514237;}*/ ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="Robots" content="noindex,nofollow"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><title><?php echo sysconf('site_name'); ?></title><link rel="stylesheet" type="text/css" href="/public/css/style.min.css?v=1595490115"><link rel="stylesheet" type="text/css" href="/public/css/ui.min.css?v=1595490115"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><style>
        .form {
            height: 2rem;
            line-height: 2rem;
            color: rgb(240, 98, 96);
            display: flex;
            justify-content: space-between;
            flex-direction: row;
            padding: 0 .5rem;
            border-bottom: 1px solid #eeeeee;
        }

        .form>input {
            border: none;
            outline: none;
            margin-right: auto;
            color: rgb(240, 98, 96);
            margin-left: 1rem;
            width:59%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .form_title {
            font-size: .5rem;
            width: 25%;
        }

        .copy_btn {
            color: #60aef0;
            width: 16%;
            text-align: center;
            height: 2rem;
            line-height: 2rem;
        }

        #bank_site {
            margin-right: auto;
            margin-left: 1rem;
            width:80%;
        }

        .user_info {
            height: 2rem;
            line-height: 2rem;
            display: flex;
            padding: 0 .5rem;
            border-bottom: 1px solid #eeeeee;
        }

        .user_info>div:first-child {
            width: 20%;
            height: 100%;
            display: flex;
        }

        .icon {
            width: 1.2rem;
            height: 1.2rem;
            background-size: cover;
            background-repeat: no-repeat;
            margin: auto 0 auto auto;
        }

        .user_info>input {
            margin-left: 1rem;
            width: 60%;
            border: none;
            outline: none;
        }

        .user_box>.user_info:nth-child(1) .icon {
            background-image: url(/public/img/name.png);
        }

        .user_box>.user_info:nth-child(2) .icon {
            background-image: url(/public/img/phone.png);
            background-size: 80%;
            background-position: 4px 0px;
            width: 1.4rem;
            height: 1.4rem;
        }

        .user_box>.user_info:nth-child(3) .icon {
            background-image: url(/public/img/je.png);
        }

        .btn {
            width: 90%;
            height: 2rem;
            line-height: 2rem;
            text-align: center;
            color: white;
            margin: 1rem auto 0;
            border-radius: 5px;
            background: #9374EB;
        }

        .file_container {
            margin-top: 10px;
        }

        .file {
            width: 60%;
            margin: 0 auto;
            height: 6rem;
            background: #dedede;
            position: relative;
        }

        .file::before {
            content: "";
            height: 1px;
            width: 3rem;
            background: white;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
        }

        .file::after {
            content: "";
            width: 1px;
            height: 3rem;
            background: white;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
        }

        .file input {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            opacity: 0;
            z-index: 233;
        }

        .file img {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            z-index: 230;
        }
        .wrin{
            padding:0 .3rem;
            color:red;
            width:90%;
            margin:.5rem auto;
        }
    </style></head><body><header><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span class="main-title"><?php echo htmlentities($pay['name']); ?></span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><section><div class="container"><p class="wrin wrin-description"><?php echo $pay['name_description']; ?></p><div class="user_box"><div class="user_info"><div><i class="fa fa-user" aria-hidden="true" style="color: #9A0B98;font-size: 40px;margin: auto 0 auto auto;"></i></div><input type="text" placeholder="<?php echo lang('Please enter recharge name'); ?>" id="user_name"></div><div class="user_info"><div><i class="fa fa-phone" aria-hidden="true" style="color: #9A0B98;font-size: 40px;margin: auto 0 auto auto;"></i></div><input type="number" placeholder="<?php echo lang('Please enter the recharge mobile number'); ?>" id="user_phone"></div><div class="user_info"><div><!-- <i class="fa fa-money" aria-hidden="true" style="color: #FFB805;font-size: 40px;margin: auto 0 auto auto;"></i> --><i class="fa fa-jpy" aria-hidden="true" style="color: #FFB805;font-size: 40px;margin: auto 0 auto auto;"></i></div><input type="number" placeholder="<?php echo lang('Please enter the recharge amount'); ?>" id="user_num"></div></div><p class="wrin wrin-description"><?php echo htmlentities($pay['description']); ?></p><input type="hidden" id="payid" value="<?php echo htmlentities($pay['id']); ?>"><div class="btn"><?php echo lang('confirm'); ?></div></div></section><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="https://cdn.jsdelivr.net/npm/dot@1.1.3/doT.min.js"type="text/javascript" ></script><script src="https://cdn.jsdelivr.net/npm/cleave.js@1.5.10/dist/cleave.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595490115"></script><script src="/public/js/common.min.js?v=1595490115"></script><script src="/public/js/city.js"></script><script src="/public/js/picker.min.js"></script><script>
var submit = true;

// 提交资料
$('.btn').click(function() {
    var real_name = $("#user_name").val();
    var tel = $("#user_phone").val();
    var num = $("#user_num").val();
    var payid = $("#payid").val();
    if (real_name == "") {
        QS_toast.show(`<?php echo lang('Please enter recharge name'); ?>`, true)
    } else if (tel == "") {
        QS_toast.show(`<?php echo lang('Please enter the recharge mobile number'); ?>`, true)
    } else if (num == "") {
        QS_toast.show(`<?php echo lang('Please enter the recharge amount'); ?>`, true)
    } else {
        var data = { real_name: real_name, tel: tel, num: num, payid: payid }
        if (submit) {
            var indexD = layer.open({
                type: 2,
                shadeClose: false,
                content: `<?php echo lang('submitting'); ?>`
            });
            sumit = false;
            $.ajax({
                url: urlPost("recharge/recharge"),
                type: "POST",
                dataType: "JSON",
                data: data,
                success: function(res) {
                    console.log(res)
                    if (res.code == 0) {
                        QS_toast.show(res.info, true);
                        layer.close(indexD);
                    } else {
                        submit = true;
                        QS_toast.show(res.info, true, 2000);
                        layer.close(indexD);
                    }
                    if(res.url){
                        var timer = setTimeout(function() {
                            window.location.href = res.url;
                        }, 1500)
                    }
                },
                error: function(err) { console.log(err) }
            })
        }
    }
})
</script></body></html>