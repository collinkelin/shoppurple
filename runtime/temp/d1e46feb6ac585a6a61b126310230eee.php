<?php /*a:2:{s:67:"/www/wwwroot/www.oabuhps.cn/application/admin/view/order/index.html";i:1593758702;s:60:"/www/wwwroot/www.oabuhps.cn/application/admin/view/main.html";i:1593205496;}*/ ?>
<div class="layui-card layui-bg-gray"><?php if(!(empty($title) || (($title instanceof \think\Collection || $title instanceof \think\Paginator ) && $title->isEmpty()))): ?><div class="layui-card-header layui-anim layui-anim-fadein notselect"><span class="layui-icon layui-icon-next font-s10 color-desc margin-right-5"></span><?php echo htmlentities((isset($title) && ($title !== '')?$title:'')); ?><div class="pull-right"></div></div><?php endif; ?><div class="layui-card-body layui-anim layui-anim-upbit"><div class="think-box-shadow"><form class="layui-form search-form" lay-filter="search-form"><div class="layui-form-item"><div class="layui-form-mid"><div class="layui-form-mid">订单类型</div><div class="layui-input-inline" style="width: 130px;"><select name="type"><option value="-1" selected="">所有</option><option value="0">正常</option><option value="1">特殊</option></select></div></div><div class="layui-form-mid"><div class="layui-form-mid">订单状态</div><div class="layui-input-inline" style="width: 130px;"><select name="status"><option value="-1" selected="">所有</option><option value="0">等待付款</option><option value="1">完成付款</option><option value="2">用户取消</option><option value="3">强制付款</option><option value="4">系统取消</option><option value="5">订单冻结</option></select></div></div><div class="layui-form-mid"><div class="layui-form-mid">搜索类型</div><div class="layui-input-inline" style="width: 100px;"><select name="valueKey"><option value="id" selected="">订单号</option><option value="username">用户名</option><option value="uid">用户ID</option></select></div><div class="layui-form-mid">搜索内容</div><div class="layui-input-inline" style="width: 100px;"><input type="text" name="value" placeholder="" autocomplete="off" class="layui-input"></div></div><div class="layui-form-mid"><div class="layui-form-mid">时间段</div><div class="layui-input-inline" style="width: 100px;"><input type="text" name="startTime" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input" id="startTime"></div><div class="layui-form-mid">-</div><div class="layui-input-inline" style="width: 100px;"><input type="text" name="endTime" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input" id="endTime"></div></div><div class="layui-form-mid"><div class="layui-form-mid">排序类型</div><div class="layui-input-inline" style="width: 130px;"><select name="sortKey"><option value="addtime" selected="">下单时间</option><option value="uid">用户ID</option><option value="endtime">完成时间</option><option value="difference">未支付</option><option value="paid">已支付</option><option value="num">交易数额</option></select></div><div class="layui-form-mid">排序方法</div><div class="layui-input-inline" style="width: 130px;"><select name="sort"><option value="DESC" selected="">降序</option><option value="ASC">升序</option></select></div></div><div class="layui-form-mid"><div class="layui-input-inline"><span class="layui-btn" lay-submit="" lay-filter="search">搜索</span></div></div></div></form><table class="layui-tab table-data" lay-filter="list"></table></div><script type="text/html" id="toolbar"></script><script type="text/html" id="action">    {{#  if(d.status === 0){ }}
    {{#  if(d.special === 0){ }}
    <a class="layui-btn layui-btn-xs" lay-event="order">强制付款</a>    {{#  } }}
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="cancel">取消订单</a>    {{#  } }}
</script><script type="text/html" id="special">    {{ d.special ? '特殊' : '' }}
</script><script type="text/html" id="goods_price">    {{ formatNumber(d.goods_price) }}
</script><script type="text/html" id="num">    {{ formatNumber(d.num) }}
</script><script type="text/html" id="commission">    {{ formatNumber(d.commission) }}
</script><script type="text/html" id="paid">    {{ formatNumber(d.paid) }}
</script><script type="text/html" id="difference">    {{ formatNumber(d.difference) }}
</script><script type="text/html" id="status">    {{ statuss[d.status] }}
</script><script type="text/html" id="create_time">    {{layui.util.toDateString(d.addtime*1000, 'yyyy-MM-dd HH:mm:ss')}}
</script><script>var statuss = {0: '<span style="color:#009688;">等待付款</span>', 1: '<span style="color:#5FB878;">完成付款</span>', 2: '<span style="color:#FFB800;">用户取消</span>', 3: '<span style="color:#1E9FFF;">强制付款</span>', 4: '<span style="color:#FF5722;">系统取消</span>', 5: '<span style="color:#2F4056;">订单冻结</span>'}
var table = layui.table;
var form = layui.form;
var laydate = layui.laydate;
var where = {};
var order_token = '';
var cancel_token = '';
form.val('search-form', where);
form.render();
var tableIn = table.render({
    elem: '.table-data',
    url: '<?php echo url(""); ?>',
    method: 'post',
    // totalRow: true,
    toolbar: '#toolbar', //开启头部工具栏，并为其绑定左侧模板
    defaultToolbar: ['filter', 'exports', 'print', { //自定义头部工具栏右侧图标。如无需自定义，去除该参数即可
        title: '提示',
        layEvent: 'LAYTABLE_TIPS',
        icon: 'layui-icon-tips'
    }],
    parseData: function(res) { //res 即为原始返回的数据
        order_token = res.token.order;
        cancel_token = res.token.cancel;
        where = res.where;
        return {
            "code": res.code, //解析接口状态
            "msg": res.msg, //解析提示文本
            "count": res.count, //解析数据长度
            "data": res.data //解析数据列表
        };
    },
    cols: [
        [
            { field: 'id', title: '订单号', fixed: true, sort: true, totalRowText: '合计行', event: 'id', },
            { field: 'username', title: '用户名', event: 'username', },
            { field: 'goods_name', title: '商品名称', },
            { field: 'goods_price', title: '商品单价', templet: '#goods_price', totalRow: true, },
            { field: 'goods_count', title: '交易数量', totalRow: true, },
            { field: 'num', title: '交易数额', templet: '#num', totalRow: true, },
            { field: 'commission', title: '佣金', templet: '#commission', totalRow: true, },
            { field: 'paid', title: '已支付', templet: '#paid', totalRow: true, },
            { field: 'difference', title: '未支付', templet: '#difference', totalRow: true, },
            { field: 'addtime', title: '下单时间', width: 162, },
            { field: 'special', title: '订单类型', templet: '#special', event: 'special', },
            { field: 'status', title: '交易状态', templet: '#status', event: 'status', },
            { title: '操作', toolbar: '#action', align: "left", width: 160, },
        ]
    ],
    id: 'tableData',
    limits: [10, 15, 20, 25, 50, 100],
    limit: 15,
    page: true,
    where: form.val("search-form")
});

table.on('tool(list)', function(obj) {
    var data = obj.data;
    switch (obj.event) {
        case 'order':
            loading = layer.load(1, { shade: [0.1, '#fff'] });
            $.ajax({
                type: 'POST',
                url: `<?php echo url('order_confirm'); ?>`,
                data: data,
                beforeSend: function(request) {
                    request.setRequestHeader("User-Token-Csrf", order_token);
                },
                success: function(res) {
                    if (res.code == 1) {
                        layer.msg(res.info, { time: 1800, icon: 1 });
                    } else {
                        layer.msg(res.info, { time: 1800, icon: 2 });
                    }
                    layer.close(loading);
                    reload();
                }
            });
            break;
        case 'cancel':
            loading = layer.load(1, { shade: [0.1, '#fff'] });
            $.ajax({
                type: 'POST',
                url: `<?php echo url('order_cancel'); ?>`,
                data: data,
                beforeSend: function(request) {
                    request.setRequestHeader("User-Token-Csrf", cancel_token);
                },
                success: function(res) {
                    if (res.code == 1) {
                        layer.msg(res.info, { time: 1800, icon: 1 });
                    } else {
                        layer.msg(res.info, { time: 1800, icon: 2 });
                    }
                    layer.close(loading);
                    reload();
                }
            });
            break;
        case 'id':
        case 'username':
            var d = { valueKey: obj.event, value: data[obj.event] };
            console.log(d);
            form.val('search-form', d);
            form.render();
            reload();
            break;
        case 'special':
            var d = { type: data.special };
            if(data.special > 1){
                d = { type: 1 };
            }
            console.log(d);
            form.val('search-form', d);
            form.render();
            reload();
            break;
        case 'status':
            var d = { status: data.status };
            console.log(d);
            form.val('search-form', d);
            form.render();
            reload();
            break;
    }
});

//日期
laydate.render({
    elem: '#startTime'
});

//日期
laydate.render({
    elem: '#endTime'
});

function reload() {
    //执行重载
    table.reload('tableData', {
        page: {
            curr: 1 //重新从第 1 页开始
        },
        where: form.val("search-form"),
    }, 'data');
}

//监听提交
form.on('submit(search)', function(data) {
    reload();
    return false;
});
</script></div></div>