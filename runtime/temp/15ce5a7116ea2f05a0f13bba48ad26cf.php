<?php /*a:2:{s:79:"/www/wwwroot/www.oabuhps.cn/application/index/view/recharge/recharge_admin.html";i:1591935099;s:60:"/www/wwwroot/www.oabuhps.cn/application/index/view/main.html";i:1593514237;}*/ ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="Robots" content="noindex,nofollow"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><title><?php echo sysconf('site_name'); ?></title><link rel="stylesheet" type="text/css" href="/public/css/style.min.css?v=1595490258"><link rel="stylesheet" type="text/css" href="/public/css/ui.min.css?v=1595490258"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><style>
    .orders-item {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        border-bottom: .1rem solid rgb(248, 242, 242);
        padding: .3rem .5rem;
        font-size: .7rem;
        height: 3rem;
    }

    .orders-item .botton {
        position: relative;
        top:50%;
        transform:translateY(-50%);
        padding: 5px;
    }

    .money {
        text-align: right;
        color: #8BC34A
    }

    .top>p:first-child {
        color: #777777;
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
</style></head><body><header><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('Recharge Record'); ?></span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><section><div class="container"><ul class="list"></ul></div></section><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="https://cdn.jsdelivr.net/npm/dot@1.1.3/doT.min.js"type="text/javascript" ></script><script src="https://cdn.jsdelivr.net/npm/cleave.js@1.5.10/dist/cleave.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595490258"></script><script src="/public/js/common.min.js?v=1595490258"></script><script src="/public/js/city.js"></script><script src="/public/js/picker.min.js"></script><script type="text/html" id="list-tpl">
    {{ for(var key in it.data) { }}
<li class="orders-item"><div class="top"><p>{{= it.data[key].id }}</p><p style="{{= getColor(it.data[key]) }}">{{= getStatus(it.data[key]) }}</p></div><div class="top">
        {{= setButton(it.data[key]) }}
    </div><div class="top"><p>{{= timeTransform(it.data[key].addtime) }}</p><p class="money">{{= milliFormat(it.data[key].num) }}</p></div></li>
{{  } }}
</script><script>
function setButton(val) {
    if(val.is_vip == 1){
        return '';
    }
    if(val.status == 1 && !val.pic){
        return `<button data-id="${val.id}" class="botton" type=""><?php echo lang('upload certificate'); ?></button>`;
    }else if((val.status == 3 && val.description) || (val.status == 1 && val.pic)){
        return `<button data-id="${val.id}" class="botton" type=""><?php echo lang('see details'); ?></button>`;
    }else{
        return '';
    }
}

function getColor(val) {
    switch (val.status) {
        case 2:
            return 'color: #059fce';
            break;
        case 3:
            return 'color: #fd0a0a';
            break;
        default:
            return 'color: #FFB800';
            break;
    }
}

function getStatus(val) {
    switch (val.status) {
        case 2:
            return `<?php echo lang('Successful recharge'); ?>`
            break;
        case 3:
            return `<?php echo lang('Recharge failed'); ?>`
            break;
        default:
            return `<?php echo lang('Pending'); ?>`
            break;
    }
}
var page = 1;
$(function() {
    list(page)
})

$(window).scroll(function() {
    var scroH = $(document).scrollTop();
    var viewH = $(window).height();
    var contentH = $(document).height();
    if (scroH > 100) {

    }
    if (contentH - (scroH + viewH) <= 10) {
        page++;
        list(page);
    }
    if (contentH = (scroH + viewH)) {

    }
});

function list(page) {
    $.ajax({
        url: urlPost("recharge/get_recharge_order"),
        type: "POST",
        dataType: "JSON",
        data: { page, num: 15 },
        success: function(res) {
            if (res.code == 0) {
                var list = res.data;
                if (page != 1 && list.length == 0) {
                    QS_toast.show(`<?php echo lang('No more data'); ?>..`, true)
                }
                if (page == 1 && list.length == 0) {
                    $(".list").append(`<li class="no-data"><?php echo lang('No data'); ?>...</li>`)
                }
                var tpl1 = doT.template($("#list-tpl").html());
                $('.list').append(tpl1(res));
            }
        },
        error: function(err) { console.log(err) }
    })
}
$('.list').on('click', '.botton', function(index) {
    var id = $(this).data('id');
    $.ajax({
        url: urlPost("recharge/info"),
        type: "POST",
        dataType: "JSON",
        data: { id: id },
        success: function(res) {
            if (res.code == 0) {
                // QS_toast.show(res.info, true);
                var timer = setTimeout(function() {
                    window.location.href = res.url;
                }, 100)
            } else {
                submit = true;
                QS_toast.show(res.info, true, 2000);
            }
        },
        error: function(err) { console.log(err) }
    })
})
</script></body></html>