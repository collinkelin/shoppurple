<?php /*a:2:{s:73:"/www/wwwroot/45.118.248.72/application/admin/view/convey_match/index.html";i:1593753491;s:59:"/www/wwwroot/45.118.248.72/application/admin/view/main.html";i:1593205496;}*/ ?>
<div class="layui-card layui-bg-gray"><?php if(!(empty($title) || (($title instanceof \think\Collection || $title instanceof \think\Paginator ) && $title->isEmpty()))): ?><div class="layui-card-header layui-anim layui-anim-fadein notselect"><span class="layui-icon layui-icon-next font-s10 color-desc margin-right-5"></span><?php echo htmlentities((isset($title) && ($title !== '')?$title:'')); ?><div class="pull-right"></div></div><?php endif; ?><div class="layui-card-body layui-anim layui-anim-upbit"><div class="think-box-shadow"><table class="layui-tab table-data" lay-filter="list"></table></div><script type="text/html" id="toolbar"><div class="layui-btn-container"><?php if(auth("add")): ?><button class="layui-btn layui-btn-sm" lay-event="add">添加</button><?php endif; ?></div></script><script type="text/html" id="action"><a class="layui-btn layui-btn-xs" lay-event="edit"><?php echo lang('edit'); ?></a><a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><?php echo lang('del'); ?></a></script><script type="text/html" id="info"><form class="layui-form" lay-filter="info" style="margin-left:10px;margin-top: 10px;"><div class="layui-form-item"><label class="layui-form-label">次数</label><div class="layui-input-inline"><input type="text" name="frequency" placeholder="请输入次数" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">分配次数:第几次</div></div><div class="layui-form-item"><label class="layui-form-label">金额</label><div class="layui-input-inline"><input type="text" name="initial" placeholder="请输入金额" autocomplete="off" class="layui-input"></div><!-- <div class="layui-form-mid help-tippy" data-tippy-content="Another Tooltip"><i class="layui-icon layui-icon-help" style="font-size: 20px; color: #FF5722;"></i></div> --><div class="layui-form-mid layui-word-aux">金额,为余额的金额(如:金额:10000,最小分配:0.5,最大分配:0.8;则最终刷单金额为:10000*0.5 ~ 10000*0.8)</div></div><div class="layui-form-item"><label class="layui-form-label">最小分配</label><div class="layui-input-inline"><input type="text" name="min" placeholder="请输入最小分配" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">最小分配,比如:3;就是3倍本金</div></div><div class="layui-form-item"><label class="layui-form-label">最大分配</label><div class="layui-input-inline"><input type="text" name="max" placeholder="请输入最大分配" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">最大分配,比如:4;就是4倍本金,必须大于最小分配,否则无法正确刷单</div></div><div class="layui-form-item"><label class="layui-form-label">收益</label><div class="layui-input-inline"><input type="text" name="proportion" placeholder="请输入收益" autocomplete="off" class="layui-input"></div><div class="layui-form-mid layui-word-aux">收益,比如:0.008</div></div><div class="layui-form-item"><label class="layui-form-label">允许撤单</label><div class="layui-input-block"><input type="radio" name="cancel" value="0" title="否" checked=""><input type="radio" name="cancel" value="1" title="是"></div></div><div class="layui-form-item"><div class="layui-input-block"><button type="submit" class="layui-btn" lay-submit="" lay-filter="submit">立即提交</button><button type="reset" class="layui-btn layui-btn-primary">重置</button><input type="hidden" name="id" value="0" /></div></div></form></script><script type="text/html" id="cancel">    {{ d.cancel ? '是' : '否' }}
</script><script type="text/html" id="initial">    {{ formatNumber(d.initial) }}
</script><script>var table = layui.table;
var form = layui.form;
// var where = {};
// form.val('search-form', where);
// form.render();
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
    cols: [
        [
            { field: 'id', title: 'ID', width: 80, fixed: true, sort: true, totalRowText: '合计行', },
            { field: 'frequency', title: '次数', },
            { field: 'initial', title: '金额', templet: '#initial', },
            { field: 'min', title: '最小分配', totalRow: true, },
            { field: 'max', title: '最大分配', totalRow: true, },
            { field: 'proportion', title: '收益', totalRow: true, },
            { field: 'cancel', title: '允许撤单', totalRow: true, templet: '#cancel', },
            { title: '操作', toolbar: '#action', align: "left", width: 110, },
        ]
    ],
    id: 'tableData',
    limits: [10, 15, 20, 25, 50, 100],
    limit: 15,
    page: true,
    // where: form.val("search-form")
});

//头工具栏事件
table.on('toolbar(list)', function(obj) {
    // var checkStatus = table.checkStatus(obj.config.id);
    var data = obj.data;
    switch (obj.event) {
        case 'add':
            info = data;
            layer.open({
                title: '添加',
                type: 1,
                closeBtn: 0, //不显示关闭按钮
                anim: 2,
                area: ['auto', 'auto'],
                shadeClose: true, //开启遮罩关闭
                content: $('#info').html(),
                cancel: function(index, layero) {
                    info = {};
                    return false;
                },
                end: function(index, layero) {
                    info = {};
                    return false;
                }
            });
            // form.val("info", info);
            form.render();
            break;
    };
});

table.on('tool(list)', function(obj) {
    var data = obj.data;
    console.log(obj.event);
    switch (obj.event) {
        case 'del':
            layer.confirm('<?php echo lang("Are you sure you want to delete it"); ?>', function(index) {
                loading = layer.load(1, { shade: [0.1, '#fff'] });
                $.post("<?php echo url('delete'); ?>", { id: data.id }, function(res) {
                    layer.close(loading);
                    layer.close(index);
                    if (res.code == 1) {
                        layer.msg(res.msg, { time: 1000, icon: 1 });
                        obj.del();
                    } else {
                        layer.msg(res.msg, { time: 1000, icon: 2 });
                    }
                });
            });
            break;
        case 'edit':
            info = data;
            layer.open({
                title: '添加',
                type: 1,
                closeBtn: 0, //不显示关闭按钮
                anim: 2,
                area: ['auto', 'auto'],
                shadeClose: true, //开启遮罩关闭
                content: $('#info').html(),
                cancel: function(index, layero) {
                    info = {};
                    return false;
                },
                end: function(index, layero) {
                    info = {};
                    return false;
                }
            });
            form.val("info", info);
            form.render();
            break;
    }
});

function reload() {
    //执行重载
    table.reload('tableData', {
        page: {
            curr: 1 //重新从第 1 页开始
        },
        // where: form.val("search-form"),
    }, 'data');
}

form.on('submit(submit)', function(data) {
    loading = layer.load(1, { shade: [0.1, '#fff'] });
    $.ajax({
        type: 'POST',
        url: "<?php echo url('save'); ?>",
        data: data.field,
        beforeSend: function(request) {
            request.setRequestHeader("User-Token-Csrf", "<?php echo systoken('admin/convey_match/save'); ?>");
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
    return false;
});
</script></div></div>