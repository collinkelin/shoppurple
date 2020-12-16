<?php /*a:2:{s:64:"/www/wwwroot/45.118.248.72/application/index/view/ctrl/help.html";i:1590340363;s:59:"/www/wwwroot/45.118.248.72/application/index/view/main.html";i:1593514237;}*/ ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="Robots" content="noindex,nofollow"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><title><?php echo sysconf('site_name'); ?></title><link rel="stylesheet" type="text/css" href="/public/css/style.min.css?v=1595228342"><link rel="stylesheet" type="text/css" href="/public/css/ui.min.css?v=1595228342"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><link rel="stylesheet" type="text/css" href="/res/common/css/hui.min.css?v=1595228342"><link rel="stylesheet" type="text/css" href="/statics/css/userstyle.min.css?v=1595228342"><link rel="stylesheet" type="text/css" href="/statics/css/base.min.css?v=1595228342"><link rel="stylesheet" type="text/css" href="/statics/css/user.min.css?v=1595228342"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><style>
.data_item {
    width: 90%;
    margin: auto;
    display: flex;
    height: 2rem;
    line-height: 2rem;
    flex-wrap: wrap;
    justify-content: space-between;
}

a:link,a:visited{ 
    text-decoration:none;  /*超链接无下划线*/ 
} 
a:hover{ 
    text-decoration:underline;  /*鼠标放上去有下划线*/ 
} 

.head {
    width: 1.3rem;
    height: 1.3rem;
    border-radius: 50%;
    overflow: hidden;
    margin: auto 1rem auto auto;
}

.right {
    width: 1.2rem;
    height: 1.7rem;
    margin: auto 0 auto 0;
}
.right i {
    font-size: 1rem;
}

</style></head><body><header><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('Help center'); ?></span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><section><div class="container" style="padding-top: 2.5rem;"><div class="article-box"><?php foreach($list as $key=>$vo): if(!(empty($vo['url']) || (($vo['url'] instanceof \think\Collection || $vo['url'] instanceof \think\Paginator ) && $vo['url']->isEmpty()))): ?><a href="<?php echo htmlentities($vo['url']); ?>" class="article-list" target="_blank"><h3><?php echo htmlentities($vo['title']); ?></h3><em class="remind"></em></a><?php endif; ?><?php endforeach; ?></div><?php foreach($list as $key=>$vo): if(!(empty($vo['url']) || (($vo['url'] instanceof \think\Collection || $vo['url'] instanceof \think\Paginator ) && $vo['url']->isEmpty()))): ?><!-- <a href="<?php echo htmlentities($vo['url']); ?>" class="data_item" id="head" target="_blank"><div class="form_title"><?php echo htmlentities($vo['title']); ?></div><div class="right"><i class="fa fa-chevron-right" aria-hidden="true"></i></div></a> --><?php endif; ?><?php endforeach; ?></div></section><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="https://cdn.jsdelivr.net/npm/dot@1.1.3/doT.min.js"type="text/javascript" ></script><script src="https://cdn.jsdelivr.net/npm/cleave.js@1.5.10/dist/cleave.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595228342"></script><script src="/public/js/common.min.js?v=1595228342"></script><script src="/public/js/city.js"></script><script src="/public/js/picker.min.js"></script><script></script></body></html>