<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="x-dns-prefetch-control" content="on">
		<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
		<meta http-equiv="Cache-Control" content="no-siteapp" />
		<meta name="renderer" content="webkit">
		<title>班级图书信息</title>
		<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
		<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
	</head>

	<body>

		<div class="wrap2">
			<div class="head-cate">
				<ul class="ul_1">
					<li class="li_cur"><span><?php echo ($class_name); ?> </span></li>
				</ul>
			</div>
			<div class="line_hr"></div>
			<ul class="cart_list">
				<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart">
					<dl class="cart_pro">
						<dt class=""><img src="<?php echo ($vo["book_thumb"]); ?>" alt="" /></dt>
						<dd class="dd_pname2 p_padr">
							<p><b><?php echo ($vo["book_name"]); ?></b></p>
						</dd>
						<dd class="dd_r dd_r3" style="margin:5px 0 0 0">
							<a href="<?php echo U('mobile.php/Teacher/history/',array('book_id'=>$vo['book_id'],'tid'=>$id));?>" class="ibtn_1">借阅历史</a>
						</dd>
					</dl>
				</li><?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
			<a href="<?php echo U('mobile.php/SIndex/Index',array('id'=>$id));?>">
<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
</a>
		</div>
		<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
		<script type="text/javascript">
		</script>
	</body>

</html>