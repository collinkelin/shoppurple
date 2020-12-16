<?php /*a:2:{s:79:"/www/wwwroot/45.118.248.72/application/index/view/recharge/recharge_before.html";i:1590474295;s:59:"/www/wwwroot/45.118.248.72/application/index/view/main.html";i:1593514237;}*/ ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="Robots" content="noindex,nofollow"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><title><?php echo sysconf('site_name'); ?></title><link rel="stylesheet" type="text/css" href="/public/css/style.min.css?v=1595230816"><link rel="stylesheet" type="text/css" href="/public/css/ui.min.css?v=1595230816"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><style>
        .form {
            /*height: 80px;*/
            /*line-height: 80px;*/
            color: rgb(240, 98, 96);
            display: flex;
            justify-content: space-between;
            flex-direction: row;
            padding: 0 .5rem;
            border-bottom: 1px solid #eeeeee;
            min-height: 60px;
            padding-top: 10px;
        }

        .form>input {
            border: none;
            outline: none;
            margin-right: auto;
            color: rgb(240, 98, 96);
            margin-left: 1rem;
            width:70%;
            font-size: 0.8rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .form_title {
            font-size: .5rem;
            width: 10%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form_title img {
            height: auto;
        }

        .form_title i {
            font-size: 30px;
        }

        .copy_btn {
            color: #60aef0;
            width: 10%;
            text-align: center;
            height: 2rem;
            line-height: 2rem;
        }

        .user_info>div:first-child {
            width: 20%;
            height: 100%;
            display: flex;
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
    </style></head><body><header><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('Please select the recharge amount'); ?></span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><section><div class="container"><div class="form_box"><?php foreach($list as $key=>$vo): ?><a href="<?php echo htmlentities($vo['url']); ?>?payid=<?php echo htmlentities($vo['id']); ?>" class="form"><div class="form_title"><?php if($vo['ico_type'] == '1'): ?><?php echo $vo['ico']; else: ?><img src="<?php echo htmlentities($vo['ico']); ?>" alt="" style="width: 40px; height:40px"><?php endif; ?></div><input type="text" id="bank_name" value="<?php echo htmlentities($vo['name']); ?>" readonly><div class="copy_btn">></div></a><?php endforeach; ?></div><p class="wrin"><?php echo lang('card Note'); ?></p></div></section><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="https://cdn.jsdelivr.net/npm/dot@1.1.3/doT.min.js"type="text/javascript" ></script><script src="https://cdn.jsdelivr.net/npm/cleave.js@1.5.10/dist/cleave.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595230816"></script><script src="/public/js/common.min.js?v=1595230816"></script><script src="/public/js/city.js"></script><script src="/public/js/picker.min.js"></script><script>
var submit = true;
// 复制信息
$('.copy_btn').click(function() {
    var val = $(this).siblings('input').attr('id');
    copyNum(val)
})

// 上传凭证
$('#voucher').change(function() {
    var file = $("#voucher").get(0).files[0];
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onloadend = function() {
        $(".voucher_img").attr("src", reader.result);
        pic = reader.result;
    }
})

function copyNum(val) {
    var NumClip = document.getElementById(val);
    var NValue = NumClip.value;
    var valueLength = NValue.length;
    selectText(NumClip, 0, valueLength);
    if (document.execCommand('copy', false, null)) {
        document.execCommand('copy', false, null) // 执行浏览器复制命令
        QS_toast.show(`<?php echo lang('Copy successfully'); ?>`, true)
    } else {
        var copyDOM = document.getElementById(val); //要复制文字的节点  
        var range = document.createRange();
        // 选中需要复制的节点  
        range.selectNode(copyDOM);
        // 执行选中元素  
        window.getSelection().addRange(range);
        // 执行 copy 操作  
        var successful = document.execCommand('copy');
        try {
            var msg = successful ? 'successful' : 'unsuccessful';

            console.log('copy is' + msg);
        } catch (err) {
            console.log('Oops, unable to copy');
        }
        // 移除选中的元素  
        window.getSelection().removeAllRanges();
        QS_toast.show(`<?php echo lang('Copy successfully'); ?>`, true)
    }
}

function selectText(textbox, startIndex, stopIndex) {
    textbox.setSelectionRange(startIndex, stopIndex);
    textbox.focus();
}
</script></body></html>