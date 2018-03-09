<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>订单支付</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body>

<div class="wrap2"> 
 <ul class="ul_form ul_form_js border_b">
	<li><span class="s_label">支付方式</span><div class="d_text">微信支付</div></li>
</ul>	
 <ul class="ul_form ul_form_js">
	<li class="li_orderno"><span class="s_label">订单编号</span><div class="d_text"><?php echo ($info["order_sn"]); ?></div></li>
</ul>


 <ul class="ul_form ul_form_js">
	<li><span class="s_label color2">订单总金额</span><div class="d_text t_r color2"><?php echo ($binfo["shop_price"]); ?>元</div></li>
	<li><span class="s_label color2">还需支付</span><div class="d_text t_r color2"><?php echo ($binfo["shop_price"]); ?>元</div></li>
	<br>

	<div class="btn_box btn_boxadd"><?php echo ($jsapi); ?></div>
	<br>
</ul>

 <a href="<?php echo U('mobile.php/Ucenter/index');?>">
<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
</a> 
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script type="text/javascript">
	
$(function(){
	$(".jies_t2").click(function(){
		$(this).toggleClass("jies_t2");
		$(".cart_list").toggle();
	})
})
</script>
</body>
</html>