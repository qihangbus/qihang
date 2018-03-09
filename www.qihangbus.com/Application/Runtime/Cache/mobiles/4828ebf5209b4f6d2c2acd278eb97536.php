<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>我的订单</title>
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
<style type="text/css">
.i_tit{background:#FFF !important;color:#333!important;}
.i_ic {
    padding-left: 20px!important;
}
</style>
</head>
<body>

<div class="wrap2"> 
	<div class="head-cate">
		<ul class="ul_3">
			<li <?php if($status == 1): ?>class="li_cur"<?php endif; ?>><a href="<?php echo U('mobile.php/order/index',array('user_id'=>$user_id,'status'=>1,'user_flag'=>$user_flag));?>"><span><i class="i_ic i_ic_fk">待付款</i> </span></a></li>
			<li <?php if($status == 2): ?>class="li_cur"<?php endif; ?>><a href="<?php echo U('mobile.php/order/index',array('user_id'=>$user_id,'status'=>2,'user_flag'=>$user_flag));?>"><span><i class="i_ic i_ic_sh">待收货</i></span></a></li>
			<li <?php if($status == 99): ?>class="li_cur"<?php endif; ?>><a href="<?php echo U('mobile.php/order/index',array('user_id'=>$user_id,'status'=>99,'user_flag'=>$user_flag));?>"><span><i class="i_ic i_ic_all">全部订单</i></span></a></li>
		</ul>
	</div>
	
	<div class="line_hr"></div>
	<ul class="cart_list order_list">
		<?php if(empty($list)): ?><div class="data-empty">
			<p><img src="/Public/images/mobiles/empty.png"><p>
			<p>暂无数据</p>
			</div><?php endif; ?>
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["order_amount"] > '0'): ?><li class="li_cart" style="margin-bottom:0px;">
			
			
			<div class="i_tit">下单时间 <span class="color4"><?php echo (date("Y-m-d H:i:s",$vo["add_time"])); ?></span></div>
			<?php if(is_array($vo["goods_list"])): $i = 0; $__LIST__ = $vo["goods_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gl): $mod = ($i % 2 );++$i;?><dl class="cart_pro" style="border-bottom: 1px solid #f2f2f2;padding:0;height:85px;">
				
			<dt><img src="<?php echo ((isset($gl["img_url"]) && ($gl["img_url"] !== ""))?($gl["img_url"]):'/Public/images/mobiles/nodata.png'); ?>" alt="" /></dt>
			
			<dd class="dd_pname2">
			<p><b>商品名称 </b><span class="color1"><?php echo ($gl["product_name"]); ?></span></p>
 			<p><b>商品金额 </b><span class="s_price"><?php echo ($gl["product_price"]); ?></span> 金豆</p>
			<p style="padding-left:25%">×<?php echo ($gl["num"]); ?>本</p>
			</dd>
			
			</dl><?php endforeach; endif; else: echo "" ;endif; ?>
			<div class="i_tit" style="margin-bottom:0px;padding-bottom:0px;">
			订单编号 <span class="color4"><?php echo ($vo["order_sn"]); ?></span>
			<label style="display:block;">订单金额 <span class="s_price"><?php echo ($vo["order_amount"]); ?></span> 元</label>
			<label style="display:block;">订单状态 <span class="color1"><?php echo ($vo["order_status_name"]); ?> <?php echo ($vo["shipping_status_name"]); ?> <?php echo ($vo["pay_status_name"]); ?></span>
			<a href="javascript:void(0);" oid="<?php echo ($vo["order_id"]); ?>" class="ibtn_1 ibtn" id="ibtn">收货</a>
			<a href="<?php echo U('mobile.php/Order/order_info',array('order_id'=>$vo['order_id']));?>" style="float:right;" class="ibtn_1">订单详情</a>
			</label>
			
			</div>
			
		</li>
		<div class="line_hr"></div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
	</ul>

 <a href="<?php echo ($url); ?>">
<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
</a> 
 </div>
   <script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
	$.each($(".ck_list"),function(b, c) {
		$.each($(c).find(".li_cart"),function(b2, c2) {
			$(c2).click(function(event){
				$(this).toggleClass("li_cart_ck");
			});	
		});
	});

		$(".ibtn").click(function(){
		var order_id = $(this).attr("oid");
		alert(order_id);
		  layer.open({
    		content: '确认收货？'
   			,btn: ['确定', '取消']
    		,yes: function(index){
    		$.post("<?php echo U('mobile.php/Order/receive');?>",{order_id:order_id},function(result){
    			if(result>0){
    				location.reload();
    			}
    		});

      		// location.reload();
      		layer.close(index);
    		}
  		});
	});
})
</script>
</body>
</html>