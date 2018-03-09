<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-dns-prefetch-control" content="on">
	<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<meta name="renderer" content="webkit">
	<title>借阅</title>
	<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
	<link rel="stylesheet" href="/Public/css/mobiles/base.css">
	<link rel="stylesheet" href="/Public/css/mobiles/app.css">
	<style type="text/css">
		.cart_pro .dd_pname2 p {
			line-height: 32px;
		}
	</style>
</head>
<body>

<div class="wrap2">
	<div class="head-cate">
		<ul class="ul_3">
			<li <?php if($flag == 1): ?>class="li_cur"<?php endif; ?>><a href="<?php echo U('mobile.php/Borrowtoread/index',array('student_id'=>$student_id,'flag'=>1,'user_flag'=>$user_flag));?>"><span><i class="i_ic">当前阅读</i> </span></a></li>
			<li <?php if($flag == 2): ?>class="li_cur"<?php endif; ?>><a href="<?php echo U('mobile.php/Borrowtoread/index',array('student_id'=>$student_id,'flag'=>2,'user_flag'=>$user_flag));?>"><span><i class="i_ic">即将阅读</i></span></a></li>
			<li <?php if($flag == 99): ?>class="li_cur"<?php endif; ?>><a href="<?php echo U('mobile.php/Borrowtoread/index',array('student_id'=>$student_id,'flag'=>99,'user_flag'=>$user_flag));?>"><span><i class="i_ic">赔偿管理</i></span></a></li>
		</ul>
	</div>
	<div class="line_hr"></div>
	<?php if($next_info != ''): ?><ul class="cart_list order_list">

			<?php if(empty($next_info)): ?><div class="data-empty">
					<p><img src="/Public/images/mobiles/empty.png"><p>
					<p>暂无数据</p>
				</div><?php endif; ?>
			<?php if($next_info[0].book_name != ''): if(is_array($next_info)): $i = 0; $__LIST__ = $next_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart" style="padding-top:5px;padding-bottom:1px">
						<dl class="cart_pro">
							<dt class="dt_tx">
								<a href="<?php echo U('mobile.php/Borrowtoread/info',array('book_id'=>$vo['book_id'],'student_id'=>$student_id,'user_flag'=>$user_flag));?>">
									<img src="<?php echo ($vo["book_thumb"]); ?>" alt="" />
								</a>
							</dt>
							<dd class="dd_pname2">
								<a href="<?php echo U('mobile.php/Borrowtoread/info',array('book_id'=>$vo['book_id'],'student_id'=>$student_id,'user_flag'=>$user_flag));?>">
									<p><strong><?php echo ($vo["book_name"]); ?></strong><?php if(($vo["type"]) == "1"): ?><span style="font-weight: 900">【预】</span><?php endif; ?></p>
								</a>
								<p><b>借阅时间：</b><?php echo (date("Y-m-d",$vo["add_time"])); ?></p>
							</dd>
						</dl>
						<?php if(($vo["type"]) == "1"): ?><div style="position:absolute;right:10px;top:24px;">
								<a href="<?php echo U('mobile.php/Borrowtoread/removeBorrow',array('circulation_id'=>$vo['circulation_id']));?>" class="ibtn_1">
									取消预约
								</a>
							</div><?php endif; ?>
					</li><?php endforeach; endif; else: echo "" ;endif; endif; ?>

		</ul>
		<div class="line_hr"></div><?php endif; ?>
	<a href="<?php echo U('mobile.php/Ucenter/index');?>">
		<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
	</a>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script type="text/javascript">
	$(function(){
		$.each($(".ck_list"),function(b, c) {
			$.each($(c).find(".li_cart"),function(b2, c2) {
				$(c2).click(function(event){
					$(this).toggleClass("li_cart_ck");
				});
			});
		});

		$(".ibtn_cancel").click(function(){
			var oid = $(this).attr('oid');

			layer.open({
				title: false,
				content: '确定取消此订单',
				btn:['确定','取消'],
				yes:function(index,layero){

					$.post("<?php echo U('mobile.php/Order/cancel');?>",{order_id:oid},function(result){
						if(result > 0){

							layer.open({
								title:false,
								content:'取消订单成功',
								btn:['关闭'],
								yes:function(index){
									layer.closeAll();
									location.reload();
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
	})
</script>
</body>
</html>