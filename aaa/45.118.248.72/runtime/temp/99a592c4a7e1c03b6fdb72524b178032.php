<?php /*a:2:{s:68:"/www/wwwroot/45.118.248.72/application/index/view/ctrl/team_bgk.html";i:1590340290;s:59:"/www/wwwroot/45.118.248.72/application/index/view/main.html";i:1593514237;}*/ ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="Robots" content="noindex,nofollow"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><title><?php echo sysconf('site_name'); ?></title><link rel="stylesheet" type="text/css" href="/public/css/style.min.css?v=1595228335"><link rel="stylesheet" type="text/css" href="/public/css/ui.min.css?v=1595228335"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><style>
nav {
    display: flex;
    border-bottom: 1px solid #e5e5e5;
}

nav>p {
    width: 33%;
    text-align: center;
    height: 2rem;
    line-height: 2rem;
}

.active {
    color: #ffc000;
    position: relative;
}

.active::after {
    content: "";
    position: absolute;
    width: 60%;
    height: 1px;
    background: #ffc000;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
}

.today {
    height: 2.5rem;
    border-bottom: 1px solid #e5e5e5;
    line-height: 2.5rem;
}

.head {
    width: 2rem;
    height: 2rem;
    overflow: hidden;
    border-radius: 50%;
    margin: auto .3rem auto .3rem;
    background: skyblue;
}

img {
    width: 100%;
    height: 100%;
}

.today p {
    text-align: center;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}

.today p span {
    color: red;
}

.items {
    display: flex;
    border-bottom: 1px solid #e5e5e5;
}

.items span {
    width: 100%;
    text-align: center;
}

.user {
    width: 23%;
    color: red;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}

.content ul {
    display: none;
}
</style></head><body><header><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('Team commission'); ?></span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><section><div class="container"><nav><p class="active"><?php echo lang('Generation commission'); ?></p><p><?php echo lang('Second generation commission'); ?></p><p><?php echo lang('Three Generation Commission'); ?></p></nav><div class="content"><ul class="onelist" style="display: block;"></ul><ul class="twolist"></ul><ul class="threelist"></ul></div></div></section><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="https://cdn.jsdelivr.net/npm/dot@1.1.3/doT.min.js"type="text/javascript" ></script><script src="https://cdn.jsdelivr.net/npm/cleave.js@1.5.10/dist/cleave.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595228335"></script><script src="/public/js/common.min.js?v=1595228335"></script><script src="/public/js/city.js"></script><script src="/public/js/picker.min.js"></script><script>
$(function() {
    getList(1, 1)
})

$('nav>p').click(function() {
    var _ind = $(this).index();
    $(this).addClass('active').siblings().removeClass('active');
    $('.content>ul').eq(_ind).show().siblings().hide();
    getList(_ind + 1, 1)
})

function getList(lv, page) {
    $.ajax({
        url: urlPost("my/get_team_reward"),
        type: "POST",
        dataType: "JSON",
        data: { lv: lv, page: page },
        success: function(res) {
            console.log(res)
            var num = lv == 1 ? 'one' : (lv == 2 ? 'two' : 'three');
            $(`.${num}list`).html('');
            if (res.code == 0) {
                $(`.${num}list`).append(`
                        <li class="today"><p><?php echo lang('Commission today'); ?>:<span>${milliFormat(res.data.num)}</span></p></li>
                    `)
                $(`.${num}list`).append(`
                        <li class="items"><span><?php echo lang('Subordinate users'); ?></span><span><?php echo lang('Revenue share'); ?></span></li>
                    `)
                for (var p in res.data.list) {
                    $(`.${num}list`).append(`
                        <li class="items"><span>${res.data.list[p].nickname}</span><span>${milliFormat(res.data.list[p].num)}</span></li>
                    `)
                }
            } else {
                $(`.${num}list`).append(`
                        <li class="today"><p><?php echo lang('Commission today'); ?>:<span>0</span></p></li>
                    `)
            }
        },
        error: function(err) {
            console.log(err)
        }
    })
}
</script></body></html>