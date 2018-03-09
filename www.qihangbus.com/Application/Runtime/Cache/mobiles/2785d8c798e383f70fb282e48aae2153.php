<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>跳转提示</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
<style type="text/css">
/*12.09 */
.c_del{ position:fixed; right:20px; bottom:60px;} 

.dl_404{ padding:0 20px; color:#99d656; position:absolute; left:0; width:100%; top:25%; }
.dl_404{ text-align:center;}
.dl_404 dt{ padding:10px;}
.dl_404 dt img{ width:30%; max-width:120px; }
.dl_404 .dd_tips{ padding:10px 0;line-height:25px; }
.dl_succ dt img{ width:25%;}
</style>
</head>
<body>

<div class="wrap2 mbg"> 


		<dl class="dl_404 dl_succ">
				
				<?php if($message != ''): ?><dt><img src="/Public/images/mobiles/ic_succ.png" alt="" /></dt>
				<dd class="dd_tips"><?php echo ($message); ?>，页面跳转中</dd>
				
				<?php else: ?>
				<dt><img src="/Public/images/mobiles/ic_error.png" alt="" /></dt>
				<dd class="dd_tips"><?php echo ($error); ?>，页面跳转中</dd><?php endif; ?>	

				
			</dl>




<b id="wait" style="display:none;"><?php echo ($waitSecond); ?></b>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
var wait = document.getElementById('wait');
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = "<?php echo ($jumpUrl); ?>";
		clearInterval(interval);
	};
}, 1000);
});
</script>
</body>
</html>