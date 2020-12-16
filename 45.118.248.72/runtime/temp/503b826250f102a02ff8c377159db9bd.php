<?php /*a:2:{s:64:"/www/wwwroot/45.118.248.72/application/index/view/ctrl/bank.html";i:1590340328;s:59:"/www/wwwroot/45.118.248.72/application/index/view/main.html";i:1593514237;}*/ ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="Robots" content="noindex,nofollow"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><title><?php echo sysconf('site_name'); ?></title><link rel="stylesheet" type="text/css" href="/public/css/style.min.css?v=1595234893"><link rel="stylesheet" type="text/css" href="/public/css/ui.min.css?v=1595234893"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><style>
.bank-list li {
    height: 2.5rem;
    display: flex;
    justify-content: space-between;
    flex-direction: row;
    border-bottom: 1px solid #eeeeee;
    padding: 0 2rem 0 1rem;
    position: relative;
}

.bank-list li::after {
    content: "";
    position: absolute;
    right: .5rem;
    top: 0;
    bottom: 0;
    margin: auto;
    width: 1rem;
    height: 1rem;
    background-image: url(/public/img/right.png);
    background-size: 100%;
    background-repeat: no-repeat;
}

.bank-list li>span {
    margin: auto 0;
}

.bank_title {
    padding: .5rem 1rem;
    border-bottom: 1px solid #eeeeee;
}

#add {
    position: absolute;
    right: .5rem;
    top: 0;
    bottom: 0;
    margin: auto;
    width: 1.2rem;
    height: 1.1rem;
    background-image: url(/public/img/add.png);
    background-size: cover;
    background-repeat: no-repeat;
}

.no-data {
    height: 2rem;
    text-align: center;
    line-height: 2rem;
    color: #777777;
}
</style></head><body><header><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('Bank Card'); ?></span><a class="back" href="<?php echo url('my/add_bank'); ?>"><i class="fa fa-plus" aria-hidden="true"></i></a></header><section><div class="container"><div class="bank"><!-- <p class="bank_title"><?php echo lang('My bank card'); ?></p> --><ul class="bank-list"></ul></div></div></section><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="https://cdn.jsdelivr.net/npm/dot@1.1.3/doT.min.js"type="text/javascript" ></script><script src="https://cdn.jsdelivr.net/npm/cleave.js@1.5.10/dist/cleave.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595234893"></script><script src="/public/js/common.min.js?v=1595234893"></script><script src="/public/js/city.js"></script><script src="/public/js/picker.min.js"></script><script>
$(function() {
    var dataList = 0;
    $.ajax({
        url: urlPost("ctrl/do_bankinfo"),
        type: "GET",
        dataType: "JSON",
        data: {},
        success: function(res) {
            console.log(res)
            var list = res.data || [];
            dataList = list.length;
            if (list.length == 0) {
                $('.bank-list').append(`<div class="no-data"><?php echo lang('You have not added bank card information'); ?></div>`);
                $('#deposit').click(function() {
                    layer.open({
                        content: `<?php echo lang('You have not added bank card information'); ?>`,
                        btn: [`<?php echo lang('Go to add'); ?>`, `<?php echo lang('cancel'); ?>`],
                        shadeClose: false,
                        yes: function(index) {
                            location.href = "<?php echo url('my/add_bank'); ?>"
                        }
                    });
                })
            } else {
                $('#deposit').click(function() {
                    location.href = "<?php echo url('ctrl/deposit'); ?>"
                })
            }
            if (res.code == 0) {
                list.map(function(val) {
                    if (val.status == 1) {
                        $('.bank-list').prepend(`
                            <li id="${val.id}"><span class="title" title="">${val.bank_name}</span><span>${val.card_number}</span></li>
                            `)
                    } else {
                        $('.bank-list').append(`
                            <li id="${val.id}"><span class="title" title="">${val.bank_name}</span><span>${val.card_number}</span></li>
                            `)
                    }
                })
            }
        },
        error: function(err) { console.log(err) }
    })

})

$(".bank-list").on('click', 'li', function(e) {
    var bankCard = $(e.target).attr('id') || $(e.target).parents('li').attr('id');
    sessionStorage.setItem('bankId', bankCard)
    location.href = "<?php echo url('my/edit_bank'); ?>"
})
</script></body></html>