<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-dns-prefetch-control" content="on">
	<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<meta name="renderer" content="webkit">
	<title>借阅记录</title>
	<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
	<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body>
<div class="wrap2">
	<div class="line_hr"></div>
	<ul class="cart_list ck_list" id="cart_list" style="margin-bottom:80px;">
		<?php if(empty($borrows)): ?><div class="data-empty">
				<p><img src="/Public/images/mobiles/empty.png"><p>
				<p>暂无数据</p>
			</div><?php endif; ?>
		<?php if(is_array($borrows)): $i = 0; $__LIST__ = $borrows;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["flag"] < '1'): ?><li class="li_cart" cid="<?php echo ($vo["circulation_id"]); ?>" style="margin-bottom:0px;padding-top:10px;">
					<dl class="cart_pro">
						<dt class="dt_tx"><img src="<?php echo ((isset($vo['student_avatar']) && ($vo['student_avatar'] !== ""))?($vo['student_avatar']):'Public/images/mobiles/default.png'); ?>" alt="" /></dt>
						<dd class="dd_pname2">
							<p>
								<b><?php echo ($vo['student_name']); ?></b>
							</p>
							<p style="margin: 5px 0 0 0">
								<strong>NO.<?php echo ($vo["book_no"]); ?></strong> - <?php echo ($vo['book_name']); ?>
							</p>
							<p><strong>借阅时间：</strong><?php echo (date('m-d H:i',$vo["borrow_time"])); ?></p>
							<a href="<?php echo U('mobile.php/TBorrow/loanRevoke',array('circulation_id'=>$vo['circulation_id'],'teacher_id'=>$teacher_id));?>" class="ibtn_1 wh" style="float: right;position: relative;bottom: 45px;">撤销</a>
						</dd>
					</dl>
				</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
	</ul>

	<div class="footer2">
		<ul class="footer_pbtn">
			<input type="hidden" id="user_id" value="<?php echo ($user_id); ?>">
			<input type="hidden" id="user_flag" value="<?php echo ($user_flag); ?>">
			<li class="fbtn_l"><span class="pay_quan" id="a_ckall">全选</span></li>
			<li class="fbtn_r"><a href="javascript:void(0);" class="batch_borrow btn_js" style="width:100px;float:right;">批量撤销</a> </li>
		</ul>
	</div>

</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/layer.js"></script>
<a href="<?php echo U('mobile.php/TIndex/index',array('teacher_id'=>$teacher_id));?>">
	<span class="f_index" style="bottom:60px;"><span class="iconfont icon-shouyeshouye"></span></span>
</a>
<script type="text/javascript">
	$(function(){

		$(".compensate").click(function(){
			var bid = $(this).attr("bid");
			var sid = $(this).attr("sid");
//        var hf = $(this).attr("hf");
			var hf = 99;
			var compensate = $(this);


			layer.open({
				title: false,
				content: '确定给家长发送损坏赔偿提醒？',
				btn:['确定','取消'],
				yes:function(index,layero){

					$.post("<?php echo U('mobile.php/TBorrow/push_msg');?>",{bid:bid,sid:sid,hf:hf},function(result){
						if(result == 99){
							layer.open({
								title:false,
								content:'提醒成功',
								btn:['关闭'],
								yes:function(index){
									compensate.closest('li').remove();
									layer.closeAll();
								},
							});
						}
					});

				},
				btn2:function(index,layero){
					layer.closeAll();
				}
			});
		});


		$(".batch_borrow").click(function(){
			var cid = "<?php echo ($class_id); ?>";

			var list = $("#cart_list li");
			var values = [];
			var key = 0;
			for (var i=0;i<list.length;i++) {
				f = list.eq(i).hasClass('li_cart_ck');
				if(f){
					values[key] = list.eq(i).attr('cid');
					key++;
				}
			};
			if($('li.li_cart_ck').length == 0){
				layer.msg('至少选择一位小朋友！',{time:2000});
			}else{
				layer.open({
					title: false,
					content: '确认批量撤销操作？',
					btn:['确认','取消'],
					yes:function(index,layero){
						$.post("<?php echo U('mobile.php/TBorrow/loanRevoke');?>",{cids:values.join(','),teacher_id:<?php echo ($teacher_id); ?>},function(result){
							if(result.status == 1){
								layer.msg(result.info,{time:1000},function(){
									location.href = result.url;
								});
							}else{
								layer.msg(result.info,{time:2000},function(index){
									layer.close(index);
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

		$("#a_ckall").bind("click",function(){
			if($(this).hasClass("pay_quan_ed")){
				$(this).removeClass("pay_quan_ed");
				$.each($("#cart_list .li_cart"),function(b, c) {
					$(c).removeClass("li_cart_ck");
				});
				$("#cart_amount").html(0);
				$("#ck_val").html(0);
			}else{
				if($("#cart_list .li_cart").length>0){
					$(this).addClass("pay_quan_ed");
					$.each($("#cart_list .li_cart"),function(b, c) {
						$(c).addClass("li_cart_ck");
					});
				}else{
					alert("暂无借阅数据！");
				}
			}
		});

		$("#cart_list .li_cart").click(function(event){
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
		});

	})
</script>
</body>
</html>