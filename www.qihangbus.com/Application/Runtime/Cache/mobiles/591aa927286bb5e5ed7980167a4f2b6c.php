<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>班级书架</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body>

<div class="wrap2"> 
<div class="c_t">
  <div class="head-cate">
  		<ul class="ul_3">
  			<input type="hidden" id="types" value="<?php echo ($type); ?>"/>
  			<input type="hidden" id="uid" value="<?php echo ($user_id); ?>"/>
  			<input type="hidden" id="uflag" value="<?php echo ($user_flag); ?>"/>
  			<li style="width:50%" <?php if($type == '1'): ?>class="li_cur"<?php endif; ?>>
  				<a href="<?php echo U('mobile.php/TBookshelf/index',array('teacher_id'=>$user_id,'type'=>1));?>">
  				<span>未借阅绘本(<?php echo ($in_num); ?>)</span>
  				</a>
  			</li>
  			
  			<li style="width:50%" <?php if($type == '2'): ?>class="li_cur"<?php endif; ?>>
  				<a href="<?php echo U('mobile.php/TBookshelf/index',array('teacher_id'=>$user_id,'type'=>2));?>">
  				<span>借阅绘本(<?php echo ($out_num); ?>)</span>
  				</a>
  			</li>
  		</ul>
  </div>
  </div>

  	<ul class="cart_list">
		<?php if(empty($list)): ?><div class="data-empty">
				<p><img src="/Public/images/mobiles/empty.png"><p>
				<p>暂无数据</p>
				</div><?php endif; ?>
	    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart">
                <dl class="cart_pro">
					<a href="<?php echo U('mobile.php/TBookshelf/info',array('id'=>$vo['book_id'],'user_id'=>$user_id,'user_flag'=>2));?>">
                    <dt class=""><img src="<?php echo ($vo['book_thumb']); ?>" alt="" /></dt>
                    <dd class="dd_pname2 p_padr" style="padding-top:13px;">
						<p>
							<strong>NO.<?php echo ($vo["book_no"]); ?></strong>
						</p>
                        <p>
							<b><?php echo ($vo['book_name']); if($vo['bad'] == 1): ?><font color='red'> [图书损坏]</font><?php endif; ?></b>
						</p>
                    </dd>
					</a>
                </dl>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
	</ul>	
  <a href="<?php echo U('mobile.php/TIndex/index',array('teacher_id'=>$user_id));?>">
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