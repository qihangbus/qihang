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
		.cart_pro .dt_tx .play-img{
			width: 35px;
			height: 35px;
			margin-top: 16px;
			margin-left: -50px;
			z-index: 100;
			position: absolute;
		}
		.cart_pro .dt_tx .stop-img{
			width: 35px;
			height: 35px;
			margin-top: 16px;
			margin-left: -50px;
			z-index: 100;
			position: absolute;
		}
		.btn-circle {
			width: 30px;
			height: 30px;
			text-align: center;
			padding: 6px 0;
			font-size: 12px;
			line-height: 1.428571429;
			border-radius: 15px;
		}
		.btn-circle.btn-lg {
			width: 50px;
			height: 50px;
			padding: 10px 16px;
			font-size: 18px;
			line-height: 1.33;
			border-radius: 25px;
		}
		.btn-circle.btn-xl {
			width: 70px;
			height: 70px;
			padding: 10px 16px;
			font-size: 24px;
			line-height: 1.33;
			border-radius: 35px;
		}
		.tip {
			width:80% !important;
		}
		.tip .layui-m-layercont {
			padding: 36px 10px;
			line-height: 22px;
			text-align: center;
		}
		.tip .layui-m-layerbtn {
			display: -webkit-box;
			width: 100%;
			height: 40px;
			line-height: 40px;
			font-size: 0;
			border-top: 1px solid #D0D0D0;
			background-color: #F2F2F2;
			position: relative;
			text-align: center;
			border-radius: 0 0 5px 5px;
		}
		.tip .layui-m-layerbtn span {
			display: block;
			-moz-box-flex: 1;
			box-flex: 1;
			-webkit-box-flex: 1;
			font-size: 14px;
			cursor: pointer;
			position: relative;
			text-align: center;
			border-radius: 0 0 5px 5px;
		}
		.tip .layui-m-layerbtn span[no] {
			border-right: 1px solid #D0D0D0;
			border-radius: 0 0 0 5px;
		}
		.tip .layui-m-layerbtn span[yes] {
			color: #40AFFE;
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
	<?php if($now_info != ''): ?><ul class="cart_list order_list">
			<?php if(empty($now_info)): ?><div class="data-empty">
					<p><img src="/Public/images/mobiles/empty.png"><p>
					<p>暂无数据</p>
				</div><?php endif; ?>
			<?php if($now_info[0].book_name != ''): if(is_array($now_info)): $i = 0; $__LIST__ = $now_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart" style="padding-top:5px;padding-bottom:1px">
						<dl class="cart_pro">
							<dt class="dt_tx">
								<img src="<?php echo ($vo["book_thumb"]); ?>" class="book-img"/>
								<?php if(file_exists('.'.$vo['video'])){ echo "<img src='/Public/2017/image/pause.png' class='play-img'/>
									<audio src='".$vo['video']."' id='my_audio'></audio>"; } ?>
							</dt>
							<dd class="dd_pname2">
								<p>
									<strong><?php echo ($vo["book_name"]); ?></strong>
									<?php if(($vo["compen"]) == "1"): ?><a href="<?php echo ($url); ?>" class="ibtn_1" style="background-color: rgb(255, 216, 135);float:right;margin-right:6px;position:absolute;top:19px;right:6px;">赔偿中</a>
										<?php else: ?>
										<a href="javascript:void(0);" bid="<?php echo ($vo['book_id']); ?>" class="ibtn_1 compensate" style="background-color: rgb(255, 216, 135);float:right;margin-right:6px;position:absolute;top:19px;right:6px;">损坏赔偿</a><?php endif; ?>
								</p>
								<p>
									<b>借阅时间：</b><?php echo (date("Y-m-d",$vo["add_time"])); ?>
								</p>
							</dd>
						</dl>
					</li><?php endforeach; endif; else: echo "" ;endif; endif; ?>

		</ul>
		<div class="line_hr"></div><?php endif; ?>
	<a href="<?php echo U('mobile.php/Ucenter/index');?>">
		<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
	</a>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script type="text/javascript" src="/Public/2017/js/jQueryRotate.js"></script>
<script type="text/javascript">
	(function() {
		var time1;
		$('.play-img').click(function () {
			var now = $(this);
			var my_audio = now.next()[0];
			if (now.attr('class') == 'play-img') {
				clearInterval(time1);
				$('.stop-img').each(function (e) {
					$(this).attr('src', '/Public/2017/image/pause.png');
					$(this).attr('class', 'play-img');
					$(this).next()[0].pause();
				});
				now.attr('src', '/Public/2017/image/play.png');
				now.attr('class', 'stop-img');
				var angle = 0;
				time1 = setInterval(function () {
					angle += 3;
					now.prev().rotate(angle);
				}, 50);
				my_audio.play();
				my_audio.addEventListener('ended', function () {
					clearInterval(time1);
					now.attr('src', '/Public/2017/image/pause.png');
					now.attr('class', 'play-img');
				}, false);
			} else {
				clearInterval(time1);
				now.attr('src', '/Public/2017/image/pause.png');
				now.attr('class', 'play-img');
				my_audio.pause();
			}

		});
		$(".compensate").click(function(){
			var bid = $(this).attr("bid");
			var compensate = $(this);

			layer.open({
				className: 'tip',
				title: false,
				content: '绘本损坏，发起损坏赔偿？',
				btn:['确定','取消'],
				yes:function(index,layero){

					$.post("<?php echo U('compensate');?>",{bid:bid},function(data){
						if(data.status == 0){
							layer.open({
								content: data.info
								,skin: 'msg'
								,time: 2 //2秒后自动关闭
							});
						}else{
							layer.open({
								className: 'tip',
								content: '操作成功'
								,btn: '去支付'
								,yes: function(){
									location.href = data.url;
								}
							});
						}
					});

				},
				btn2:function(index,layero){
					layer.closeAll();
				}
			});
		});
	})(jQuery);
</script>
</body>
</html>