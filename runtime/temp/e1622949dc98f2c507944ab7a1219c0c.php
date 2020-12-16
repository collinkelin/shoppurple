<?php /*a:2:{s:67:"/www/wwwroot/www.oabuhps.cn/application/admin/view/users/level.html";i:1589084598;s:60:"/www/wwwroot/www.oabuhps.cn/application/admin/view/main.html";i:1593205496;}*/ ?>
<div class="layui-card layui-bg-gray"><?php if(!(empty($title) || (($title instanceof \think\Collection || $title instanceof \think\Paginator ) && $title->isEmpty()))): ?><div class="layui-card-header layui-anim layui-anim-fadein notselect"><span class="layui-icon layui-icon-next font-s10 color-desc margin-right-5"></span><?php echo htmlentities((isset($title) && ($title !== '')?$title:'')); ?><div class="pull-right"><?php if(auth("add_level")): ?><button data-modal='<?php echo url("add_level"); ?>' data-title="添加等级" class='layui-btn'>添加等级</button><?php endif; ?></div></div><?php endif; ?><div class="layui-card-body layui-anim layui-anim-upbit"><div class="think-box-shadow"><table class="layui-table margin-top-15" lay-filter="tab" lay-skin="line"><?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?><thead><tr><th lay-data="{field:'id',width:80}" class='text-left nowrap'>ID</th><th lay-data="{field:'level',width:80}" class='text-left nowrap'>等级</th><th lay-data="{field:'name',width:80}" class='text-left nowrap'>名称</th><th lay-data="{field:'num',width:80}" class='text-left nowrap'>会员价格</th><th lay-data="{field:'bili',width:120}" class='text-left nowrap'>佣金比例</th><th lay-data="{field:'match',width:200}" class='text-left nowrap'>匹配规则</th><th lay-data="{field:'num_min',width:120}" class='text-left nowrap'>最小余额</th><th lay-data="{field:'order_num',width:80}" class='text-left nowrap'>接单次数</th><th lay-data="{field:'withdraw',width:200}" class='text-left nowrap'>提现限制(最小/最大/次数)</th><th lay-data="{field:'member',width:200}" class='text-left nowrap'>升级条件(人数/额度)</th><th lay-data="{field:'edit',width:280,fixed: 'right'}" class='text-left nowrap'>操作</th></tr></thead><?php endif; ?><tbody><?php foreach($list as $key=>$vo): ?><tr><td class='text-left nowrap'><?php echo htmlentities($vo['id']); ?></td><td class='text-left nowrap'><?php echo htmlentities($vo['level']); ?></td><td class='text-left nowrap'><?php echo htmlentities($vo['name']); ?></td><td class='text-left nowrap'><?php echo htmlentities($vo['num']); ?></td><td class='text-left nowrap'><?php echo json_decode($vo['extended'], true)['commission_min']??0; ?>/<?php echo json_decode($vo['extended'], true)['commission_max']??0; ?></td><td class='text-left nowrap'><?php echo json_decode($vo['extended'], true)['match_min']??0; ?>/<?php echo json_decode($vo['extended'], true)['match_max']??0; ?>/<?php echo json_decode($vo['extended'], true)['match_proportion']?'比例':'额度'; ?></td><td class='text-left nowrap'><?php echo htmlentities($vo['num_min']); ?></td><td class='text-left nowrap'><?php echo htmlentities($vo['order_num']); ?></td><td class='text-left nowrap'><?php echo json_decode($vo['extended'], true)['withdraw_min']??0; ?>/<?php echo json_decode($vo['extended'], true)['withdraw_max']??0; ?>/<?php echo json_decode($vo['extended'], true)['withdraw_num']??0; ?></td><td class='text-left nowrap'><?php echo json_decode($vo['extended'], true)['member_num']??0; ?>/<?php echo json_decode($vo['extended'], true)['member_money']??0; ?></td><td class='text-left nowrap'><a data-dbclick class="layui-btn layui-btn-xs" data-title="会员等级编辑" data-modal='<?php echo url("admin/users/edit_users_level"); ?>?id=<?php echo htmlentities($vo['id']); ?>'>编辑</a><a class="layui-btn layui-btn-xs layui-btn" onClick="delete_level(<?php echo htmlentities($vo['id']); ?>)" style='background:red;'>删除</a></td></tr><?php endforeach; ?></tbody></table><script>    function delete_level(id) {
        layer.confirm("确认要删除吗，删除后不能恢复", { title: "删除确认" }, function(index) {
            $.ajax({
                type: 'POST',
                url: "<?php echo url('delete_level'); ?>",
                data: {
                    'id': id,
                },
                beforeSend: function(request) {
                    request.setRequestHeader("User-Token-Csrf", "<?php echo systoken('admin/users/delete_level'); ?>");
                },
                success: function(res) {
                    layer.msg(res.info, { time: 2500 });
                    location.reload();
                }
            });
        }, function() {});
    }
    </script><script>    var table = layui.table;
    //转换静态表格
    var limit = Number('<?php echo htmlentities(app('request')->get('limit')); ?>');
    if (limit == 0) limit = 20;
    table.init('tab', {
        cellMinWidth: 120,
        skin: 'line,row',
        size: 'lg',
        limit: limit
    });
    </script><?php if(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty())): ?><span class="notdata">没有记录哦</span><?php else: ?><?php echo (isset($pagehtml) && ($pagehtml !== '')?$pagehtml:''); ?><?php endif; ?></div></div></div>