<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>学生信息</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body>

<div class="wrap2"> 
  <div class="head-cate" <?php if($f > '0'): ?>style="height:84px;"<?php endif; ?>>
  		<ul class="ul_3">
  			<input type="hidden" id="types" value="<?php echo ($type); ?>"/>
  			<?php if(is_array($grade_list)): $i = 0; $__LIST__ = $grade_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if($type == $vo['grade_id']): ?>class="li_cur"<?php endif; ?> type="<?php echo ($vo["grade_id"]); ?>">
  				<a href="<?php echo U('mobile.php/Student/index',array('user_flag'=>4,'admin_id'=>$admin_id,'id'=>$id,'type'=>$vo['grade_id']));?>">
  				<span><?php echo ($vo["grade_name"]); ?></span>
  				</a>
  			</li><?php endforeach; endif; else: echo "" ;endif; ?>
  		</ul>
  </div>
<div class="line_hr"></div>
	
	<ul class="cart_list list_banj">
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart">
                <dl class="cart_pro">
                    <dt class="dt_tx"><img src="<?php echo ((isset($vo['student_avatar']) && ($vo['student_avatar'] !== ""))?($vo['student_avatar']):'/Public/images/mobiles/default.png'); ?>" alt="" /></dt>
                    <dd class="dd_pname2 p_padr">
                        <p class=" p_name"><?php echo ($vo['student_name']); ?></p>
                    </dd>
                    <dd class="dd_r" style="margin:5px 0 0 0">
                        <a href="<?php echo U('mobile.php/Student/readhistory',array('student_id'=>$vo['student_id'],'id'=>$id,'admin_id'=>$admin_id));?>" class="ibtn_1">借阅历史</a>
                    </dd>
                </dl>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
        <div style="vertical-align:central"></div>
    </ul>	

  <a href="<?php echo ($url); ?>">
<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
</a>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
	$("#ageid").click(function(){
		$("#c_nianl").hide();
	});
	$("#cateidcancel").click(function(){
		$("#c_nian2").hide();
	});

	$("#agecf").click(function(){
		var age = $("#age").val();
		var type = $("#types").val();

		window.location.href="<?php echo U('mobile.php/Mybag/index');?>&type="+type+"&age="+age;	
	});

	$("#catecf").click(function(){
		var cateid = $("#cateid").val();
		var type = $("#types").val();
		var uid = "<?php echo ($user_id); ?>";
		var uflag = "<?php echo ($user_flag); ?>";
		window.location.href="<?php echo U('mobile.php/Mybag/index');?>&type="+type+"&cateid="+cateid+"&user_id="+uid+"&user_flag="+uflag;	
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