<?php /*a:2:{s:63:"/www/wwwroot/45.118.248.72/application/index/view/ctrl/set.html";i:1591815856;s:59:"/www/wwwroot/45.118.248.72/application/index/view/main.html";i:1593514237;}*/ ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="Robots" content="noindex,nofollow"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><title><?php echo sysconf('site_name'); ?></title><link rel="stylesheet" type="text/css" href="/public/css/style.min.css?v=1595239175"><link rel="stylesheet" type="text/css" href="/public/css/ui.min.css?v=1595239175"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><style>
    .list li {
        display: flex;
        height: 2.1rem;
        line-height: 2.1rem;
        border-top: 1px solid #eaeaea;
    }

    .list li div {
        margin: auto 0;
    }

    .list li .icon {
        width: 1.5rem;
        height: 1.5rem;
        margin: auto 1rem;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .icon>i {
        color: #fff;
        font-size: 32px;
    }

    .list li .right {
        width: 1.2rem;
        height: 1.7rem;
        background-size: cover;
        background-repeat: no-repeat;
        background-image: url(/public/img/right.png);
        margin: auto 1rem auto auto;
    }

    </style></head><body><header><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('Settings'); ?></span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><section><div class="container"><div class="co"></div><ul class="list"><li onclick="location.href=`<?php echo url('ctrl/edit_pwd'); ?>`"><div class="icon" style="background: rgb(133, 179, 107);"><i class="fa fa-lock" aria-hidden="true"></i></div><div><?php echo lang('change Password'); ?></div><div class="right"></div></li><li onclick="location.href=`<?php echo url('ctrl/edit_deposit_pwd'); ?>`"><div class="icon" style="background:rgb(147, 92, 175)"><i class="fa fa-lock" aria-hidden="true"></i></div><div><?php echo lang('transaction password'); ?></div><div class="right"></div></li><!-- <li onclick="location.href=`<?php echo url('my/bind_tel'); ?>`"><div class="icon" style="background: rgb(236, 90, 90);"><i class="fa fa-mobile" aria-hidden="true"></i></div><div><?php echo lang('Mobile phone binding'); ?></div><div class="right"></div></li><li class="clean"><div class="icon" style="background: rgb(45, 190, 195);"><i class="fa fa-trash-o" aria-hidden="true"></i></div><div><?php echo lang('clear cache'); ?></div><div class="right"></div></li> --></ul></div></section><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="https://cdn.jsdelivr.net/npm/dot@1.1.3/doT.min.js"type="text/javascript" ></script><script src="https://cdn.jsdelivr.net/npm/cleave.js@1.5.10/dist/cleave.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595239175"></script><script src="/public/js/common.min.js?v=1595239175"></script><script src="/public/js/city.js"></script><script src="/public/js/picker.min.js"></script><script>
$('.clean').click(function() {
    layer.open({
        type: 2,
        content: `<?php echo lang('Cleaning up'); ?>`,
        time: 2,
        shadeClose: false,
    });
    var timer = setTimeout(function() {
        QS_toast.show(`<?php echo lang('Cache cleared successfully'); ?>`, true)
    }, 2000);
})
</script></body></html>