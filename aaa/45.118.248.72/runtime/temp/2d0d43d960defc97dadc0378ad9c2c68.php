<?php /*a:2:{s:66:"/www/wwwroot/45.118.248.72/application/admin/view/users/index.html";i:1593597803;s:59:"/www/wwwroot/45.118.248.72/application/admin/view/main.html";i:1593205496;}*/ ?>
<div class="layui-card layui-bg-gray"><?php if(!(empty($title) || (($title instanceof \think\Collection || $title instanceof \think\Paginator ) && $title->isEmpty()))): ?><div class="layui-card-header layui-anim layui-anim-fadein notselect"><span class="layui-icon layui-icon-next font-s10 color-desc margin-right-5"></span><?php echo htmlentities((isset($title) && ($title !== '')?$title:'')); ?><div class="pull-right"><?php if(auth("add_users")): ?><button data-modal='<?php echo url("add_users"); ?>' data-title="添加会员" class='layui-btn'>添加会员</button><?php endif; ?></div></div><?php endif; ?><div class="layui-card-body layui-anim layui-anim-upbit"><style type="text/css" media="screen">
::-webkit-scrollbar {
    width: 16px;
    /*滚动条宽度*/
    height: 16px;
    /*滚动条高度*/
}
</style><div class="think-box-shadow"><fieldset><legend>条件搜索</legend><form class="layui-form layui-form-pane form-search" action="<?php echo request()->url(); ?>" onsubmit="return false" method="get" autocomplete="off"><div class="layui-form-item"><div class="layui-form-mid"><div class="layui-form-mid">用户名称</div><div class="layui-input-inline"><input name="username" value="<?php echo htmlentities((app('request')->get('username') ?: '')); ?>" placeholder="请输入用户名称" class="layui-input"></div></div><div class="layui-form-mid"><div class="layui-form-mid">注册时间</div><div class="layui-input-inline"><input data-date-range name="addtime" value="<?php echo htmlentities((app('request')->get('addtime') ?: '')); ?>" placeholder="请选择注册时间" class="layui-input"></div></div><div class="layui-form-mid"><div class="layui-form-mid">上级用户</div><div class="layui-input-inline"><input name="parent" value="<?php echo htmlentities((app('request')->get('parent') ?: '')); ?>" placeholder="请输入上级用户名" class="layui-input"></div></div><div class="layui-form-mid"><div class="layui-form-mid">顶级用户</div><div class="layui-input-inline"><input name="parent_first" value="<?php echo htmlentities((app('request')->get('parent_first') ?: '')); ?>" placeholder="请输入顶级用户名" class="layui-input"></div></div><div class="layui-form-mid"><div class="layui-form-mid">充提时间</div><div class="layui-input-inline"><input data-date-range name="actiontime" value="<?php echo htmlentities((app('request')->get('actiontime') ?: '')); ?>" placeholder="请选择充提时间" class="layui-input"></div></div><div class="layui-form-mid"><div class="layui-form-mid">排序类型</div><div class="layui-input-inline" style="width: 130px;"><select name="sortKey"><option value="addtime" <?php if(app('request')->get('sortKey') == 'addtime'): ?>selected=""<?php endif; ?>>注册时间</option><option value="id" <?php if(app('request')->get('sortKey') == 'id'): ?>selected=""<?php endif; ?>>用户ID</option><option value="balance" <?php if(app('request')->get('sortKey') == 'balance'): ?>selected=""<?php endif; ?>>余额</option><option value="freeze_balance" <?php if(app('request')->get('sortKey') == 'freeze_balance'): ?>selected=""<?php endif; ?>>冻结金额</option><option value="recharge_total" <?php if(app('request')->get('sortKey') == 'recharge_total'): ?>selected=""<?php endif; ?>>总充值</option><option value="deposit_total" <?php if(app('request')->get('sortKey') == 'deposit_total'): ?>selected=""<?php endif; ?>>总提现</option><option value="childs" <?php if(app('request')->get('sortKey') == 'childs'): ?>selected=""<?php endif; ?>>邀请量</option><option value="level" <?php if(app('request')->get('sortKey') == 'level'): ?>selected=""<?php endif; ?>>VIP</option><option value="child_level" <?php if(app('request')->get('sortKey') == 'child_level'): ?>selected=""<?php endif; ?>>用户层级</option></select></div></div><div class="layui-form-mid"><div class="layui-form-mid">排序方法</div><div class="layui-input-inline" style="width: 130px;"><select name="sort"><option value="DESC" <?php if(app('request')->get('sort') == 'DESC'): ?>selected=""<?php endif; ?>>降序</option><option value="ASC" <?php if(app('request')->get('sort') == 'ASC'): ?>selected=""<?php endif; ?>>升序</option></select></div></div><div class="layui-form-mid"><div class="layui-form-mid">余额大于等于</div><div class="layui-input-inline"><input name="balance" value="<?php echo htmlentities((app('request')->get('balance') ?: 0)); ?>" placeholder="请输入顶级用户名" class="layui-input"></div></div><div class="layui-form-mid"><div class="layui-inline"><button class="layui-btn layui-btn-primary"><i class="layui-icon">&#xe615;</i> 搜 索</button></div></div></div></form></fieldset><script>
    form.render()
    </script><fieldset>
        充值:<?php echo htmlentities($recharge); ?> 提现:<?php echo htmlentities($deposit); ?> 总充值人数:<?php echo htmlentities($counts); ?></fieldset><table class="layui-table margin-top-15" lay-filter="tab" lay-skin="line"><?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?><thead><tr><th lay-data="{field:'id',width:80}" class='text-left nowrap'>ID</th><th lay-data="{field:'username'}" class='text-left nowrap'>用户名</th><th lay-data="{field:'balance'}" class='text-left nowrap'>账户余额</th><th lay-data="{field:'freeze_balance'}" class='text-left nowrap'>冻结金额</th><th lay-data="{field:'recharge'}" class='text-left nowrap'>充值/提现</th><th lay-data="{field:'parent_name'}" class='text-left nowrap'>上级用户</th><th lay-data="{field:'parent_user'}" class='text-left nowrap'>顶级用户</th><th lay-data="{field:'invite_code'}" class='text-left'>邀请码(邀请量)</th><th lay-data="{field:'level',width:50}" class='text-left'>VIP</th><th lay-data="{field:'deal_special_count',width:50}" class='text-left'>特殊</th><th lay-data="{field:'addtime',width:168}" class='text-left nowrap'>注册时间</th><th lay-data="{field:'edit',width:430}" class='text-left'>操作</th></tr></thead><?php endif; ?><tbody><?php foreach($list as $key=>$vo): ?><tr><td class='text-left nowrap'><?php echo htmlentities($vo['id']); ?></td><td class='text-left nowrap'><a href="/admin.html#/admin/users/index.html?spm=m-62-63-64&username=<?php echo htmlentities($vo['username']); ?>"><?php echo htmlentities($vo['username']); ?></a></td><td class='text-left nowrap'><?php echo htmlentities($vo['balance']); ?></td><td class='text-left nowrap'><?php echo htmlentities($vo['freeze_balance']); ?></td><td class='text-left nowrap'><?php echo htmlentities($vo['recharge_days']); ?> / <span style="color: #FF5909;"><?php echo htmlentities($vo['deposit_days']); ?></span></td><td class='text-left nowrap'><?php if($vo['parent_id'] > 0): ?><a href="/admin.html#/admin/users/index.html?spm=m-62-63-64&username=<?php echo getUser($vo['parent_id'])['username']; ?>"><?php echo getUser($vo['parent_id'])['username']; ?></a><?php endif; ?></td><td class='text-left nowrap'><?php if($vo['parent_first'] > 0): ?><a href="/admin.html#/admin/users/index.html?spm=m-62-63-64&username=<?php echo getUser($vo['parent_first'])['username']; ?>"><?php echo getUser($vo['parent_first'])['username']; ?></a><?php endif; ?></td><td class='text-left'><?php echo htmlentities($vo['invite_code']); ?>(<?php if($vo['childs']): ?><a style="color: #0963FF;" href="/admin.html#/admin/users/index.html?spm=m-62-63-64&parent=<?php echo htmlentities($vo['username']); ?>"><?php echo htmlentities($vo['childs']); ?></a><?php else: ?><span style="color: #FF1212;"><?php echo htmlentities($vo['childs']); ?></span><?php endif; ?>)
                </td><td class='text-left nowrap'><?php echo htmlentities($vo['level']+1); ?></td><td class='text-left nowrap'><?php echo htmlentities($vo['deal_special_count']); ?></td><td class='text-left nowrap'><?php echo htmlentities(format_datetime($vo['addtime'])); ?></td><td class='text-left'><a data-dbclick class="layui-btn layui-btn-xs" data-title="发送消息" data-modal='<?php echo url("admin/users/sendmsg"); ?>?uid=<?php echo htmlentities($vo['id']); ?>'>发送消息</a><?php if(auth("edit_users")): ?><a class="layui-btn layui-btn-xs layui-btn-warm" onClick="cancel_special(<?php echo htmlentities($vo['id']); ?>)" style='background:green;'>关闭任务</a><!-- <a data-dbclick class="layui-btn layui-btn-xs layui-btn-danger" data-title="暗扣设置" data-modal='<?php echo url("admin/users/edit_users_ankou"); ?>?id=<?php echo htmlentities($vo['id']); ?>'>暗扣设置</a> --><a data-dbclick class="layui-btn layui-btn-xs" data-title="编辑菜单" data-modal='<?php echo url("admin/users/edit_users"); ?>?id=<?php echo htmlentities($vo['id']); ?>'>编 辑</a><?php if(($vo['status'] == 1) and auth("edit_users_status")): ?><a class="layui-btn layui-btn-xs layui-btn-warm" data-action="<?php echo url('edit_users_status',['status'=>2,'id'=>$vo['id']]); ?>" data-value="id#<?php echo htmlentities($vo['id']); ?>;status#2" style='background:red;'>禁用</a><?php elseif(($vo['status'] == 2) and auth("edit_users_status")): ?><a class="layui-btn layui-btn-xs layui-btn-warm" data-action="<?php echo url('edit_users_status',['status'=>1,'id'=>$vo['id']]); ?>" data-value="id#<?php echo htmlentities($vo['id']); ?>;status#1" style='background:green;'>启用</a><?php endif; ?><a class="layui-btn layui-btn-xs layui-btn" data-action="<?php echo url('edit_users_ewm',['status'=>2,'id'=>$vo['id']]); ?>" data-value="id#<?php echo htmlentities($vo['id']); ?>;status#<?php echo htmlentities($vo['invite_code']); ?>" style='background:red;'>刷新二维码</a><a data-dbclick class="layui-btn layui-btn-xs" data-title="银行卡信息" data-modal='<?php echo url("admin/users/edit_users_bk"); ?>?id=<?php echo htmlentities($vo['id']); ?>'>银行卡</a><a class="layui-btn layui-btn-xs layui-btn" onClick="del_user(<?php echo htmlentities($vo['id']); ?>)" style='background:red;'>删除</a><?php endif; ?></td></tr><?php endforeach; ?></tbody></table><script>
    function cancel_special(id) {
        layer.confirm("确认要取消任务吗，取消后不能恢复", { title: "取消确认" }, function(index) {
            $.ajax({
                type: 'POST',
                url: "<?php echo url('cancel_special'); ?>",
                data: {
                    'id': id,
                },
                headers: {
                    'User-Token-Csrf': "<?php echo systoken('admin/users/cancel_special'); ?>"
                },
                success: function(res) {
                    layer.msg(res.info, { time: 2500 });
                    location.reload();
                }
            });
        }, function() {});
    }
    function del_user(id) {
        layer.confirm("确认要删除吗，删除后不能恢复", { title: "删除确认" }, function(index) {
            $.ajax({
                type: 'POST',
                url: "<?php echo url('delete_user'); ?>",
                data: {
                    'id': id,
                },
                headers: {
                    'User-Token-Csrf': "<?php echo systoken('admin/users/delete_user'); ?>"
                },
                success: function(res) {
                    layer.msg(res.info, { time: 2500 });
                    location.reload();
                }
            });
        }, function() {});
    }
    </script><script>
    var table = layui.table;
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