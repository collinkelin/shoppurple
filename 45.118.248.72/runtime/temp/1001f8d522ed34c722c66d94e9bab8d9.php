<?php /*a:2:{s:74:"/www/wwwroot/45.118.248.72/application/index/view/ctrl/deposit_before.html";i:1589470521;s:59:"/www/wwwroot/45.118.248.72/application/index/view/main.html";i:1593514237;}*/ ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="Robots" content="noindex,nofollow"><meta http-equiv="X-UA-Compatible" content="ie=edge"><meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" /><title><?php echo sysconf('site_name'); ?></title><link rel="stylesheet" type="text/css" href="/public/css/style.min.css?v=1595228348"><link rel="stylesheet" type="text/css" href="/public/css/ui.min.css?v=1595228348"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/need/layer.css"><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css"><style>
    .form {
            color: rgb(240, 98, 96);
            display: flex;
            justify-content: space-between;
            flex-direction: row;
            padding: 0 .5rem;
            border-bottom: 1px solid #eeeeee;
            min-height: 60px;
        }

        .form>input {
            border: none;
            outline: none;
            margin-right: auto;
            color: rgb(240, 98, 96);
            margin-left: 1rem;
            font-size: 0.8rem;
            width:70%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .form_title {
            font-size: .5rem;
            width: 10%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form_title img {
            height: auto;
        }

        .form_title i {
            font-size: 30px;
        }

        .copy_btn {
            color: #60aef0;
            width: 10%;
            text-align: center;
            height: 2rem;
            line-height: 2rem;
        }

        .wrin{
            padding:0 .3rem;
            color:red;
            width:90%;
            margin:.5rem auto;
        }
    </style></head><body><header><a class="back" href="javascript:history.go(-1);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><span><?php echo lang('Choose withdrawal method'); ?></span><a class="back" href="/"><i class="fa fa-home" aria-hidden="true"></i></a></header><section><div class="container"><div class="form_box"><?php foreach($pay as $key=>$vo): ?><a href="<?php echo url($vo['url'],['id' => $vo['id']]); ?>" class="form"><div class="form_title"><?php if($vo['ico_type'] == '1'): ?><?php echo $vo['ico']; else: ?><img src="<?php echo htmlentities($vo['ico']); ?>" alt="" style="width: 40px; height:40px"><?php endif; ?></div><input type="text" id="bank_name" value="<?php echo htmlentities($vo['name']); ?>" readonly><div class="copy_btn">></div></a><?php endforeach; ?><p><?php echo lang('Withdrawal amount limit'); ?></p></div><p class="wrin"></p></div></section><script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/chlayer@3.1.5/dist/mobile/layer.js"></script><script src="https://cdn.jsdelivr.net/npm/dot@1.1.3/doT.min.js"type="text/javascript" ></script><script src="https://cdn.jsdelivr.net/npm/cleave.js@1.5.10/dist/cleave.min.js" type="text/javascript"></script><script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js" type="text/javascript"></script><script src="/public/js/ui.js?v=1595228348"></script><script src="/public/js/common.min.js?v=1595228348"></script><script src="/public/js/city.js"></script><script src="/public/js/picker.min.js"></script></body></html>