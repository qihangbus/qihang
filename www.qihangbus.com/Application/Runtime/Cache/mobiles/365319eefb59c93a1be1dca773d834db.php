<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-dns-prefetch-control" content="on">
	<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<meta name="renderer" content="webkit">
	<title>借阅</title>
	<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
	<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
	<link rel="stylesheet" href="/Public/css/mobiles/font-awesome/css/font-awesome.css">
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

	<ul class="cart_list ck_list" id="cart_list" style="margin-bottom:80px;">
		<?php if(empty($borrows)): ?><div class="data-empty">
				<p><img src="/Public/images/mobiles/empty.png"><p>
				<p>暂无数据</p>
			</div><?php endif; ?>
		<?php if(is_array($borrows)): $i = 0; $__LIST__ = $borrows;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart" sid="<?php echo ($vo["student_id"]); ?>" bid="<?php echo ($vo["book_id"]); ?>" style="margin-bottom:0px;padding-top:10px;">
				<dl class="cart_pro">
					<dt class="dt_tx"><img src="<?php echo ((isset($vo['student_avatar']) && ($vo['student_avatar'] !== ""))?($vo['student_avatar']):'Public/images/mobiles/default.png'); ?>" alt="" /></dt>
					<dd class="dd_pname2" style="padding-top:8px;">
						<p>
							<!--<a href="<?php echo U('mobile.php/TBorrow/getborrow',array('circulation_id'=>$vo['circulation_id'],'teacher_id'=>$teacher_id,'circul_status'=>$circul_status,'student_id'=>$vo['student_id']));?>" class="ibtn_1" style="float:right;">借阅</a>-->
							<a href="javascript:void(0);" class="ibtn_1 dd" style="float: right; background-color: rgb(255, 216, 135);">未到</a>
							<a href="<?php echo U('mobile.php/TBorrow/getborrow',array('circulation_id'=>$vo['circulation_id'],'teacher_id'=>$teacher_id,'circul_status'=>$circul_status,'student_id'=>$vo['student_id']));?>" class="ibtn_1" style="float:right;margin-right:5px;">借阅</a>
							<b><?php echo ($vo['student_name']); ?></b>
						</p>
						<p>
							<strong>NO.<?php echo ($vo["book_no"]); ?></strong> - <?php echo ($vo['book_name']); if(($vo["type"]) == "1"): ?><span style="font-weight: 900">【预】</span><?php endif; ?>
						</p>
					</dd>
				</dl>
				<div class="i_btn" style="padding-top:0px;"></div>
			</li><?php endforeach; endif; else: echo "" ;endif; ?>
	</ul>


	<div class="footer2">
		<ul class="footer_pbtn">
			<input type="hidden" id="user_id" value="<?php echo ($user_id); ?>">
			<input type="hidden" id="user_flag" value="<?php echo ($user_flag); ?>">
			<li class="fbtn_l"><span class="pay_quan" id="a_ckall">全选</span></li>
			<li class="fbtn_r"><a href="<?php echo U('mobile.php/TBorrow/loanRecord',array('teacher_id'=>$teacher_id));?>" class="btn_js" style="width:100px;float:right;background-color:#aee078">借阅撤销</a> </li>
			<li class="fbtn_r"><a href="javascript:void(0);" class="batch_borrow btn_js" style="width:100px;float:right;">批量借阅</a> </li>
		</ul>
	</div>

</div>


</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script src="/Public/layer/layer.js"></script>
<a href="<?php echo U('mobile.php/TIndex/index',array('teacher_id'=>$teacher_id));?>">
	<span class="f_index" style="bottom:60px;"><span class="iconfont icon-shouyeshouye"></span></span>
</a>
</body>
<script type="text/javascript">
	$(function(){
		$(".batch_borrow").click(function(){
			var cid = "<?php echo ($class_id); ?>";

			var list = $("#cart_list li");
			var values = [];
			var bookids = [];
			var key = 0;
			for (var i=0;i<list.length;i++) {
				f = list.eq(i).hasClass('li_cart_ck');
				if(f){
					values[key] = list.eq(i).attr('sid');
					bookids[key] = list.eq(i).attr('bid');
					key++;
				}
			};

			if($('li.li_cart_ck').length == 0){
				layer.msg('至少选择一位小朋友',{time:2500});
			}else{
				layer.open({
					title: false,
					content: '确认批量借阅操作？',
					btn:['确认','取消'],
					yes:function(index,layero){
						$.post("<?php echo U('mobile.php/TBorrow/batch_borrow');?>",{sid:values,bid:bookids},function(result){
							if(result == 99){
								layer.msg('借阅成功',{time:2500},function(){
									location.reload();
								});
							}
						});
					},
					btn2:function(index,layero){
						layer.closeAll();
					}
				});
			}
		});

		if($("#cart_list .li_cart").length>0){
//		$(this).addClass("pay_quan_ed");
//		$.each($("#cart_list .li_cart"),function(b, c) {
//			$(c).addClass("li_cart_ck");
//		});
		}

		$("#a_ckall").bind("click",function(){
			if($(this).hasClass("pay_quan_ed")){
				$(this).removeClass("pay_quan_ed");
				$.each($("#cart_list .li_cart"),function(b, c) {
					$(this).find('a.dd').css('background-color','#ffd887');
					$(this).find('a.dd').html('未到');
					$(c).removeClass("li_cart_ck");
				});
				$("#cart_amount").html(0);
				$("#ck_val").html(0);
			}else{
				if($("#cart_list .li_cart").length>0){
					$(this).addClass("pay_quan_ed");
					$.each($("#cart_list .li_cart"),function(b, c) {
						$(this).find('a.dd').attr('style','float:right;');
						$(this).find('a.dd').html('已到');
						$(c).addClass("li_cart_ck");
					});
				}else{
					alert("暂无借阅数据！");
				}
			}
		});

		$("#cart_list .li_cart").click(function(event){
			if($(this).hasClass('li_cart_ck')){
				$(this).find('a.dd').css('background-color','#ffd887');
				$(this).find('a.dd').html('未到');
			}else{
				$(this).find('a.dd').attr('style','float:right;');
				$(this).find('a.dd').html('已到');
			}
			$(this).toggleClass("li_cart_ck")
			var l=$("#cart_list .li_cart").length;
			var l2=$("#cart_list .li_cart_ck").length;
			if(l2==l){
				$("#a_ckall").addClass("pay_quan_ed");
			}else{
				$("#a_ckall").removeClass("pay_quan_ed");
			}

			event.stopPropagation();

			var list = $("#cart_list li");
			var values = [];
			var key = 0;
			for (var i=0;i<list.length;i++) {
				f = list.eq(i).hasClass('li_cart_ck');
				if(f){
					values[key] = list.eq(i).attr('recid');
					key++;
				}
			};
			var user_id = $("#user_id").val();
			var user_flag = $("#user_flag").val();
			$.post("<?php echo U('mobile.php/Cart/ajax_cart_two');?>",{rec_id:values.join(','),user_id:user_id,user_flag:user_flag},function(result){
				if(result){
					$("#cart_amount").text(result.cart_amount);
					$("#ck_val").text(result.cart_number);
				}
			},'json');
		});
	})

</script>
</html>