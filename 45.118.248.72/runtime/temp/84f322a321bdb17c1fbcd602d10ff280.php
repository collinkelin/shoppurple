<?php /*a:2:{s:65:"/www/wwwroot/45.118.248.72/application/admin/view/menu/index.html";i:1580959096;s:59:"/www/wwwroot/45.118.248.72/application/admin/view/main.html";i:1593205496;}*/ ?>
<div class="layui-card layui-bg-gray"><?php if(!(empty($title) || (($title instanceof \think\Collection || $title instanceof \think\Paginator ) && $title->isEmpty()))): ?><div class="layui-card-header layui-anim layui-anim-fadein notselect"><span class="layui-icon layui-icon-next font-s10 color-desc margin-right-5"></span><?php echo htmlentities((isset($title) && ($title !== '')?$title:'')); ?><div class="pull-right"><?php if(auth("add")): ?><button data-modal='<?php echo url("add"); ?>' data-title="添加菜单" class='layui-btn layui-btn-sm layui-btn-primary'>添加菜单</button><?php endif; if(auth("remove")): ?><button data-action='<?php echo url("remove"); ?>' data-csrf="<?php echo systoken('remove'); ?>" data-rule="id#{key}" class='layui-btn layui-btn-sm layui-btn-primary'>删除菜单</button><?php endif; ?></div></div><?php endif; ?><div class="layui-card-body layui-anim layui-anim-upbit"><div class="think-bg-white"><?php if(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty())): ?><blockquote class="layui-elem-quote">没 有 记 录 哦！</blockquote><?php else: ?><table class="layui-table" lay-skin="line"><thead><tr><th class='list-table-check-td think-checkbox'><input data-auto-none data-check-target='.list-check-box' type='checkbox'></th><th class='list-table-sort-td'><button type="button" data-reload class="layui-btn layui-btn-xs">刷 新</button></th><th class='text-center' style="width:30px"></th><th style="width:180px"></th><th class='layui-hide-xs' style="width:180px"></th><th colspan="2"></th></tr></thead><tbody><?php foreach($list as $key=>$vo): ?><tr data-dbclick><td class='list-table-check-td think-checkbox'><input class="list-check-box" value='<?php echo htmlentities($vo['ids']); ?>' type='checkbox'></td><td class='list-table-sort-td'><input data-action-blur="<?php echo request()->url(); ?>" data-value="id#<?php echo htmlentities($vo['id']); ?>;action#sort;sort#{value}" data-loading="false" value="<?php echo htmlentities($vo['sort']); ?>" class="list-sort-input"></td><td class='text-center'><i class="<?php echo htmlentities($vo['icon']); ?> font-s18"></i></td><td class="nowrap"><span class="color-desc"><?php echo $vo['spl']; ?></span><?php echo htmlentities($vo['title']); ?></td><td class='layui-hide-xs'><?php echo htmlentities($vo['url']); ?></td><td class='text-center nowrap'><?php if($vo['status'] == '0'): ?><span class="color-red">已禁用</span><?php else: ?><span class="color-green">使用中</span><?php endif; ?></td><td class='text-center nowrap notselect'><?php if(auth("add")): ?><span class="text-explode">|</span><!--<?php if($vo['spt'] < 2): ?>--><a class="layui-btn layui-btn-xs layui-btn-primary" data-title="添加子菜单" data-modal='<?php echo url("add"); ?>?pid=<?php echo htmlentities($vo['id']); ?>'>添 加</a><!--<?php else: ?>--><a class="layui-btn layui-btn-xs layui-btn-disabled">添 加</a><!--<?php endif; ?>--><?php endif; if(auth("edit")): ?><a data-dbclick class="layui-btn layui-btn-xs" data-title="编辑菜单" data-modal='<?php echo url("edit"); ?>?id=<?php echo htmlentities($vo['id']); ?>'>编 辑</a><?php endif; if($vo['status'] == 1 and auth("forbid")): ?><a class="layui-btn layui-btn-warm layui-btn-xs" data-confirm="确定要禁用菜单吗？" data-action="<?php echo url('forbid'); ?>" data-value="id#<?php echo htmlentities($vo['ids']); ?>;status#0" data-csrf="<?php echo systoken('forbid'); ?>">禁 用</a><?php elseif(auth("resume")): ?><a class="layui-btn layui-btn-warm layui-btn-xs" data-action="<?php echo url('resume'); ?>" data-value="id#<?php echo htmlentities($vo['ids']); ?>;status#1" data-csrf="<?php echo systoken('resume'); ?>">启 用</a><?php endif; if(auth("remove")): ?><a class="layui-btn layui-btn-danger layui-btn-xs" data-confirm="确定要删除数据吗?" data-action="<?php echo url('remove'); ?>" data-value="id#<?php echo htmlentities($vo['ids']); ?>" data-csrf="<?php echo systoken('remove'); ?>">删 除</a><?php endif; ?></td></tr><?php endforeach; ?></tbody></table><?php endif; ?></div></div></div>