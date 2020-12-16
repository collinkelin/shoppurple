<?php /*a:1:{s:63:"/www/wwwroot/45.118.248.72/application/index/view/ctrl/vip.html";i:1595234460;}*/ ?>
<html><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1.0"><meta name="keywords" content=""><meta name="description" content=""><title><?php echo lang('Member Upgrade'); ?></title><link rel="stylesheet" href="/public/css/style.min.css?v=1595240260"><link rel="stylesheet" href="/statics/css/share.min.css?v=1595240260"><link rel="stylesheet" href="/statics/css/font.min.css?v=1595240260&v=v1"><link href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css" type="text/css" rel="styleSheet" id="layermcss"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><script type="text/javascript" src="/statics/js/jquery-2.1.1.min.js"></script><script type="text/javascript" src="/statics/js/jquery-form.js"></script><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="https://cdn.jsdelivr.net/npm/swiper@5.3.6/js/swiper.min.js"></script><!-- <script src="/statics/js/swiper.3.1.7.min.js"></script> --><script src="/statics/js/jquery.simplesidebar.js"></script><script src="/statics/js/jquery.SuperSlide.2.1.1.js"></script><script src="/statics/js/TouchSlide.1.0.js"></script><script type="text/javascript" src="/statics/js/func.js?v=1595240260"></script><script>if(("standalone" in window.navigator) && window.navigator.standalone){
        var noddy, remotes = false;
        document.addEventListener('click', function(event) {
            noddy = event.target;
            while(noddy.nodeName !== "A" && noddy.nodeName !== "HTML") {
                noddy = noddy.parentNode;
            }
            if('href' in noddy && noddy.href.indexOf('http') !== -1 && (noddy.href.indexOf(document.location.host) !== -1 || remotes)){
                event.preventDefault();
                document.location.href = noddy.href;
            }
        },false);
    }</script></head><body style="background-color:#ffffff;" class="vip"><header><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('Member Upgrade'); ?></span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><!-- 头部部分 开始 --><section><div class="vip_car"><div class="vip_car_xx"><img class="vip_car_logo" src="<?php echo htmlentities($info['headpic']); ?>" alt="" onerror="this.src='/public/img/head.png'"><p class="vip_car_logo_name" style="width: auto;"><?php echo lang('Membership level'); ?>:<?php echo htmlentities($level_name); ?></p><!-- <p class="vip_car_logo_rw"><?php echo lang('Available every day'); ?>:<span id="num_task"><?php echo htmlentities($order_num); ?></span><?php echo lang('single'); ?></p> --><a class="vip_car_hyxq" href="/index/my/index"><?php echo lang('Member Details'); ?></a></div></div><div class="vip_xxjss"><ul><li><img style="width:50px; height:50px;" src="/public/img/v1.png"><p><?php echo lang('Commission Bonus'); ?></p></li><li><img  style="width:50px; height:50px;" src="/public/img/v2.png"><p style="color: #e65a69;"><?php echo lang('More tasks'); ?></p></li><li><img style="width:50px; height:50px;"  src="/public/img/v3.png"><p style="color: #33cdf8;"><?php echo lang('Exclusive customer service'); ?></p></li></ul></div><p style="text-align: center;color: #d4d2d2;"><?php echo lang('Swipe left and right to see more'); ?></p><!--</div>--><div class="swiper-container vio_rwktlb" id="vip_sel"><ul class="swiper-wrapper"><?php foreach($member_level as $key=>$vo): $lv = $vo['id'] == $info['level'] ? 1 : ''  ?><li class="swiper-slide sub <?php echo $vo['level'] > $info['level'] ? 'lv' :'' ;?>  " data-id="<?php echo htmlentities($vo['level']); ?>" data-price="<?php echo htmlentities($vo['num']); ?>"><p class="vip_hylss"><?php echo htmlentities($vo['name']); ?></p><?php if($vo['num'] > '0'): ?><p class="vip_hyjg"><?php echo lang('member price',[lang('symbol'),$vo['num']]); ?></p><?php endif; if(!(empty($vo['extended']['withdraw_num']) || (($vo['extended']['withdraw_num'] instanceof \think\Collection || $vo['extended']['withdraw_num'] instanceof \think\Paginator ) && $vo['extended']['withdraw_num']->isEmpty()))): ?><p><?php echo lang('Withdrawal count', $vo['extended']); ?></p><?php endif; if(( $vo['extended']['withdraw_min'] ) OR ( $vo['extended']['withdraw_max'] )): if(empty($vo['extended']['withdraw_max']) || (($vo['extended']['withdraw_max'] instanceof \think\Collection || $vo['extended']['withdraw_max'] instanceof \think\Paginator ) && $vo['extended']['withdraw_max']->isEmpty())): ?><p><?php echo lang('Withdrawal amount b',$vo['extended']); ?></p><?php else: ?><p><?php echo lang('Withdrawal amount a',$vo['extended']); ?></p><?php endif; ?><?php endif; if(!(empty($vo['order_num']) || (($vo['order_num'] instanceof \think\Collection || $vo['order_num'] instanceof \think\Paginator ) && $vo['order_num']->isEmpty()))): ?><p><?php echo lang('Number of orders',['order_num'=>(int)$vo['order_num']]); ?></p><?php endif; if(( $vo['extended']['commission_min'] ) OR ( $vo['extended']['commission_max'] )): if(empty($vo['extended']['commission_max']) || (($vo['extended']['commission_max'] instanceof \think\Collection || $vo['extended']['commission_max'] instanceof \think\Paginator ) && $vo['extended']['commission_max']->isEmpty())): ?><p><?php echo lang('Commission ratio',$vo['extended']); ?></p><?php else: ?><p><?php echo lang('Commission ratio a',$vo['extended']); ?></p><?php endif; ?><?php endif; if(!(empty($vo['extended']) || (($vo['extended'] instanceof \think\Collection || $vo['extended'] instanceof \think\Paginator ) && $vo['extended']->isEmpty()))): ?><p><?php echo lang('Match order quota a',$vo['extended']); ?></p><?php endif; if(!(empty($vo['extended']['member_num']) || (($vo['extended']['member_num'] instanceof \think\Collection || $vo['extended']['member_num'] instanceof \think\Paginator ) && $vo['extended']['member_num']->isEmpty()))): ?><p><?php echo lang('Upgrade conditions',$vo['extended']); ?></p><?php endif; if(!(empty($vo['directions']) || (($vo['directions'] instanceof \think\Collection || $vo['directions'] instanceof \think\Paginator ) && $vo['directions']->isEmpty()))): ?><!-- <p><?php echo htmlentities($vo['directions']); ?></p> --><p data-name="<?php echo !empty($lv) ? htmlentities($lv) :  ''; ?>" class="vip_yuanjia <?php echo $lv? 'isc': '' ?> "><?php echo htmlentities($vo['directions']); ?></p><?php endif; ?></li><?php endforeach; ?></ul><!-- Add Pagination --><div class="swiper-pagination"></div></div><input type="hidden" id="is_vip_price" value="1"><input type="hidden" id="is_vip_bu" value="1"><input type="hidden" name="price" id="price" value=""><input type="hidden" name="level" id="level" value=""><div class="recharge_box" style="margin-top: 0;border-top: 0; display: none"><input type="hidden" name="payment_type" id="payment_type" value=""><?php foreach($list as $key=>$vo): ?><label data-key="<?php echo htmlentities($vo['id']); ?>"><a><?php if($vo['ico_type'] == '1'): ?><?php echo $vo['ico']; else: ?><img src="<?php echo htmlentities($vo['ico']); ?>" alt="" class="avatar"><?php endif; ?><?php echo htmlentities($vo['name']); ?><span class=""></span></a></label><?php endforeach; ?></div><button data-name="1" style="display: none;" id="submit" class="vip_lijisj"><?php echo lang('Upgrade now'); ?><span id="show_price_1"></span></button><div class="recharge_box2 task_box" style="display:none ;z-index:99999"><div class="con"><ul><li class="payinfo"></li></ul></div><label style="padding:0"><?php echo lang('Please contact customer service if you have questions'); ?></label><button type="button" class="cancel vip_lijisj"><?php echo lang('cancel'); ?></button><button type="submit" id="submit_cz" class="vip_lijisj"><?php echo lang('Confirm recharge'); ?></button><div id="pay_desc" style="text-align: left"></div></div></section><script>    $(document).ready(function() {
        $('.recharge_box label').click(function() {
            $('.recharge_box label span').removeClass('active');
            $(this).find('span').addClass('active');
            var payment_type = $(this).attr('data-key');
            $('#payment_type').val(payment_type);
        });

        $('#vip_sel .sub.lv').click(function() {
            var price = $(this).attr('data-price');
            var level = $(this).attr('data-id');
            if(price > 0){
                $(this).addClass('active').siblings().removeClass('active');

                var is_vip_bu = $('#is_vip_bu').val();
                var is_vip_price = $('#is_vip_price').val();
                if (0 && is_vip_bu == 1) {
                    price = price - is_vip_price;
                    $('#show_price_1').html("（<?php echo lang('Need to make up'); ?><?php echo lang('symbol'); ?>" + price + "）");
                } else {
                    $('#show_price_1').html("（<?php echo lang('symbol'); ?>" + price + "）");
                }

                $('.recharge_box').show();
                $('#submit').show()
                $('#price').val(price);
                $('#level').val(level);
            }
        });

        $('#submit').click(function() {
            var payment_type = $('#payment_type').val();
            var level = $('#level').val();

            if (level == '') {
                sp_tip(`<?php echo lang('Please select the level you want to upgrade'); ?>`);
                return false;
            }
            if (payment_type == '') {
                sp_tip(`<?php echo lang('Please select a payment channel'); ?>`);
                return false;
            }
            submitPay(payment_type)
        })

        setTimeout(function() {
            console.log($('.isc').data('name'))
            $('#num_task').text($('.isc').attr('data-name'))
        }, 100);

        setTimeout(function() {
            console.log($('.isc').data('name'))
            $('#num_task').text($('.isc').attr('data-name'))
        }, 2000);
    });

    function submitPay(paytype) {
        var payment_type = $('#payment_type').val();
        var level = $('#level').val();

        if (level == '') {
            sp_tip(`<?php echo lang('Please select the level you want to upgrade'); ?>`);
            return false;
        }

        $.ajax({
            type: "POST",
            url: "<?php echo url('recharge/recharge_dovip'); ?>",
            data: { level: level, type: paytype },
            dataType: "json",
            async: false,
            success: function(coordinates) {
                result = coordinates;
                if (result.code == 0) {
                    var html = `
                        <a class="link text"><p class="clear"><div class="t"><?php echo lang('Payment method'); ?>: </div><div class="zhuangtai" id="bank_name">${result.info.name}</div></p></a><a class="link text"><p class="clear"><div class="t"><?php echo lang('Order number'); ?>: </div><div class="zhuangtai" id="orderId">${result.info.id}</div></p></a><a class="link text"><p class="clear"><div class="t"><?php echo lang('Recharge amount'); ?>: </div><div class="zhuangtai" id="price2">${result.info.num}</div></p></a>                    `;
                    if (result.paytype == 'alipay_wap' && result.redirect_url) {
                        window.location.href = result.redirect_url;
                        return false;
                    }
                    if (result.paytype != '' && result.redirect_url) {
                        window.location.href = result.redirect_url;
                        return false;
                    }

                    if (result.info.name2 == 'card') {
                        html += `
                            <a class="link text"><p class="clear"><div class="t"><?php echo lang('Owned bank'); ?>: </div><div class="zhuangtai" id="">${result.info.payinfo.bank_name}</div></p></a><a class="link text"><p class="clear"><div class="t"><?php echo lang('Branch name'); ?>: </div><div class="zhuangtai" id="">${result.info.payinfo.branch_name}</div></p></a><a class="link text"><p class="clear"><div class="t"><?php echo lang('Branch number'); ?>: </div><div class="zhuangtai" id="">${result.info.payinfo.branch_number}</div></p></a><a class="link text"><p class="clear"><div class="t"><?php echo lang('Bank card number'); ?>: </div><div class="zhuangtai" id="">${result.info.payinfo.card_number}</div></p></a><a class="link text"><p class="clear"><div class="t"><?php echo lang('Name a'); ?>: </div><div class="zhuangtai" id="">${result.info.payinfo.name}</div></p></a>                            `;
                    }
                    if (result.info.name2 == 'balance') {
                        html += `
                            <a class="link text"><p class="clear"><div class="t"><?php echo lang('Your available balance'); ?>: </div><div class="zhuangtai" id="">${result.info.balance}</div></p></a>                            `;
                    }
                    if(result.info.payinfo.qrcode){
                        html += `
                            <a class="link"><p class="clear"><div class="qrcode" id=""><img src="${result.info.payinfo.qrcode}"></div></p></a>                            `;
                    }
                    $('.payinfo').html(html);
                    $('.task_box').show();
                } else {
                    sp_tip(result.info);
                }


            },
            error: function(data) {
                console.log('error' + data.status)
            }
        });
    }
    $('.cancel').click(function() {
        $('.task_box').hide();
    });

    $('#submit_cz').click(function() {
        layer.open({
            content: `<?php echo lang('Recharging'); ?>`,
            skin: 'msg',
            time: 2 //2秒后自动关闭
        });
        $('.task_box').hide();
    });

    function submit_callback(data) {
        if (data.status == 1) {
            window.location.href = data.url;
        } else {
            sp_tip(data.info);
        }
    }

    $('.copy_btn').click(function() {
        // let txt = $(this).siblings('input');
        // txt.select();
        // document.execCommand('copy');
        var val = $(this).siblings('input').attr('id');
        copyNum(val)
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

    //
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 2,
        spaceBetween: 10,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });
    </script></body></html>