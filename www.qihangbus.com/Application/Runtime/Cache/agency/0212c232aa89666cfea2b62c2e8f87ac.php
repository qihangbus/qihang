<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="renderer" content="webkit">
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/iconfont.css"/> 
    <title>账户详情</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
    <style type="text/css">
       .d_signin{margin-top: 25px;} 
    </style>
</head>
<body>
<div class="wrap2 have-bottom-swiper">
	<div class="address_t"><a href="<?php echo U('mobile.php/Account/index',array('id'=>$id));?>"><span>返回</span></a></div>
		<div class="line_hr"></div>
		<div class="ul_form ul_form_jz">
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div style="border-bottom:1px solid #f2f2f2;padding:10px 0px;padding-left:15px;"><span class="s_label"><font style="color:#aee078;"><?php echo (date("Y-m-d H:i:s",$vo["add_time"])); ?></font> <strong><?php if($vo['log_type'] == 1): ?>增加 <?php elseif($vo['log_type'] == 2): ?>减少<?php endif; ?></strong></span><span class="s_input "><font style="color:#aee078;"><?php echo ($vo["money"]); ?></font>元 </span><p><?php echo ($vo["remark"]); ?></p></div><?php endforeach; endif; else: echo "" ;endif; ?>
		</div>	
	<a href="<?php echo U('mobile.php/AIndex/index',array('id'=>$id));?>">
	<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
	</a>
	
</div>

<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
</body>
</html>