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
<div class="wrap2">
	<div class="address_t"><a href="javascript:history.back(-1);"><span>返回</span></a></div>
	<div class="line_hr"></div>
	
	<ul class="cart_list">
		<?php if(empty($list)): ?><div class="data-empty">
			<p><img src="/Public/images/mobiles/empty.png"><p>
			<p>暂无数据</p>
		</div><?php endif; ?>
	    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart" style="padding:10px 5px;">
			<dl class="cart_pro" style="margin:0px;">
				<a href="#">
				<dt class=""><img src="<?php echo ($vo['book_thumb']); ?>" alt="" /></dt>
				<dd class="dd_pname2 p_padr" style="padding-top:20px;">
					<p><b><?php echo ($vo['book_name']); ?></b></p>
				</dd>
				</a>
			</dl>
		</li><?php endforeach; endif; else: echo "" ;endif; ?>
	</ul>

	<a href="<?php echo U('mobile.php/AIndex/index',array('id'=>$info.id));?>">
	<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
	</a>
</div>
</body>
</html>