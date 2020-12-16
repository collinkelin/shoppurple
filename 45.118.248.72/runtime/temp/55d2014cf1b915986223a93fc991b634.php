<?php /*a:1:{s:71:"/www/wwwroot/45.118.248.72/application/index/view/order/order_info.html";i:1593600675;}*/ ?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><title><?php echo lang('order details'); ?></title><link rel="stylesheet" href="/public/css/style.min.css?v=1595178492"><link rel="stylesheet" href="/public/css/ui.min.css?v=1595178492"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595178492"></script><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="/public/js/common.min.js?v=1595178492"></script><style>
    .container>div {
        padding: .5rem 1rem;
    }

    .site {
        border-bottom: .3rem solid rgb(248, 242, 242);
    }

    .site>div:first-child {
        display: flex;
        justify-content: space-between;
        flex-direction: row;
        margin-bottom: .5rem;
    }

    .site>div:last-child {
        position: relative;
    }

    .site>div:last-child::after {
        content: "";
        width: 1rem;
        height: 1rem;
        position: absolute;
        right: 0;
        background-image: url(/public/img/right.png);
        background-size: cover;
        background-repeat: no-repeat;
    }

    .location {
        width: 75%;
        display: inline-block;
    }

    .order {
        font-size: .5rem;
        border-bottom: .3rem solid rgb(248, 242, 242);
    }

    .order_info {
        margin-top: .5rem;
        display: flex;
    }

    .info_img {
        width: 3rem;
        height: 3rem;
        background: #eeeeee;
    }

    .info_data {
        max-width: 55%;
        margin: 0 0 0 1rem;
        display: flex;
        justify-content: space-between;
        flex-direction: column;
    }

    .info_store,
    .money {
        color: #FDC003;
    }

    .info_money {
        margin-left: auto;
        text-align: right;
    }

    .data_cont {
        border-bottom: .3rem solid rgb(248, 242, 242);
        text-align: right;
    }

    .data_cont>p {
        margin-bottom: .2rem;
    }

    .container>p {
        border-bottom: .1rem solid rgb(248, 242, 242);
        text-align: right;
        padding: .3rem 1rem;
    }

    .data_cont>p span:last-child {
        color: #FDC003;
        margin-left: .1rem;
    }

    .btn {
        margin: 1.2rem 1rem;
        height: 2.2rem;
        line-height: 2.2rem;
        border-radius: 5px;
        background: #FDC003;
        text-align: center;
        color: white;
        padding: 0 !important;
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
    </style></head><body><header><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('order details'); ?></span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><section><div class="container"><div class="site"><div><p><?php echo lang('Consignee name'); ?>:<span class="user"><?php echo htmlentities($info['name']); ?></span></p><p class="tel"><?php echo htmlentities($info['tel']); ?></p></div><div><?php echo lang('Shipping address'); ?>: <span class="location"><?php echo htmlentities($info['address']); ?></span></div></div><div class="order"><div class="order_num"><?php echo htmlentities($info['oid']); ?></div><div class="order_info"><div class="info_img"><img src="<?php echo htmlentities($info['goods_pic']); ?>" alt=""></div><div class="info_data"><p class="info_name"><?php echo htmlentities($info['goods_name']); ?></p><p class="info_store"><?php echo htmlentities($info['shop_name']); ?></p></div><div class="info_money"><p class="money" style="margin-bottom: .5rem;"><?php echo lang('symbol'); ?><?php echo htmlentities($info['goods_price']); ?></p><p>x<span class="info_num"><?php echo htmlentities($info['goods_count']); ?></span></p></div></div></div><p><span class="data_title"><?php echo lang('Your available balance'); ?>:</span><span class="usable_num"><?php echo lang('symbol'); ?><?php echo htmlentities($info['balance']); ?></span></p><div class="data_cont"><p><span class="data_title"><?php echo lang('order amount'); ?>:</span><span class="data_money"><?php echo lang('symbol'); ?><?php echo htmlentities($info['num']); ?></span></p><p><span class="data_title"><?php echo lang('Commission'); ?>:</span><span class="brokerage"><?php echo lang('symbol'); ?><?php echo htmlentities($info['commission']); ?></span></p><p><span class="data_title"><?php echo lang('Estimated return'); ?>:</span><span class="return"><?php echo lang('symbol'); ?><?php echo round(($info['num']+$info['commission'])*100)/100; ?></span></p><?php if($info['special'] != '0'): ?><p><span class="data_title"><?php echo lang('Paid'); ?>:</span><span class="paid"><?php echo lang('symbol'); ?><?php echo htmlentities($info['paid']); ?></span></p><p><span class="data_title"><?php echo lang('Still need to pay'); ?>:</span><span class="difference"><?php echo lang('symbol'); ?><?php echo htmlentities($info['difference']); ?></span></p><?php endif; ?></div><?php if($info['status'] == '0'): ?><div class="btn btn-submit"><?php echo lang('Submit orders'); ?></div><?php if($match['cancel'] == '1'): ?><div class="btn btn-cancel"><?php echo lang('Cancel order'); ?></div><?php endif; ?><?php endif; ?></div></section></body><script>
var oid = "<?php echo htmlentities($info['oid']); ?>", // order ID
    add_id = "<?php echo htmlentities($info['add_id']); ?>",
    submit = true;

$(".site").click(function() {
    location.href = `<?php echo url('ctrl/receive_site'); ?>`
})

var zhujiTime = "<?php echo config('deal_zhuji_time'); ?>";
var shopTime = "<?php echo config('deal_shop_time'); ?>";
zhujiTime = zhujiTime * 1000;
shopTime = shopTime * 1000;

$(".btn-submit").click(function() {
    var status = $(this).data('status');
    console.log(status);
    var i = 0;
    layer.open({
        type: 2,
        content: `<?php echo lang('Order submission'); ?>`,
        time: zhujiTime,
        shadeClose: false,
    });
    var timer = setInterval(function() {
        i++;
        if (i == 1) {
            layer.open({
                type: 2,
                content: `<?php echo lang('Remote host is being assigned'); ?>`,
                time: zhujiTime,
                shadeClose: false,
            })
        } else if (i == 2) {
            layer.open({
                type: 2,
                content: `<?php echo lang('Waiting for a response from the merchant system'); ?>`,
                time: shopTime,
                shadeClose: false,
            })

            var ajaxT = setTimeout(function() {
                $.ajax({
                    url: `<?php echo url('order/do_order'); ?>`,
                    type: "POST",
                    dataType: "JSON",
                    data: { oid: oid, add_id: add_id, status: 1 },
                    success: function(res) {
                        console.log(res)
                        if (res.code == 0) {
                            QS_toast.show(`<?php echo lang('Orders submitted successfully'); ?>`, true);
                            clearInterval(timer)
                            var linkTime = setTimeout(function() {
                                location.href = res.url
                            }, 1800);
                        } else {
                            if (res.code == 5) {
                                layer.open({
                                    // anim: 'up',
                                    content: res.info,
                                    btn: [`<?php echo lang('confirm'); ?>`, `<?php echo lang('cancel'); ?>`],
                                    shadeClose: false,
                                    yes: function(index) {
                                        if (res.url)
                                            location.href = res.url
                                        layer.closeAll();
                                    },
                                    no: function() {
                                        layer.closeAll();
                                    }
                                });
                            } else {
                                QS_toast.show(res.info, true);
                                if (res.url) {
                                    var linkTime = setTimeout(function() {
                                        location.href = res.url
                                    }, 1800);
                                } else {
                                    var timer = setInterval(function() {
                                        location.href = location.href
                                    }, 3000);
                                }
                            }
                        }
                        sumbit = true;
                    },
                    error: function(err) {
                        console.log(err);
                        sumbit = true;
                    }
                })
            }, shopTime)
        }
    }, zhujiTime)
})

$('.btn-cancel').on('click', function(argument) {
    layer.open({
        content: `<?php echo lang('Cancel order'); ?>`,
        btn: [`<?php echo lang('confirm'); ?>`, `<?php echo lang('cancel'); ?>`],
        shadeClose: false,
        yes: function(index) {
            $.ajax({
                url: `<?php echo url('order/do_order'); ?>`,
                type: "POST",
                dataType: "JSON",
                data: { oid: oid, add_id: add_id, status: 2 },
                success: function(res) {
                    console.log(res)
                    if (res.code == 0) {
                        QS_toast.show(`<?php echo lang('Order cancelled successfully'); ?>`, true);
                        var linkTime = setTimeout(function() {
                            location.href = res.url
                        }, 1800);
                    } else {
                        QS_toast.show(res.info, true);
                        layer.closeAll();
                    }
                    sumbit = true;
                },
                error: function(err) {
                    console.log(err);
                    sumbit = true;
                }
            })
        },
        no: function() {
            layer.closeAll();
        }
    });

})
</script></html>