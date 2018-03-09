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
		<span class="iconfont icon-dadou"></span>当前可用金豆<b class="color2"><?php echo ($gold); ?></b>颗<a href="<?php echo U('mobile.php/Ucenter/earn_log',array('user_id'=>$user_id,'user_flag'=>$user_flag));?>" class="ibtn_1">收益记录</a>
	</div>
 	<div class="line_hr"></div>
 </div>

  
  
 <a href="<?php echo U('mobile.php/Ucenter/index');?>">
<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
</a> 
 </div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script type="text/javascript">
// <a href="<?php echo U('mobile.php/Ucenter/index');?>">
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