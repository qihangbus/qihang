<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-dns-prefetch-control" content="on">
	<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<meta name="renderer" content="webkit">
	<title>任务额外奖励-听姐姐讲绘本故事</title>
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
	</style>
</head>
<body>

<div class="wrap2">
		<ul class="cart_list order_list">

			<?php if(empty($data)): ?><div class="data-empty">
					<p><img src="/Public/images/mobiles/empty.png"><p>
					<p>暂无数据</p>
				</div>
			<?php else: ?>
				<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart" style="padding-top:5px;padding-bottom:1px">
						<dl class="cart_pro">
							<dt class="dt_tx">
								<img src="<?php echo ((isset($vo["book_thumb"]) && ($vo["book_thumb"] !== ""))?($vo["book_thumb"]):'Public/images/mobiles/default.png'); ?>" class="book-img"/>
									<img src='/Public/2017/image/pause.png' class='play-img'/>
									<audio src='/Public/bookVideo/<?php echo ($vo["book_isbn"]); ?>.wav' id='my_audio'></audio>
								</php>
							</dt>
							<dd class="dd_pname2">
								<p><strong><?php echo ($vo["book_name"]); ?></strong></p>
								<p style="color: #9C9898;font-size: 13px;"><b style="color: #9C9898;font-size: 13px;">时长：</b><?php echo ($vo["time"]); ?>分钟</p>
							</dd>
						</dl>
					</li><?php endforeach; endif; else: echo "" ;endif; endif; ?>
		</ul>
		<div class="line_hr"></div>
	<a href="<?php echo U('mobile.php/Ucenter/index');?>">
		<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
	</a>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script type="text/javascript" src="/Public/2017/js/jQueryRotate.js"></script>
<script type="text/javascript">
	(function(){
		var time1;
		$('.play-img').click(function(){
			var now = $(this);
			var my_audio = now.next()[0];
			if(now.attr('class') == 'play-img'){
				clearInterval(time1);
				$('.stop-img').each(function(e){
					$(this).attr('src','/Public/2017/image/pause.png');
					$(this).attr('class','play-img');
					$(this).next()[0].pause();
				});
				now.attr('src','/Public/2017/image/play.png');
				now.attr('class','stop-img');
				var angle = 0;
				time1 = setInterval(function(){
					angle+=3;
					now.prev().rotate(angle);
				},50);
				my_audio.play();
				my_audio.addEventListener('ended', function () {
					clearInterval(time1);
					now.attr('src','/Public/2017/image/pause.png');
					now.attr('class','play-img');
				}, false);
			}else{
				clearInterval(time1);
				now.attr('src','/Public/2017/image/pause.png');
				now.attr('class','play-img');
				my_audio.pause();
			}

		});
	})(jQuery);
</script>
</body>
</html>