<?php /*a:2:{s:73:"/www/wwwroot/45.118.248.72/application/index/view/ctrl/deposit_admin.html";i:1592190850;s:59:"/www/wwwroot/45.118.248.72/application/index/view/main.html";i:1593514237;}*/ ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="Robots" content="noindex,nofollow"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><title><?php echo sysconf('site_name'); ?></title><link rel="stylesheet" type="text/css" href="/public/css/style.min.css?v=1595234833"><link rel="stylesheet" type="text/css" href="/public/css/ui.min.css?v=1595234833"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><style>
    .list li {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    border-bottom: .1rem solid rgb(248, 242, 242);
    padding: .3rem 1rem;
    font-size: .7rem;
}

.list li .items {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}

.money div {
    display: block;
    flex-direction: column;
    width: 100%;
    color: #8BC34A
}

.add {
    position: absolute;
    width: 2rem;
    line-height: 2rem;
    top: 0;
    bottom: 0;
    right: .3rem;
    margin: auto;
    background-size: 80%;
    background-repeat: no-repeat;
}
</style></head><body><header><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('Withdraw record'); ?></span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><section><div class="container"><ul class="list"></ul></div></section><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="https://cdn.jsdelivr.net/npm/dot@1.1.3/doT.min.js"type="text/javascript" ></script><script src="https://cdn.jsdelivr.net/npm/cleave.js@1.5.10/dist/cleave.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595234833"></script><script src="/public/js/common.min.js?v=1595234833"></script><script src="/public/js/city.js"></script><script src="/public/js/picker.min.js"></script><script type="text/html" id="list-tpl">
{{ for(var key in it.data) { }}
<li id="{{= it.data[key].id }}"><div class="items"><p><?php echo lang('Order Id'); ?>:{{= it.data[key].id }}</p></div><div class="items"><p>{{= timeTransform(it.data[key].addtime) }}</p><p style="{{= getColor(it.data[key]) }}">{{= getStatus(it.data[key]) }}</p></div><div class="items"><div class="money"><div><?php echo lang('Withdrawal amount'); ?></div><div>{{= milliFormat(it.data[key].num) }}</div></div><div class="money"><div><?php echo lang('Actually arrived'); ?></div><div>{{= milliFormat(it.data[key].arrival) }}</div></div><div class="money"><div><?php echo lang('Handling fee'); ?></div><div>{{= milliFormat(it.data[key].handling_fee) }}</div></div></div></li>
{{  } }}
</script><script>
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
            return `<?php echo lang('Successful withdrawal'); ?>`
            break;
        case 3:
            return `<?php echo lang('Withdrawal failed'); ?>`
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

// $(window).scroll(function() {
//     var scroH = $(document).scrollTop();
//     var viewH = $(window).height();
//     var contentH = $(document).height();
//     if (scroH > 100) {

//     }
//     if (contentH - (scroH + viewH) <= 10) {
//         page++;
//         list(page);
//     }
//     if (contentH = (scroH + viewH)) {

//     }
// });

function list(page) {
    $.ajax({
        url: urlPost("ctrl/get_deposit"),
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
                // let list = res.data;
                // if (page != 1 && list.length == 0) {
                //     QS_toast.show(`<?php echo lang('No more data'); ?>...`, true)
                // }
                // var tpl1 = doT.template($("#list-tpl").html());
                // $('.list').html(tpl1(res));
            }
        },
        error: function(err) { console.log(err) }
    })
}
</script></body></html>