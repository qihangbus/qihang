<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="renderer" content="webkit">
    <title>评价</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
	<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
    <script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script> 
</head>
<body>
    <div class="wrap2">
        <div class="head-cate">
            <ul class="ul_2">
                <li  style="width:33%" 
                    <?php if(($circul_status) == "2"): ?>class="li_cur"<?php endif; ?>>
                    <a href="<?php echo U('mobile.php/TBorrow/index',array('teacher_id'=>$teacher_id,'circul_status'=>2));?>">
                        <span>借阅 </span>
                    </a>
                </li>
                <li  style="width:34%" 
                    <?php if(($circul_status) == "1"): ?>class="li_cur"<?php endif; ?>>
                    <a href="<?php echo U('mobile.php/TBorrow/index',array('teacher_id'=>$teacher_id,'circul_status'=>1));?>">
                        <span>归还</span>
                    </a>
                </li>
                <li style="width:33%" <?php if(($circul_status) == "3"): ?>class="li_cur"<?php endif; ?>>
                    <a href="<?php echo U('mobile.php/TBorrow/evaluate',array('teacher_id'=>$teacher_id,'circul_status'=>3));?>">
                        <span>评价</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="line_hr"></div>
        <div id="div1">
            <div class="dengj_tit">已完成</div>
            <ul class="cart_list order_list ck_list finish">
                <?php if(empty($list)): ?><div class="data-empty">
					<p><img src="/Public/images/mobiles/empty.png"><p>
					<p>暂无数据</p>
					</div><?php endif; ?>
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart li_cart_ck" uid="<?php echo ($vo['student_id']); ?>">
                        <dl class="cart_pro">
                            <dt class="dt_tx"><img src="<?php echo ((isset($vo['student_avatar']) && ($vo['student_avatar'] !== ""))?($vo['student_avatar']):'Public/images/mobiles/tx.png'); ?>" alt="" /></dt>
                            <dd class="dd_pname2">
                                <p><b><?php echo ($vo['student_name']); ?> </b></p>
                            </dd>
                        </dl>
                        <div class="i_btn"></div>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
					
            </ul>
			<?php if(!empty($list)): ?><div class="footer2" style="position:relative;">
				<ul class="footer_pbtn">
				  <input type="hidden" id="user_id" value="<?php echo ($user_id); ?>">	
				  <input type="hidden" id="user_flag" value="<?php echo ($user_flag); ?>">
				  <li class="fbtn_l"><span class="pay_quan pay_quan_ed" id="a_ckall">全选</span></li>
				  <li class="fbtn_r" style="line-height:40px;"><a id="send" href="javascript:void(0);" class="ibtn_1" style="float:right;margin-top:14px;margin-right:10px;">评价</a></li>
				</ul>
			  </div><?php endif; ?>
            <div class="line_hr"></div>
			<div style="clear:both;"></div>
            <div class="dengj_tit">未完成</div>
            <ul class="cart_list order_list ck_list unfinish">
				<?php if(empty($circulation_list)): ?><div class="data-empty">
					<p><img src="/Public/images/mobiles/empty.png"><p>
					<p>暂无数据</p>
					</div><?php endif; ?>
                <?php if(is_array($circulation_list)): $i = 0; $__LIST__ = $circulation_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart li_cart_ck" style="clear:both;" uid="<?php echo ($vo['student_id']); ?>">
                        <dl class="cart_pro">
                            <dt class="dt_tx"><img src="<?php echo ((isset($vo['student_avatar']) && ($vo['student_avatar'] !== ""))?($vo['student_avatar']):'Public/images/mobiles/tx.png'); ?>" alt="" /></dt>
                            <dd class="dd_pname2">
                                <p><b><?php echo ($vo['student_name']); ?> </b></p>
                            </dd>
                        </dl>
                        <div class="i_btn"></div>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
			<?php if(!empty($circulation_list)): ?><div class="footer2" style="position:relative;">
				<ul class="footer_pbtn">
				  
				  <li class="fbtn_l"><span class="pay_quan pay_quan_ed" id="a_ckall1">全选</span></li>
				  <li class="fbtn_r" style="line-height:40px;"><a id="remind" f="$vo[flag]" href="javascript:void(0);" class="ibtn_1" style='float:right;<?php if($vo[flag] > 0): ?>background-color:#ccc;<?php endif; ?>;margin-top:14px;margin-right:10px;'>提醒</a></li>
				</ul>
			  </div><?php endif; ?>
        </div>
    </div>
      <a href="<?php echo U('mobile.php/TIndex/index',array('teacher_id'=>$teacher_id));?>">
<span class="f_index" style="bottom:40px;"><span class="iconfont icon-shouyeshouye"></span></span>
</a> 

<script type="text/javascript">
$(function(){

	$("#send").click(function(){
		var list = $(".finish li");
		var values = [];
		var key = 0;
		for (var i=0;i<list.length;i++) {
			f = list.eq(i).hasClass('li_cart_ck');
			if(f){
				values[key] = list.eq(i).attr('uid');
				key++;
			}
		};
		var tid = "<?php echo ($teacher_id); ?>";
		location.href="/mobile.php?m=mobile.php&c=TBorrow&a=task_comment&teacher_id="+tid+"&eval_type=1&circul_status=3&student_id="+values.join(',');
	});
	
	$("#remind").click(function(){
		var list = $(".unfinish li");
		var values = [];
		var key = 0;
		for (var i=0;i<list.length;i++) {
			f = list.eq(i).hasClass('li_cart_ck');
			if(f){
				values[key] = list.eq(i).attr('uid');
				key++;
			}
		};
		var tid = "<?php echo ($teacher_id); ?>";
		var f = $(this).attr("f");
		location.href="/mobile.php?m=mobile.php&c=TBorrow&a=task_comment&teacher_id="+tid+"&eval_type=2&circul_status=3&flag="+f+"&student_id="+values.join(',');
	});

	$("#a_ckall").bind("click",function(){
		if($(this).hasClass("pay_quan_ed")){
			$(this).removeClass("pay_quan_ed");
			$.each($(".finish .li_cart"),function(b, c) { 
				$(c).removeClass("li_cart_ck");		
			});
		}else{
			if($(".finish .li_cart").length>0){
				$(this).addClass("pay_quan_ed");
				$.each($(".finish .li_cart"),function(b, c) { 
					$(c).addClass("li_cart_ck");		
				});
			}else{
				alert("暂无数据！");
			}
		}
		
	});
	
	$("#a_ckall1").bind("click",function(){
		if($(this).hasClass("pay_quan_ed")){
			$(this).removeClass("pay_quan_ed");
			$.each($(".unfinish .li_cart"),function(b, c) { 
				$(c).removeClass("li_cart_ck");		
			});
		}else{
			if($(".unfinish .li_cart").length>0){
				$(this).addClass("pay_quan_ed");
				$.each($(".unfinish .li_cart"),function(b, c) { 
					$(c).addClass("li_cart_ck");		
				});
			}else{
				alert("暂无数据！");
			}
		}
		
	});
	
	$(".finish .li_cart").click(function(event){
		$(this).toggleClass("li_cart_ck")
		
		var l=$(".finish .li_cart").length;
		var l2=$(".finish .li_cart_ck").length;
		if(l2==l){
			$("#a_ckall").addClass("pay_quan_ed");	
		}else{
			$("#a_ckall").removeClass("pay_quan_ed");	
		}
		
		event.stopPropagation();
	});
	
	$(".unfinish .li_cart").click(function(event){
		$(this).toggleClass("li_cart_ck")
		
		var l=$(".unfinish .li_cart").length;
		var l2=$(".unfinish .li_cart_ck").length;
		if(l2==l){
			$("#a_ckall1").addClass("pay_quan_ed");	
		}else{
			$("#a_ckall1").removeClass("pay_quan_ed");	
		}
		
		event.stopPropagation();
	});
	
})


</script>
</body>
</html>