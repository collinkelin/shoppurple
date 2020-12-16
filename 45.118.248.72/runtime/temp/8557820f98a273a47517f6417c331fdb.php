<?php /*a:2:{s:67:"/www/wwwroot/45.118.248.72/application/index/view/ctrl/my_data.html";i:1595216683;s:59:"/www/wwwroot/45.118.248.72/application/index/view/main.html";i:1593514237;}*/ ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="Robots" content="noindex,nofollow"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><title><?php echo sysconf('site_name'); ?></title><link rel="stylesheet" type="text/css" href="/public/css/style.min.css?v=1595234872"><link rel="stylesheet" type="text/css" href="/public/css/ui.min.css?v=1595234872"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><style>
.data_item {
    width: 90%;
    margin: auto;
    display: flex;
    height: 2rem;
    line-height: 2rem;
    position: relative;
}

.data_item>p:last-child {
    width: 80%;
    text-align: center;
}

.head {
    width: 1.3rem;
    height: 1.3rem;
    border-radius: 50%;
    overflow: hidden;
    margin: auto 1rem auto auto;
}

.right {
    width: 1.2rem;
    height: 1.7rem;
    margin: auto 0 auto 0;
}
.right i {
    font-size: 1rem;
}

.form_container {
    border-top: 1px solid #f3f3f3;
    margin-top: 1rem;
}

.form {
    width: 90%;
    margin: .5rem auto;
    display: flex;
    height: 1.5rem;
}

.form .form_title {
    line-height: 1.5rem;
    width: 20%;
}

.input_box {
    height: 100%;
    border: 1px solid #e2dcdc;
    border-radius: 3px;
    width: 80%;
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

.form1 {
    width: 90%;
    margin: .5rem auto;
}

.form1 .form_title {
    line-height: 1.5rem;
}

.input_box1 {
    border: 1px solid #e2dcdc;
    border-radius: 3px;
}

.input_box1 input {
    border: none;
    outline: none;
    width: 100%;
    text-indent: 10px;
}

.input_box1 input::placeholder {
    color: #a7a7a7;
}

.btn {
    width: 90%;
    margin: 2rem auto 0;
    height: 2rem;
    text-align: center;
    line-height: 2rem;
    background-image: linear-gradient(to top,#E60816,#F8838B);
    border-radius: 6px;
    color: white;
    font-size: .8rem;
}

#file,
#file_wx,
#file_zfb {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    opacity: 0;
}
</style></head><body><header><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('personal information'); ?></span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><section><div class="container" style="padding-top: 2.5rem;"><div class="data_item"><p class="form_title"><?php echo lang('account number'); ?>:</p><p class="user">-</p></div><div class="data_item" id="head"><div class="form_title"><?php echo lang('Avatar'); ?>:</div><div class="head"><img src="" id="headImg" alt=""></div><input type="file" name="" id="file"><div class="right"><i class="fa fa-chevron-right" aria-hidden="true"></i></div></div><div class="form_container"><div class="form1"><div class="form_title"><?php echo lang('nickname'); ?>:</div><div class="input_box1"><input type="text" placeholder="<?php echo lang('Please enter a nickname'); ?>" id="name"></div></div><div class="form1"><p class="form_title"><?php echo lang('Signature'); ?>:</p><div class="input_box1"><input type="text" placeholder="<?php echo lang('Please enter a personalized signature'); ?>" id="signature"></div></div></div><!-- <h4 style="border-bottom: 1px solid #ccc;padding-left: 10px;margin-top: 30px"><?php echo lang('Payment code'); ?></h4><div class="data_item" id="head_wx" style="height: auto;"><p class="form_title"><?php echo lang('WeChat'); ?>:</p><div class="head" style="height: 100px;width:100px"><img src="" id="headImg_wx" alt=""></div><input type="file" name="" id="file_wx"><div class="right"></div></div><hr><div class="data_item" id="head_zfb" style="height: auto;"><p class="form_title"><?php echo lang('Alipay'); ?>:</p><div class="head" style="height: 100px;width:100px"><img src="" id="headImg_zfb" alt=""></div><input type="file" name="" id="file_zfb"><div class="right"></div></div> --><div class="btn"><?php echo lang('confirm'); ?></div></div></section><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="https://cdn.jsdelivr.net/npm/dot@1.1.3/doT.min.js"type="text/javascript" ></script><script src="https://cdn.jsdelivr.net/npm/cleave.js@1.5.10/dist/cleave.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595234872"></script><script src="/public/js/common.min.js?v=1595234872"></script><script src="/public/js/city.js"></script><script src="/public/js/picker.min.js"></script><script>
var src = "";
var src_wx = "";
var src_zfb = "";
$(function() {
    // 默认资料
    $.ajax({
        url: urlPost("my/do_my_info"),
        type: "GET",
        dataType: "JSON",
        data: {},
        success: function(res) {
            console.log(res)
            if (res.code == 0) {
                $('.user').html(res.data.username)
                $("#headImg").attr('src', res.data.headpic)
                $("#headImg_wx").attr('src', res.data.wx_ewm)
                $("#headImg_zfb").attr('src', res.data.zfb_ewm)
                $("#name").val(res.data.nickname)
                $("#signature").val(res.data.sign)
            }
        },
        error: function(err) { console.log(err) }
    })

})

// 上传照片
$('#file').change(function() {
    var file = $("#file").get(0).files[0];
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onloadend = function() {
        $("#headImg").attr("src", reader.result);
        src = reader.result;
    }
})

// 上传微信二维码照片
$('#file_wx').change(function() {
    var file = $("#file_wx").get(0).files[0];
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onloadend = function() {
        $("#headImg_wx").attr("src", reader.result);
        src_wx = reader.result;
    }
})
// 上传微信二维码照片
$('#file_zfb').change(function() {
    var file = $("#file_zfb").get(0).files[0];
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onloadend = function() {
        $("#headImg_zfb").attr("src", reader.result);
        src_zfb = reader.result;
    }
}); //
var submit = true;
// 修改个人资料
$(".btn").click(function() {
    var data = {
        nickname: $("#name").val(),
        sign: $("#signature").val(),
        headpic: src,
        wx_ewm: src_wx,
        zfb_ewm: src_zfb
    }
    if (submit) {
        submit = false;
        $.ajax({
            url: urlPost("my/do_my_info"),
            type: "POST",
            dataType: "JSON",
            data: data,
            success: function(res) {
                console.log(res)
                if (res.code == 0) {
                    QS_toast.show(res.info, true)
                    var timer = setTimeout(function() {
                        history.back(-1)
                    }, 1800)
                } else {
                    submit = true;
                }
            },
            error: function(err) { console.log(err) }
        })
    }

})
</script></body></html>