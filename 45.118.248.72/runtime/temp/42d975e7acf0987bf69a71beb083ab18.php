<?php /*a:1:{s:67:"/www/wwwroot/45.118.248.72/application/index/view/my/edit_bank.html";i:1595216744;}*/ ?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><meta http-equiv="X-UA-Compatible" content="ie=edge"><title><?php echo lang('Edit bank card'); ?></title><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><link rel="stylesheet" href="/public/css/style.min.css?v=1595216750"><script src="/public/js/ui.js?v=1595216750"></script><link rel="stylesheet" href="/public/css/ui.min.css?v=1595216750"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><script src="/public/js/common.min.js?v=1595216750"></script><style>
        .form_container {
            border-top: 1px solid #f3f3f3;
            margin-top: 1rem;
        }

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

        .swith {
            position: relative;
            height: 2rem;
            display: flex;
        }

        .swith>label {
            float: right;
            margin: auto 3.5rem auto auto;
            font-size: .5rem;
            color: #777777;
        }

        .swith>label::before {
            content: "";
            position: absolute;
            height: 1rem;
            width: 2rem;
            border-radius: 50px;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ddd;
            right: 1rem;
            top: 0;
            bottom: 0;
            margin: auto;
            transition: background-color .0s linear .2s;
        }

        .swith>label::after {
            content: "";
            height: 1rem;
            width: 1rem;
            border-radius: 50%;
            background: white;
            background-clip: padding-box;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .4);
            position: absolute;
            right: 2rem;
            top: 0;
            bottom: 0;
            margin: auto;
            transition: right .2s linear;
        }

        .swith>input {
            display: none;
        }

        .swith>input:checked+label::after {
            right: 1rem;
        }

        .swith>input:checked+label::before {
            background: #E60816;
        }

        .btn {
            margin: 2rem auto;
            width: 90%;
            height: 2rem;
            line-height: 2rem;
            border-radius: 7px;
            background-image: linear-gradient(to top,#E60816,#F8838B);
            text-align: center;
            color: white;
        }
    </style></head><body><header><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('Edit bank card'); ?></span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><section><div class="container"><div class="form"><p class="form_title"><?php echo lang('Bank account'); ?>:</p><div class="input_box"><input type="text" placeholder="<?php echo lang('Please enter the account bank'); ?>" id="bank_name"></div></div><div class="form"><p class="form_title"><?php echo lang('Branch name'); ?>:</p><div class="input_box"><input type="text" placeholder="<?php echo lang('Please enter a branch name'); ?>" id="branch_name"></div></div><div class="form"><p class="form_title"><?php echo lang('Branch number'); ?>:</p><div class="input_box"><input type="text" placeholder="<?php echo lang('Please enter a branch number'); ?>" id="branch_number"></div></div><div class="form"><p class="form_title"><?php echo lang('Bank card number'); ?>:</p><div class="input_box"><input type="text" placeholder="<?php echo lang('Please enter your bank card number'); ?>" id="card_number"></div></div><div class="form"><p class="form_title"><?php echo lang('Account holder'); ?>:</p><div class="input_box"><input type="text" placeholder="<?php echo lang('Please enter account name'); ?>" id="name"></div></div><div class="form"><p class="form_title"><?php echo lang('cellphone number'); ?>:</p><div class="input_box"><input type="text" placeholder="<?php echo lang('Please enter the account holders mobile number'); ?>" id="tel"></div></div><div class="swith"><input type="checkbox" name="" id="swith"><label for="swith"><?php echo lang('set as Default'); ?></label></div><div class="btn"><?php echo lang('confirm'); ?></div></div></section></body><script>
var submit = true,
    id = sessionStorage.getItem('bankId');
$(function() {
    $.ajax({
        url: urlPost("ctrl/do_bankinfo"),
        type: "GET",
        dataType: "JSON",
        data: { id: id },
        success: function(res) {
            console.log(res)
            if (res.code == 0) {
                var data = res.data[0];
                $("#bank_name").val(data.bank_name)
                $("#branch_name").val(data.branch_name)
                $("#branch_number").val(data.branch_number)
                $("#card_number").val(data.card_number)
                $("#name_e").val(data.name_e)
                $("#name").val(data.name)
                $("#tel").val(data.tel)
                if (data.status == 1) {
                    $('#swith').attr('checked', true)
                }
            }
        },
        error: function(err) { console.log(err);
            submit = true }
    })
})

$('.btn').click(function() {
    var bank_name = $('#bank_name').val(),
        branch_name = $('#branch_name').val(),
        branch_number = $('#branch_number').val(),
        card_number = $('#card_number').val(),
        name_e = $('#name_e').val(),
        name = $('#name').val(),
        tel = $('#tel').val(),
        swith = $("#swith").is(":checked") ? 1 : 0,
        token = "<?php echo htmlentities(app('request')->token()); ?>";
    if (bank_name == "") {
        QS_toast.show(`<?php echo lang('Please enter the account bank'); ?>`, true)
    } else if (branch_name == "") {
        QS_toast.show(`<?php echo lang('Please enter a branch name'); ?>`, true)
    } else if (branch_number == "") {
        QS_toast.show(`<?php echo lang('Please enter a branch number'); ?>`, true)
    } else if (card_number == "") {
        QS_toast.show(`<?php echo lang('Please enter your bank card number'); ?>`, true)
    } else if (name == "") {
        QS_toast.show(`<?php echo lang('Please enter account name'); ?>`, true)
    } else if (tel == "") {
        QS_toast.show(`<?php echo lang('Please enter the account holders mobile number'); ?>`, true)
    } else {
        if (submit) {
            submit = false;
            $.ajax({
                url: urlPost("ctrl/do_bankinfo"),
                type: "POST",
                dataType: "JSON",
                data: {
                    bkid: id,
                    bank_name: bank_name,
                    branch_name: branch_name,
                    branch_number: branch_number,
                    card_number: card_number,
                    name_e: name_e,
                    name: name,
                    tel: tel,
                    default: swith,
                    token: token,
                },
                success: function(res) {
                    console.log(res)
                    if (res.code == 0) {
                        QS_toast.show(`<?php echo lang('Successfully modified'); ?>`, true);
                        var timer = setTimeout(function() {
                            history.back(-1)
                        }, 1500)
                    } else {
                        QS_toast.show(res.info, true);
                    }
                },
                error: function(err) { console.log(err);
                    submit = true }
            })
        }
    }
})
</script></html>