<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>订单确认</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body>

<?php if($product_info['is_cell'] == 1): ?><div class="wrap2"> 
 <a href="<?php echo U('mobile.php/Order/address/',array('user_id'=>$user_id,'user_flag'=>$user_flag,'recid'=>$rec_id));?>">
 <div class="jies_t">
 	
	 	<?php if($address_info): ?><span><?php echo ($address_info["consignee"]); ?> <?php echo ($address_info["address"]); ?> <?php echo ($address_info["mobile"]); ?></span>	
	 	<?php else: ?>
	 	<span>添加收货地址</span><?php endif; ?>
 </div>
 </a>
 <div class="line_hr"></div>
 <ul class="ul_form ul_form_js border_b">
	<li><span class="s_label">支付方式</span><div class="d_text">线上支付</div></li>
	<li><span class="s_label">配送方式</span><div class="d_text">快递配送</div></li>
	<li style="display:none;"><span class="s_label">发票信息</span><div class="d_invoice" style="padding-left:80px;">无</div><span class="s_edit">修改</span></li>
	<li class="invoice_info " style=" display:none;"><span class="s_label">发票抬头</span><div class="d_text"><input type="text" style="float:left;" name="invoice_name" id="invoice_name" value=""/><a href="javascript:void(0);" class="invoice_btn" style="border: 1px solid #ccc;height: 23px;width: 45px;float: left;line-height: 23px;display: block;padding: 0 5px;margin-left: 10px;text-align: center;">确认</a></div></li>
 <ul class="ul_form ul_form_js">
	<li class="li_orderno"><span class="s_label">订单编号</span><div class="d_text"><?php echo ($order_sn); ?></div></li>
</ul>
	<div class="line_hr"></div>
	 <div class="jies_t jies_t2"><span>商品清单：共<?php echo ($cart_number); ?>件商品</span></div>
<div class="line_hr"></div>
	 <!--cc-->
	  <ul class="cart_list " style=" display:none;">
	<?php if(is_array($goods_list)): $i = 0; $__LIST__ = $goods_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart" style="height:95px;line-height:95px;">
		<dl class="cart_pro">
		<dt><img src="<?php echo ($vo["thumb"]); ?>" alt="" /></dt>
		<dd class="dd_pname3">
		<p><?php echo ($vo["book_name"]); ?></p>
		<div class="sp_price"> <span class="s_price"><?php echo ($vo["points_price"]); ?></span> 金豆 × <?php echo ($vo["book_number"]); ?></div>
		</dd>
		</dl>
		</li><?php endforeach; endif; else: echo "" ;endif; ?>
	
	
<div class="line_hr"></div>
</ul>

	  <!--//cc--><?php endif; ?>



 <ul class="ul_form ul_form_js">
	<li><span class="s_label">商品原价</span><div class="d_text t_r color3"><?php echo ($cart_amount); ?>元</div></li>

<?php if($is_cell == 0): ?><li><span class="s_label">+运费</span><div class="d_text t_r color3"><?php echo ($shipping_amount); ?>元</div></li><?php endif; ?>

	<li><span class="s_label">-金豆支付</span><div class="d_text t_r color3"><?php echo ($integral_amount); ?>元</div></li>
	<li><span class="s_label color2">订单总金额</span><div class="d_text t_r color2"><?php echo ($order_amount); ?>元</div></li>
	<br>

	<div class="btn_box btn_boxadd"><a href="javascript:void(0);" class="btn btn2 checkout">去结算</a></div>
	<br>
</ul>

 <a href="<?php echo ($url); ?>">
<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
</a> 
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script type="text/javascript">
	
$(function(){
	$(".checkout").click(function(){
		var uid = "<?php echo ($user_id); ?>";
		var uflag = "<?php echo ($user_flag); ?>";
		var invoicename = $("#invoice_name").val();
		var recid = "<?php echo ($rec_id); ?>";
		var integral_amount = "<?php echo ($integral_amount); ?>";
		var order_amount = "<?php echo ($order_amount); ?>";

		var is_cell = "<?php echo ($is_cell); ?>";
		if(is_cell != 1){
			is_cell = 0;
		}
		// alert(uid);
		// alert(uflag);
		// alert(recid);
		// alert(is_cell);
		//location.href="<?php echo U('mobile.php/Order/done/user_id/"+uid+"/user_flag/"+uflag+"/invoice/"+invoicename+"');?>";
		if(integral_amount >= order_amount)
		{
			//金豆支付
			location.href="/mobile.php?m=mobile.php&c=Order&a=done1&user_id="+uid+"&user_flag="+uflag+"&invoice="+invoicename+"&recid="+recid+"&is_cell="+is_cell;
		}else
		{
			//微信支付
			location.href="/mobile.php?m=mobile.php&c=Order&a=done&user_id="+uid+"&user_flag="+uflag+"&invoice="+invoicename+"&recid="+recid+"&is_cell="+is_cell;
		}	
	});

	$(".jies_t2").click(function(){
		$(this).toggleClass("jies_t2");
		$(".cart_list").toggle();
	})
	
	$(".invoice_btn").click(function(){
		$(".invoice_info").hide();
		$(".d_invoice").html($("#invoice_name").val());
	});

	$(".s_edit").click(function(){
		$(this).toggleClass(".s_edit");
		$(".invoice_info").toggle();
		$("#invoice_name").focus();
	})
})
</script>
</body>
</html>