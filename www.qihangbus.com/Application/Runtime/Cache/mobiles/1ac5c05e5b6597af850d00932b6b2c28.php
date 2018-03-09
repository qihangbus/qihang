<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-dns-prefetch-control" content="on">
	<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<meta name="renderer" content="webkit">
	<title>轮换接收</title>
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
	<ul class="cart_list ck_list" id="cart_list" style="margin-bottom:50px;">
		<?php if(empty($data)): ?><div class="data-empty">
				<p><img src="/Public/images/mobiles/empty.png"><p>
				<p>暂无数据</p>
			</div><?php endif; ?>
		<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart" bid="<?php echo ($vo["book_id"]); ?>" style="margin-bottom:0px;padding-top:10px;">
				<dl class="cart_pro">
					<dt class="dt_tx"><img src="<?php echo ((isset($vo['book_thumb']) && ($vo['book_thumb'] !== ""))?($vo['book_thumb']):'/Public/images/mobiles/default.png'); ?>" alt="" /></dt>
					<dd class="dd_pname2" style="padding-top:8px;">
						<p>
							<a href="javascript:void(0);" class="ibtn_1 dd" style="float: right; background-color: rgb(255, 216, 135);">未到</a>
							<a href="javascript:void(0);" class="ibtn_1 lose" data-bid="<?php echo ($vo["book_id"]); ?>" style="float:right;margin-right:5px;background-color: #E67878">丢失</a>
							<strong>NO.<?php echo ($vo["book_no"]); ?></strong>
						</p>
						<p>
							<?php echo ($vo['book_name']); ?>
						</p>
					</dd>
				</dl>
				<div class="i_btn" style="padding-top:0px;"></div>
			</li><?php endforeach; endif; else: echo "" ;endif; ?>
	</ul>


	<div class="footer2">
		<ul class="footer_pbtn">
			<li class="fbtn_l"><span class="pay_quan" id="a_ckall">全选</span></li>
			<li class="fbtn_r"><a href="javascript:void(0);" class="batch_borrow btn_js" style="width:100px;float:right;">批量接收</a> </li>
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
			var list = $("#cart_list li");
			var bids = [];
			var key = 0;
			for (var i=0;i<list.length;i++) {
				f = list.eq(i).hasClass('li_cart_ck');
				if(f){
					bids[key] = list.eq(i).attr('bid');
					key++;
				}
			};

			if($('li.li_cart_ck').length == 0){
				layer.msg('至少选择一个！',{time:2000});
			}else{
				layer.open({
					title: false,
					content: '确认批量接收操作？',
					btn:['确认','取消'],
					yes:function(index,layero){
						$.post("<?php echo U('receive');?>",{bids:bids},function(data){
							if(data.status == 1){
								layer.msg(data.info,{time:800},function(){
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
					alert("暂无数据！");
				}
			}
		});

		$("#cart_list .li_cart").click(function(){
			if($(this).hasClass('li_cart_ck')){
				$(this).find('a.dd').css('background-color','#ffd887');
				$(this).find('a.dd').html('未到');
			}else{
				$(this).find('a.dd').attr('style','float:right;');
				$(this).find('a.dd').html('已到');
			}
			$(this).toggleClass("li_cart_ck");

		});

		$('.lose').click(function(event){
			var bid = $(this).attr('data-bid');
			layer.confirm('确定绘本丢失',{title:'',icon:3},function(){
				$.post("<?php echo U('lose');?>",{bid:bid},function(data){
					if(data.status == 1){
						layer.msg(data.info,{time:800},function(){
							location.reload();
						});
					}else{
						layer.msg(data.info,{time:1000});
					}
				});
			});
			event.stopPropagation();
		});
	})

</script>
</html>