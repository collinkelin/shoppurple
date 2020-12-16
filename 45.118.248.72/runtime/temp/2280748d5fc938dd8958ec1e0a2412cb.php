<?php /*a:2:{s:69:"/www/wwwroot/45.118.248.72/application/admin/view/help/help_list.html";i:1588238501;s:59:"/www/wwwroot/45.118.248.72/application/admin/view/main.html";i:1593205496;}*/ ?>
<div class="layui-card layui-bg-gray"><?php if(!(empty($title) || (($title instanceof \think\Collection || $title instanceof \think\Paginator ) && $title->isEmpty()))): ?><div class="layui-card-header layui-anim layui-anim-fadein notselect"><span class="layui-icon layui-icon-next font-s10 color-desc margin-right-5"></span><?php echo htmlentities((isset($title) && ($title !== '')?$title:'')); ?><div class="pull-right"></div></div><?php endif; ?><div class="layui-card-body layui-anim layui-anim-upbit"><div class="think-box-shadow"><a class="layui-btn layui-btn layui-btn" data-open="<?php echo url('help_add',['id'=>0]); ?>" data-value="id#0" style='background:green;'>新增</a><table class="layui-table margin-top-15" lay-skin="line"><?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?><thead><tr><th class='text-left nowrap'>ID</th><th class='text-left nowrap'>标题</th><th class='text-left nowrap'>url</th><th class='text-left nowrap'>内容</th><th class='text-left nowrap'>排序</th><?php if(auth("help_edit")): ?><th class='text-left nowrap'>操作</th><?php endif; ?></tr></thead><?php endif; ?><tbody><?php foreach($list as $key=>$vo): ?><tr><td class='text-left nowrap'><?php echo htmlentities($vo['id']); ?></td><td class='text-left nowrap'><?php echo htmlentities($vo['title']); ?></td><td class='text-left nowrap'><?php echo htmlentities($vo['url']); ?></td><td class='text-left nowrap'><?php echo htmlentities($vo['content']); ?></td><td class='text-left nowrap'><?php echo htmlentities($vo['sort']); ?></td><td class='text-left nowrap'><?php if(auth("help_edit")): ?><a class="layui-btn layui-btn-xs layui-btn" data-open="<?php echo url('help_edit',['id'=>$vo['id']]); ?>" data-value="id#<?php echo htmlentities($vo['id']); ?>" style='background:green;'>编辑</a><a class="layui-btn layui-btn-xs layui-btn" onClick="data_delete(<?php echo htmlentities($vo['id']); ?>)" style='background:red;'>删除</a><?php endif; ?></td></tr><?php endforeach; ?></tbody></table></div><script>
function data_delete(id) {
    layer.confirm("确认要删除吗，删除后不能恢复", { title: "删除确认" }, function(index) {
        $.ajax({
            type: 'POST',
            url: "<?php echo url('help_del'); ?>",
            data: {
                'id': id,
            },
            beforeSend: function(request) {
                request.setRequestHeader("User-Token-Csrf", "<?php echo systoken('admin/help/help_del'); ?>");
            },
            success: function(res) {
                layer.msg(res.info, { time: 2500 });
                location.reload();
            }
        });
    }, function() {});
}
</script></div></div>