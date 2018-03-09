<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>订单支付</title>
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
</head>
<body>

<div class="wrap2"> 
 <ul class="ul_form ul_form_js border_b">
	<li><span class="s_label">支付方式</span><div class="d_text">

	<?php if($integral_amount >= $order_amount): ?>金豆支付
	<?php else: ?>微信支付<?php endif; ?>

	</div></li>
</ul>	
 <ul class="ul_form ul_form_js">
	<li class="li_orderno"><span class="s_label">订单编号</span><div class="d_text"><?php echo ($info["order_sn"]); ?></div></li>
</ul>


 <ul class="ul_form ul_form_js">
	<li><span class="s_label color2">订单总金额</span><div class="d_text t_r color2"><?php echo ($order_amount); ?>元</div></li>

	<?php if($is_cell == 0): ?><li><span class="s_label">+运费</span><div class="d_text t_r color3"><?php echo ($shipping_amount); ?>元</div></li><?php endif; ?>

	<li><span class="s_label">-金豆支付</span><div class="d_text t_r color3"><?php echo ($integral_amount); ?>元</div></li>

	<?php if($integral_amount < $order_amount): ?><li><span class="s_label color2">还需支付</span><div class="d_text t_r color2"><?php echo ($orderamount); ?>元</div></li><?php endif; ?>

	<br>
	
	<?php if($integral_amount >= $order_amount): ?><div class="btn_box btn_boxadd"><a href="<?php echo U('order/ok1',array('user_id'=>$user_id,'rec_id'=>$rec_id,'user_flag'=>$user_flag,'order_id'=>$order_id));?>" class="btn btn2" >金豆支付</a></div>
	<?php else: ?>
	<div class="btn_box btn_boxadd"><?php echo ($jsapi); ?></div><?php endif; ?>
	<br>
</ul>

 <a href="<?php echo ($url); ?>">
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