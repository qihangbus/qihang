<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="renderer" content="webkit">
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/iconfont.css"/> 
    <title>个人信息</title>
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
	<div class="address_t"><a href="<?php echo U('mobile.php/AIndex/index',array('id'=>$info['id']));?>"><span>返回</span></a></div>
	<div class="line_hr"></div>
	
	<ul class="ul_form ul_form_jz">
		<li><span class="s_label">账户金额</span><span class="s_input "><?php echo ($info["account_money"]); ?>元 <a href="<?php echo U('agency.php/Account/detail',array('id'=>$info['id']));?>" style="color:#aee078;">查看详情</a></span></li>
	</ul>	

	<form name="myform" id="myform" action="<?php echo U('agency.php/AIndex/student_handle');?>" method="post" onsubmit="return check(this);">
		<ul class="ul_form ul_form_jz">
			<li><span class="s_label">提现金额</span><span class="s_input "><input type="text" name="" value="" placeholder="请输入提现金额"></span></li>
		</ul>	

		<br><br><br>
		<div class="btn_box btn_boxadd"><a href="javascript:void(0);" id="btn_save" class="btn btn2 btn_addjz">确定</a></div>

	</form>
	
	<a href="<?php echo U('mobile.php/AIndex/index',array('id'=>$info['id']));?>">
	<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
	</a>
	
</div>

<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
</body>
</html>