<?php /*a:1:{s:72:"/www/wwwroot/45.118.248.72/application/index/view/ctrl/receive_site.html";i:1586689610;}*/ ?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><title><?php echo lang('Shipping address'); ?></title><link rel="stylesheet" href="/public/css/style.min.css?v=1595228339"><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595228339"></script><link rel="stylesheet" href="/public/css/ui.min.css?v=1595228339"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="/public/js/common.min.js?v=1595228339"></script><style>
    body {
        background: rgb(248, 242, 242);
    }

    .site_list li {
        display: flex;
        justify-content: space-between;
        flex-direction: row;
        height: 3rem;
        padding: 0 .5rem;
        margin-bottom: .1rem;
        background: white;
    }

    .select {
        position: relative;
        color: rgb(239, 99, 98);
        padding-left: .6rem;
        margin: auto 0 auto .3rem;
        width: 3rem;
        font-size: .5rem;
    }

    .select::before {
        content: "";
        width: .4rem;
        height: .4rem;
        border-radius: 50%;
        border: 1px solid rgb(239, 99, 98);
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        margin: auto;
    }

    .default .select::before {
        background: rgb(239, 99, 98);
    }

    .site_info {
        width: 63%;
        margin: auto;
        height: 70%;
        display: flex;
        justify-content: space-between;
        flex-direction: column;
    }

    .site_info>p {
        display: flex;
    }

    .site_info>p:first-child>span:last-child {
        margin-left: 1.5rem;
    }

    .site_info>P:last-child {
        color: #888585;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .btn_container {
        margin: auto 0;
        color: #888585;
        font-size: .5rem;
        height: 70%;
        display: flex;
        justify-content: space-between;
        flex-direction: column;
    }

    .btn_container>div {
        width: 2rem;
        position: relative;
        padding-left: .7rem;

    }

    .btn_container>div::after {
        content: "";
        width: .7rem;
        height: .7rem;
        position: absolute;
        left: 0;
        background-size: 90%;
        background-repeat: no-repeat;
    }

    .compile::after {
        background-image: url(/public/img/compile.png);
    }

    .remove::after {
        background-image: url(/public/img/remove.png);
        background-size: 76% !important;
    }

    .add {
        position: absolute;
        width: 1.5rem;
        height: 1.2rem;
        top: 0;
        bottom: 0;
        right: .3rem;
        margin: auto;
        background-image: url(/public/img/add.png);
        background-size: 80%;
        background-repeat: no-repeat;
    }

    .no-data {
        height: 2rem;
        background: white !important;
    }

    .no-data>span {
        margin: auto;
        color: #777777;
    }
    </style></head><body><header><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('Shipping address'); ?></span><a class="back" href="./add_site.html"><i class="fa fa-plus" aria-hidden="true"></i></a></header><section><div class="container"><ul class="site_list"></ul></div></section></body><script>
$(function() {
    // 获取地址列表数据
    $.ajax({
        url: urlPost("my/get_address"),
        type: "POST",
        dataType: "JSON",
        data: {},
        success: function(res) {
            var list = res.data || [];
            if (list.length == 0) {
                console.log('ok')
                $(".site_list").append(`<li class="no-data"><span><?php echo lang('No harvest address has been added'); ?></span></li>`)
            }
            if (res.code == 0) {
                list.map(function(val) {
                    if (val.is_default == 1) {
                        //默认
                        $(".site_list").prepend(`
                            <li id="${val.id}" class="default"><div class="select"><span class="txt"><?php echo lang('Default'); ?></span></div><div class="site_info"><p><span>${val.name}</span><span>${val.tel}</span></p><p>${val.area ? val.area + " " + val.address : val.address}</p></div><div class="btn_container"><div class="compile"><?php echo lang('Edit'); ?></div><div class="remove"><?php echo lang('Delete'); ?></div></div></li>
                            `)
                    } else {
                        $(".site_list").append(`
                            <li id="${val.id}" ><div class="select"><span class="txt"><?php echo lang('set as Default'); ?></span></div><div class="site_info"><p><span>${val.name}</span><span>${val.tel}</span></p><p>${val.area ? val.area + " " + val.address : val.address}</p></div><div class="btn_container"><div class="compile"><?php echo lang('Edit'); ?></div><div class="remove"><?php echo lang('Delete'); ?></div></div></li>
                            `)
                    }
                })
            }
        },
        error: function(err) { console.log(err) }
    })
})

// 设置默认地址
$(".site_list").on('click', 'li', function(e) {
    var li = $(e.target),
        id = $(e.target).attr('id') || $(e.target).parents('li').attr('id');
    $.ajax({
        url: urlPost("my/set_address"),
        type: "POST",
        dataType: "JSON",
        data: { id: id },
        success: function(res) {
            console.log(res)
            if (res.code == 0) {
                if (li[0].tagName != "LI") {
                    $(e.target).parents('li').addClass('default').siblings().removeClass('default')
                } else {
                    $(e.target).addClass('default').siblings().removeClass('default')
                }
                $('.txt').html(`<?php echo lang('set as Default'); ?>`)
                $('.default .txt').html(`<?php echo lang('Default'); ?>`)
                QS_toast.show(res.info, true)
            }
        },
        error: function(err) { console.log(err) }
    })
})

// 删除地址
$(".site_list").on('click', '.remove', function(e) {
    e.stopPropagation()
    var id = $(e.target).attr('id') || $(e.target).parents('li').attr('id');
    layer.open({
        content: `<?php echo lang('Are you sure you want to delete the address'); ?>`,
        btn: [`<?php echo lang('confirm'); ?>`, `<?php echo lang('cancel'); ?>`],
        yes: function(index) {
            $.ajax({
                url: urlPost("my/del_address"),
                type: "POST",
                dataType: "JSON",
                data: { id: id },
                success: function(res) {
                    console.log(res)
                    if (res.code == 0) {
                        $(e.target).parents('li').remove()
                        layer.close(index);
                        QS_toast.show(res.info, true)
                    } else {
                        QS_toast.show(res.info, true)
                    }
                },
                error: function(err) { console.log(err) }
            })
        },
        no: (function() {
            layer.close();
        })
    });

})

// 编辑地址
$(".site_list").on('click', '.compile', function(e) {
    e.stopPropagation()
    var site_id = $(e.target).parents('li').attr('id')
    sessionStorage.setItem("site_id", site_id);
    location.href = "./set_site.html"
})
</script></html>