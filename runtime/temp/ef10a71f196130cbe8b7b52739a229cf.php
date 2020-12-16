<?php /*a:2:{s:66:"/www/wwwroot/www.oabuhps.cn/application/admin/view/index/main.html";i:1591376575;s:60:"/www/wwwroot/www.oabuhps.cn/application/admin/view/main.html";i:1593205496;}*/ ?>
<div class="layui-card layui-bg-gray"><?php if(!(empty($title) || (($title instanceof \think\Collection || $title instanceof \think\Paginator ) && $title->isEmpty()))): ?><div class="layui-card-header layui-anim layui-anim-fadein notselect"><span class="layui-icon layui-icon-next font-s10 color-desc margin-right-5"></span><?php echo htmlentities((isset($title) && ($title !== '')?$title:'')); ?><div class="pull-right"></div></div><?php endif; ?><div class="layui-card-body layui-anim layui-anim-upbit"><style>
.store-total-container {
    font-size: 14px;
    margin-bottom: 20px;
    letter-spacing: 1px;
}

.store-total-container .store-total-icon {
    top: 45%;
    right: 8%;
    font-size: 65px;
    position: absolute;
    color: rgba(255, 255, 255, 0.4);
}

.store-total-container .store-total-item {
    color: #fff;
    line-height: 4em;
    padding: 15px 25px;
    position: relative;
}

.store-total-container .store-total-item>div:nth-child(2) {
    font-size: 46px;
    line-height: 46px;
}
</style><div class="think-box-shadow store-total-container notselect"><div class="margin-bottom-15"></div><div class="layui-row layui-col-space15"><div class="layui-col-sm6 layui-col-md3"><div class="store-total-item nowrap" style="background:linear-gradient(-125deg,#57bdbf,#2f9de2)"><div>商品总量</div><div><?php echo htmlentities($goods_num); ?></div><div>当前商品总数量</div></div><i class="store-total-icon layui-icon layui-icon-template-1"></i></div><div class="layui-col-sm6 layui-col-md3"><div class="store-total-item nowrap" style="background:linear-gradient(-125deg,#ff7d7d,#fb2c95)"><div>用户总量</div><div><?php echo htmlentities($users_num); ?></div><div>当前用户总数量</div></div><i class="store-total-icon layui-icon layui-icon-user"></i></div><div class="layui-col-sm6 layui-col-md3"><div class="store-total-item nowrap" style="background:linear-gradient(-113deg,#c543d8,#925cc3)"><div>订单总量</div><div><?php echo htmlentities($order_num); ?></div><div>已付款订单总数量</div></div><i class="store-total-icon layui-icon layui-icon-read"></i></div><div class="layui-col-sm6 layui-col-md3"><div class="store-total-item nowrap" style="background:linear-gradient(-141deg,#ecca1b,#f39526)"><div>评价总量</div><div>0</div><div>订单评价总数量</div></div><i class="store-total-icon layui-icon layui-icon-survey"></i></div></div></div><div class="think-box-shadow store-total-container"><div class="margin-bottom-15">实时概况</div><div class="layui-row layui-col-space15"><div class="layui-col-md6 margin-bottom-15"><div class="layui-row"><div class="layui-col-xs3 text-center"><i class="layui-icon color-blue" style="font-size:60px;line-height:72px">&#xe65e;</i></div><div class="layui-col-xs4"><div class="font-s14">销售额（元）</div><div class="font-s16"><?php echo htmlentities(sprintf('%.2f',$sell_num)); ?></div><div class="font-s12 color-desc">昨日：<?php echo htmlentities(sprintf('%.2f',$sell_y_num)); ?></div></div><div class="layui-col-xs5"><div class="font-s14">支付订单数</div><div class="font-s16"><?php echo htmlentities($sell_count); ?></div><div class="font-s12 color-desc"><?php echo htmlentities($sell_y_count); ?></div></div></div></div><div class="layui-col-md6 margin-bottom-15"><div class="layui-row"><div class="layui-col-xs3 text-center"><i class="layui-icon color-blue" style="font-size:60px;line-height:72px">&#xe663;</i></div><div class="layui-col-xs4"><div class="font-s14">新增用户数</div><div class="font-s16"><?php echo htmlentities($new_user); ?></div><div class="font-s12 color-desc">昨日：<?php echo htmlentities($new_y_user); ?></div></div><div class="layui-col-xs5"><div class="font-s14">下单用户数</div><div class="font-s16"><?php echo htmlentities($user_order); ?></div><div class="font-s12 color-desc"><?php echo htmlentities($user_y_order); ?></div></div></div></div></div></div><div class="layui-row layui-col-space15"><div class="think-box-shadow"><table class="layui-table" lay-skin="line" lay-even><thead><tr><th class='text-left nowrap'>顶级用户</th><th class='text-left nowrap'>今日充值/提现</th><th class='text-left nowrap'>昨日充值/提现</th><th class='text-left nowrap'>本周充值/提现</th><th class='text-left nowrap'>上周充值/提现</th><th class='text-left nowrap'>本月充值/提现</th><th class='text-left nowrap'>上月充值/提现</th></tr></thead><tbody><?php foreach($list as $key=>$vo): ?><tr><td><?php echo htmlentities($vo['username']); ?></td><td><?php echo htmlentities($vo['data']['today']['recharge']); ?> / <?php echo htmlentities($vo['data']['today']['deposit']); ?></td><td><?php echo htmlentities($vo['data']['yesterday']['recharge']); ?> / <?php echo htmlentities($vo['data']['yesterday']['deposit']); ?></td><td><?php echo htmlentities($vo['data']['week']['recharge']); ?> / <?php echo htmlentities($vo['data']['week']['deposit']); ?></td><td><?php echo htmlentities($vo['data']['lastWeek']['recharge']); ?> / <?php echo htmlentities($vo['data']['lastWeek']['deposit']); ?></td><td><?php echo htmlentities($vo['data']['month']['recharge']); ?> / <?php echo htmlentities($vo['data']['month']['deposit']); ?></td><td><?php echo htmlentities($vo['data']['lastMonth']['recharge']); ?> / <?php echo htmlentities($vo['data']['lastMonth']['deposit']); ?></td></tr><?php endforeach; ?></tbody></table></div></div><script>
$(function() {
    if ($('#versionTest').data('version')) {
        setTimeout(function() {
            //updateVersion();
        }, 1000);
    }
});



function updateVersion() {
    //多窗口模式，层叠置顶
    layer.open({
        type: 2 //此处以iframe举例
            ,
        title: '有新的版本更新提示!',
        area: ['680px', '550px'],
        shade: 0,
        skin: 'layui-layer-setmybg',
        maxmin: true,
        content: "<?php echo url('Index/clear'); ?>" + '?',
        btn: ['查看全部更新记录', '关闭'] //只是为了演示
            ,
        yes: function() {
            //window.open('http://sq.251zy.com/update.html');
        },
        btn2: function() {
                layer.closeAll();
            }

            ,
        zIndex: layer.zIndex //重点1
            ,
        success: function(layero) {
            layer.setTop(layero); //重点2
        }
    });
    return false;
}
</script></div></div>