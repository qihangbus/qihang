<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="renderer" content="webkit">
    <title>班级管理</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
    <link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
    <script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
    <style type="text/css">
        .head-cate .ul_2 li{width: 25%}
    </style>
</head>
<body>
<div class="wrap2">
    <div class="head-cate">
        <ul class="ul_2">
            <li <?php if(($type) == "1"): ?>class="li_cur"<?php endif; ?>>
            <a href="<?php echo U('mobile.php/TClass/payStu',['teacher_id'=>$teacher_id,'type'=>1]);?>">
                <span>已订购(<?php echo ($count1); ?>)</span>
            </a>
            </li>
            <li <?php if(($type) == "2"): ?>class="li_cur"<?php endif; ?>>
            <a href="<?php echo U('mobile.php/TClass/payStu',['teacher_id'=>$teacher_id,'type'=>2]);?>">
                <span>未订购(<?php echo ($count2); ?>)</span>
            </a>
            </li>
            <li>
                <a href="<?php echo U('mobile.php/TClass/regStu',['teacher_id'=>$teacher_id,'type'=>1]);?>">
                    <span>已注册(<?php echo ($count3); ?>)</span>
                </a>
            </li>
            <li>
                <a href="<?php echo U('mobile.php/TClass/regStu',['teacher_id'=>$teacher_id,'type'=>2]);?>">
                    <span>未注册(<?php echo ($count4); ?>)</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="line_hr"></div>
    <div id="div1">
        <ul class="cart_list list_banj">
            <?php if(empty($list)): ?><div class="data-empty">
                    <p><img src="/Public/images/mobiles/empty.png"><p>
                    <p>暂无数据</p>
                </div><?php endif; ?>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart" style="margin-bottom:0px;padding-top:10px;">
                    <dl class="cart_pro">
                        <dt class="dt_tx">
                            <img src="<?php echo ((isset($vo['student_avatar']) && ($vo['student_avatar'] !== ""))?($vo['student_avatar']):'/Public/images/mobiles/default.png'); ?>" alt="" />
                        </dt>
                        <dd class="dd_pname2 p_padr">
                            <p class=" p_name"><?php echo ($vo['student_name']); ?></p>
                        </dd>
                    </dl>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
            <div style="vertical-align:central"></div>
        </ul>
    </div>
    <a href="<?php echo U('mobile.php/TIndex/index',array('teacher_id'=>$teacher_id));?>">
        <span class="f_index" style="bottom:15px;"><span class="iconfont icon-shouyeshouye"></span></span>
    </a>
</div>
</body>
</html>