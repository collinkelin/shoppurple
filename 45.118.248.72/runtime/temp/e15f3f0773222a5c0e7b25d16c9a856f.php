<?php /*a:2:{s:67:"/www/wwwroot/45.118.248.72/application/index/view/ctrl/deposit.html";i:1594214994;s:59:"/www/wwwroot/45.118.248.72/application/index/view/main.html";i:1593514237;}*/ ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="Robots" content="noindex,nofollow"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><title><?php echo sysconf('site_name'); ?></title><link rel="stylesheet" type="text/css" href="/public/css/style.min.css?v=1595179389"><link rel="stylesheet" type="text/css" href="/public/css/ui.min.css?v=1595179389"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><style>
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
            justify-content: flex-end;
            align-items: center;
        }

        .icon {
            width: 1.3rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .user_info i {
            color: #9A0B98;
            font-size: 36px;
        }

        .user_info>.input {
            margin-left: 1rem;
            width: 60%;
            border: none;
            outline: none;
        }

        .btn {
            width: 90%;
            height: 2rem;
            line-height: 2rem;
            text-align: center;
            color: white;
            margin: 3rem auto 0;
            border-radius: 5px;
            background: #8BC34A;
        }

        .input-radiu {
            width: 70%;
            border: 1px solid #b9adad;
            border-radius: 50px;
            margin: auto;
            height: 1.5rem;
        }

        .input-radiu input {
            border: none;
            outline: none;
            background: transparent;
            height: 100%;
            text-align: center;
            color: #777777;
        }

        .QS-toast {
            z-index: 19891016 !important;
        }

        .share {
            background-color: rgba(0, 0, 0, .7);
            position: fixed;
            top: 0;
            right: 0;
            width: 100%;
            height: 100vh;
            z-index: 1000;
            display: none;
        }

        #container {
            display: flex;
            width: 100vw;
            height: 100vh;
        }

        .confirm {
            margin: auto;
            background: white;
            width: 90%;
            border-radius: 5px;
            overflow: hidden;
        }

        .box {
            padding: 50px 30px;
            line-height: 22px;
            text-align: center;
        }

        .btn-cont {
            width: 100%;
            height: 50px;
            line-height: 50px;
            border-top: 1px solid #D0D0D0;
            background-color: #F2F2F2;
            display: flex;
        }

        .btn-cont>div {
            width: 50%;
            text-align: center;
            font-size: .7rem;
        }

        #on {
            color: #40AFFE;
            border-left: 1px solid #d0d0d0;
        }
        .wrin{
            padding:0 .3rem;
            color:red;
            width:90%;
            margin:.5rem auto;
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
    </style></head><body><header><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('Withdraw'); ?></span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><section><div class="container"><div class="user_box"><div class="user_info"><div><div class="icon"><i class="fa fa-user" aria-hidden="true"></i></div></div><input class="input" type="text" placeholder="<?php echo lang('Please enter account name'); ?>" id="name"></div><div class="user_info"><div><div class="icon"><i class="fa fa-phone" aria-hidden="true"></i></div></div><input class="input" type="number" placeholder="<?php echo lang('Please enter the account holders mobile number'); ?>" id="tel"></div><div class="user_info"><div><div class="icon"><i class="fa fa-money" aria-hidden="true"></i></div></div><input class="input" type="number" placeholder="<?php echo lang('Please enter the amount of withdrawal'); ?>" id="num"></div><div style="padding: 20px;font-size: 12px"><?php echo lang('Your available balance'); ?>: <?php echo htmlentities($user['balance']); ?></div><div class="user_box file_container"><div><div class=""><?php echo lang('Clearly upload the front photo of ID card'); ?></div></div><div class="file"><input type="file" name="" id="voucher"><img src="" alt="" class="voucher_img voucher"></div></div><div class="user_box file_container"><div><div class=""><?php echo lang('Upload the photo of the reverse side of the ID card'); ?></div></div><div class="file"><input type="file" name="" id="voucher2"><img src="" alt="" class="voucher_img voucher2"></div></div><p class="wrin wrin-description"></p></div><div class="share"><div id="container"><div class="confirm"><div class="box"><div class='input-radiu'><input placeholder="<?php echo lang('Please fill in the transaction password'); ?>" type='password' id='pwd2' class='int'></div></div><div class="btn-cont"><div id="off"><?php echo lang('cancel'); ?></div><div id="on" data-start="0"><?php echo lang('confirm'); ?></div></div></div></div></div><div class="btn"><?php echo lang('confirm'); ?></div></div></section><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="https://cdn.jsdelivr.net/npm/dot@1.1.3/doT.min.js"type="text/javascript" ></script><script src="https://cdn.jsdelivr.net/npm/cleave.js@1.5.10/dist/cleave.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595179389"></script><script src="/public/js/common.min.js?v=1595179389"></script><script src="/public/js/city.js"></script><script src="/public/js/picker.min.js"></script><script>
var bk_id = "",
    submit = true,
    num = 0;
var payid = '<?php echo htmlentities($id); ?>';
// 上传凭证
$('#voucher').change(function() {
    var file = $("#voucher").get(0).files[0];
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onloadend = function() {
        $(".voucher").attr("src", reader.result);
        pic = reader.result;
    }
})
// 上传凭证
$('#voucher2').change(function() {
    var file = $("#voucher2").get(0).files[0];
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onloadend = function() {
        $(".voucher2").attr("src", reader.result);
        pic = reader.result;
    }
})
$(function() {
    $.ajax({
        url: urlPost("ctrl/do_deposit"),
        type: "GET",
        dataType: "JSON",
        data: { pay_id: '<?php echo htmlentities($id); ?>' },
        success: function(res) {
            console.log(res)
            if (res.code == 0) {
                var data = res.data;
                $("#bank_name").val(data.bank_name)
                $("#branch_name").val(data.branch_name)
                $("#branch_number").val(data.branch_number)
                $("#card_number").val(data.card_number)
                $("#name").val(data.name)
                $("#tel").val(data.tel)
                $('.wrin-description').html(data.pay.description)
                bk_id = data.id;
            }else{
                QS_toast.show(res.info, true)
                if(res.url){
                    var timer = setTimeout(function() {
                        location.href = res.url;
                    }, 1500)
                }
            }
        },
        error: function(err) {
            console.log(err);
            submit = true
        }
    })
})

$("#pwd2").on('focus', function() {
    document.body.scrollIntoView(true);
})
$("#off").click(function() {
    $('.share').hide()
})

$('.btn').click(function() {
    num = $("#num").val();
    if (num == "") {
        QS_toast.show("<?php echo lang('Please enter the amount of withdrawal'); ?>", true)
    } else {
        //计算手续费
        $.ajax({
            url: urlPost("pay/check_rates"),
            type: "POST",
            dataType: "JSON",
            data: { amount: num, payid: payid },
            success: function(res) {
                console.log(res);
                if (res.code == 0) {
                    layer.open({
                        anim: 'up',
                        shadeClose: false,
                        content: res.info,
                        btn: [`<?php echo lang('confirm'); ?>`, `<?php echo lang('cancel'); ?>`],
                        yes: function(index){
                            layer.close(index);
                            $('.share').show();
                        }
                    });
                } else {
                    QS_toast.show(res.info, true);
                    $('#on').attr('data-start', 0);
                    if(res.url){
                        var timer = setTimeout(function() {
                            location.href = res.url;
                        }, 800)
                    }
                    layer.close(index2);
                }
            },
            error: function(err) {
                console.log(err)
                $('#on').attr('data-start', 0);
                $("#pwd2").val('');
            }
        })
    }
})

$("#on").click(function() {
    var pic = $('.voucher').attr('src');
    var pic2 = $('.voucher2').attr('src');
    if ($('#on').attr('data-start') == 1) {
        return false;
    }
    var index2 = layer.open({
        type: 2,
        shade: true,
        time: 10,
        shadeClose: false
    });

    if ($("#pwd2").val() == "") {
        QS_toast.show("<?php echo lang('Please fill in the transaction password'); ?>", true)
    }

    //验证交易密码
    $.ajax({
        url: urlPost("order/check_pwd2"),
        type: "POST",
        dataType: "JSON",
        data: { pwd2: $("#pwd2").val() },
        success: function(res) {
            console.log(res);
            $("#pwd2").val('');
            if (res.code == 0) {
                // 发起提现请求
                $.ajax({
                    url: urlPost("ctrl/do_deposit"),
                    type: "POST",
                    dataType: "JSON",
                    data: { num: num, bk_id: bk_id, pay_id: payid, pic: pic, pic2: pic2 },
                    success: function(res) {
                        console.log(res)
                        if (res.code == 0) {
                            $('.share').hide();
                            QS_toast.show(`<?php echo lang('Withdrawal application submitted'); ?>`, true)
                            var timer = setTimeout(function() {
                                history.back(-1)
                            }, 1500)
                        } else {
                            QS_toast.show(res.info, true)
                            if(res.url){
                                var timer = setTimeout(function() {
                                    location.href = res.url;
                                }, 800)
                            }
                            submit = true;
                        }

                        $('#on').attr('data-start', 0);
                        layer.close(index2);
                    },
                    error: function(err) {
                        console.log(err);
                        submit = true;
                        $('#on').attr('data-start', 0);
                        layer.close(index2);
                    }
                })
            } else {
                QS_toast.show(res.info, true);
                $('#on').attr('data-start', 0);
                if(res.url){
                    var timer = setTimeout(function() {
                        location.href = res.url;
                    }, 800)
                }
                layer.close(index2);
            }
        },
        error: function(err) {
            console.log(err)
            $('#on').attr('data-start', 0);
            layer.close(index2);
            $("#pwd2").val('');
        }
    })
})
</script></body></html>