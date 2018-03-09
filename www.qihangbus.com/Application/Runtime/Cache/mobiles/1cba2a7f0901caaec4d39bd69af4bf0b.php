<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>订单详情</title>
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
</head>
<body>

<div class="wrap2"> 

 <div class="order_detail_t"><span>订单时间</span></div>
 <ul class="ul_form ul_form_js ">
	<li><span class="s_label">下单时间</span><div class="d_text"><?php echo (date("Y-m-d H:i:s",$info["add_time"])); ?></div></li>
	<li><span class="s_label">订单编号</span><div class="d_text"><?php echo ($info["order_sn"]); ?></div></li>
	<li><span class="s_label">订单状态</span><div class="d_text color1"><?php echo ($order_status); ?> <?php echo ($shipping_status); ?> <?php echo ($pay_status); ?></div> </li>
</ul>	
<div class="line_hr"></div>
<div class="order_detail_t"><span>物流信息</span></div>

<ul class="kc_ul clearfix">
		<?php if(invoice != ''): if(is_array($invoice)): $i = 0; $__LIST__ = $invoice;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_c"><i></i> <span><?php echo ($vo["context"]); ?>【<?php echo ($vo["time"]); ?>】</span></li><?php endforeach; endif; else: echo "" ;endif; ?>	
		<?php else: ?>
		<li class="li_c"><i></i> <span>暂无物流信息</span></li><?php endif; ?>
	</ul>
	
<div class="line_hr"></div>
<div class="order_detail_t"><span>商品信息</span></div>

 <ul class="cart_list ">
<?php if(is_array($goods_list)): $i = 0; $__LIST__ = $goods_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><!-- <a href="<?php echo U('mobile.php/Book/book_info',array('book_id'=>$vo['book_id'],'user_id'=>$user_id,'user_flag'=>$user_flag));?>"> -->
	<a href="">
	<li class="li_cart">
		<dl class="cart_pro">
			<dt><img src="<?php echo ($vo["product_thumb"]); ?>" alt="" /></dt>
			<dd class="dd_pname3">
			<p><?php echo ($vo["book_name"]); ?></p>
			<div class="sp_price"> <span class="s_price"><?php echo ($vo["points_price"]); ?></span> 金豆<br> × <?php echo ($vo["book_number"]); ?></div>
			</dd>
		</dl>
	</li>
	</a><?php endforeach; endif; else: echo "" ;endif; ?>
	
	
	

</ul>

	<div class="line_hr"></div>
	<div class="order_detail_t"><span>订单金额</span></div>
 <ul class="ul_form ul_form_js">
	<li><span class="s_label">总额</span><div class="d_text"><span class="s_price"><?php echo ($info["book_amount"]); ?></span> 元</div></li>
	<li><span class="s_label">运费</span><div class="d_text">￥10</div></li>
	<li><span class="s_label">实付金额</span><div class="d_text "><span class="s_price"><?php echo ($info["order_amount"]); ?></span> 元</div> </li>
</ul>
	<div class="line_hr"></div>
	<div class="order_detail_t"><span>收货信息</span></div>
 <ul class="ul_form ul_form_js">
	<li><span class="s_label">收货人</span><div class="d_text"><?php echo ($info["consignee"]); ?></div></li>
	<li><span class="s_label">手机号码</span><div class="d_text"><?php echo ($info["mobile"]); ?></div></li>
	<li><span class="s_label">收货地址</span><div class="d_text color1"><?php echo ($info["address"]); ?></div> </li>
</ul>
 <a href="<?php echo ($url); ?>">
<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
</a> 
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script type="text/javascript">
	

</script>
</body>
</html>