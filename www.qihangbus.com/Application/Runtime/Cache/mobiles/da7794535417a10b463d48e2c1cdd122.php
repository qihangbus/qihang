<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>我的收益</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
</head>
<body>

<div class="wrap2"> 
<div class="c_t">
<div class="iban"><img src="/Public/images/mobiles/ban_mx.jpg" alt="" /></div>
	<div class="mxjilu_t">
		<span class="iconfont icon-dadou"></span>当前可用金豆<b class="color2"><?php echo ($gold); ?></b>颗<a href="<?php echo U('mobile.php/TIndex/earn_log',array('user_id'=>$user_id,'user_flag'=>$user_flag));?>" class="ibtn_1">收益记录</a>
	</div>
 	<div class="line_hr"></div>
<!-- 	<div class="head-cate" style="display:none;">
 		<ul class="ul_3">
 			<li><a href="javascript:alert('正在开发,敬请期待');"><span><i class="i_ic ic_h_chou">抽奖</i> </span></a></li>
 			<li <?php if($count > '0'): ?>class="li_xx"<?php endif; ?>><a href="<?php echo U('mobile.php/Cart/index',array('user_id'=>$user_id,'user_flag'=>$user_flag));?>"><span><i class="i_ic ic_h_cart">购物车</i></span></a></li>
 			<li><a href="<?php echo U('mobile.php/Order/index',array('user_id'=>$user_id,'user_flag'=>$user_flag));?>"><span><i class="i_ic ic_h_order">订单</i></span></a></li>
 		</ul>
 	</div> -->

<!--   	<div class="head-cate">
  		<ul class="ul_3 ul-control">
  			<li style="width:50%" <?php if($type == 1): ?>class="li_cur"<?php endif; ?> data-m="c_nian2"><span>推荐商品 </span></li>
  			<li style="width:50%" <?php if($type == 2): ?>class="li_cur"<?php endif; ?> data-m="c_nian1"><span>可兑换商品</span></li>
  			<li style="width:50%" <?php if($type == 2): ?>class="li_cur"<?php endif; ?> data-m="c_nian1"><span>流量</span></li>
  			<li style="width:50%" <?php if($type == 2): ?>class="li_cur"<?php endif; ?> data-m="c_nian1"><span>话费</span></li>
  		</ul>

  		<div class="cate_menu " id="c_nian2">  
		  	<ul class="ul_fl clearfix">
		  		<input type="hidden" id="cateid" value="<?php echo ($cateid); ?>">
		  		<?php if(is_array($cate)): $i = 0; $__LIST__ = $cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if($cateid == $vo['cate_id']): ?>class="li_select"<?php endif; ?> id="<?php echo ($vo["cate_id"]); ?>"><span><?php echo ($vo["cate_name"]); ?></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
		   		<li id="0" <?php if($cateid < '1'): ?>class="li_select"<?php endif; ?>><span>全部</span></li>
		   	</ul>
	   		<div class="cate_menubtn clearfix"><a href="javascript:void(0);" id="cateidcancel" class="btn_reset">取消</a> <a href="javascript:void(0);" id="catecf" class="btn_cf">确定</a></div>
	    </div>
		
		<div class="cate_menu " id="c_nian1">  
		  	<ul class="ul_fl clearfix">
		  		<input type="hidden" id="cate_id" value="<?php echo ($cate_id); ?>">
		   		<li id="1" <?php if($cate_id == '1'): ?>class="li_select"<?php endif; ?>><span>绘本图书类</span></li>
				<li id="2" <?php if($cate_id == '2'): ?>class="li_select"<?php endif; ?>><span>其他商品类</span></li>
		   	</ul>
	   		<div class="cate_menubtn clearfix"><a href="javascript:void(0);" id="cateid2cancel" class="btn_reset">取消</a> <a href="javascript:void(0);" id="cate_cf" class="btn_cf">确定</a></div>
	    </div>
  	</div> -->
 </div>
<!--   <ul class="prolist clearfix">
	<?php if(is_array($book_list)): $i = 0; $__LIST__ = $book_list;if( count($__LIST__)==0 ) : echo "暂无数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
		<a href="<?php echo U('mobile.php/Book/book_info',array('book_id'=>$vo['book_id'],'user_id'=>$user_id,'user_flag'=>$user_flag,'user_points'=>$user_points));?>">
		<dl class="dl_pro">
			<dt><img src="<?php echo ($vo["book_thumb"]); ?>"></dt>
			<dd class="dd_name"><?php echo ($vo["book_name"]); ?></dd>
			<dd class="dd_p">[<?php echo ($vo["sub_name"]); ?>]</dd>
			<dd class="dd_price s_price"><?php echo ($vo["points_price"]); ?>金豆</dd>
			<dd class="dd_price_s" style="display:none;">市场价：<?php echo ($vo["shop_price"]); ?>元</dd>
		</dl>
		</a>
	</li><?php endforeach; endif; else: echo "暂无数据" ;endif; ?>
</ul>	 -->
  
  
 <a href="<?php echo U('mobile.php/TIndex/index',array('teacher_id'=>$user_id));?>">
<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
</a> 
 </div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script type="text/javascript">
//<a href="<?php echo U('mobile.php/Book/index',array('user_id'=>$userinfo['student_id'],'student_points'=>$userinfo['student_points'],'type'=>1,'user_flag'=>$user_flag));?>">
$(function(){
	$("#cateidcancel").click(function(){
		$("#c_nian2").hide();
	});
	
	$("#cateid2cancel").click(function(){
		$("#c_nian1").hide();
	});

	$("#catecf").click(function(){
		var cateid = $("#cateid").val();
		var uid = "<?php echo ($userinfo["student_id"]); ?>";
		var vpoints = "<?php echo ($userinfo["student_points"]); ?>";
		var uflag = "<?php echo ($user_flag); ?>";
		window.location.href="<?php echo U('mobile.php/Book/index');?>&type=1&cateid="+cateid+"&user_id="+uid+"&user_flag="+uflag+"&student_points="+vpoints;	
	});
	
	$("#cate_cf").click(function(){
		var cate_id = $("#cate_id").val();
		var uid = "<?php echo ($userinfo["student_id"]); ?>";
		var vpoints = "<?php echo ($userinfo["student_points"]); ?>";
		var uflag = "<?php echo ($user_flag); ?>";
		window.location.href="<?php echo U('mobile.php/Book/index');?>&type=2&cate_id="+cate_id+"&user_id="+uid+"&user_flag="+uflag+"&student_points="+vpoints;	
	});

	$(".ul_3 li").click(function(event){
		$(this).addClass("li_cur").siblings("li").removeClass("li_cur");
		var t = $(this).attr('type');
		$("#types").val(t);
		$(".cate_menu").hide();
		$(".h-bg").remove();
		var ccon=$(this).attr("data-m");
		  if($("#"+ccon).length){
			  $("#"+ccon).siblings(".cate_menu").hide();
			  showfbg();
			  $("#"+ccon).toggle();
			  
		  }
		 hidelayer();
		 event.stopPropagation();
	});
	$(".cate_menu .ul_fl li").click(function(event){
		$(this).addClass("li_select").siblings("li").removeClass("li_select");
		var cat_id = $(this).attr('id');
		$("#cateid").val(cat_id);
		$("#cate_id").val(cat_id);
		var age = $(this).attr('val');
		$("#age").val(age);
		event.stopPropagation();
	});
})

function showfbg(){
	$(".h-bg").remove();
	$("body .wrap2").append("<div class='h-bg'></div>")
//	if(!$(".h-bg").length){;}
}
function hidelayer(){
	$("body,.h-bg").click(function(event){
		$(".li_cur").removeClass("li_cur");
		$(".cate_menu").hide();
		$(".h-bg").remove();
		event.stopPropagation();
	});
	$(".cate_menu").click(function(event){
		event.stopPropagation();	
	});
}
</script>
</body>
</html>