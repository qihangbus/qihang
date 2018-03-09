<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="x-dns-prefetch-control" content="on">
		<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
		<meta http-equiv="Cache-Control" content="no-siteapp" />
		<meta name="renderer" content="webkit">
		<title>教师</title>
		<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
		<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
	</head>

	<body>

		<div class="wrap2">
			<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><div class="head-cate">
				<ul class="ul_1">
					
					<li style="width:100%;padding:0 5px 0 5px">
						<span style="float:left;"><?php echo ($data["class_name"]); ?></span>
						<span style="float:right;margin-top: 6px;"><a href="<?php echo U('mobile.php/Teacher/showBooks',array('id'=>$data['class_id'],'sid'=>$id));?>" class="ibtn_1">图书信息</a></span>
					</li>
				</ul>
			</div>

			<ul class="cart_list list_banj">
				
				<?php if(is_array($data['teacher'])): $i = 0; $__LIST__ = $data['teacher'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart">
					<dl class="cart_pro">
						<dt class="dt_tx"><img src="<?php echo ((isset($vo["teacher_avatar"]) && ($vo["teacher_avatar"] !== ""))?($vo["teacher_avatar"]):'/Public/images/mobiles/tx.png'); ?>" alt="teacher_avatar" /></dt>
						<dd class="dd_pname2 p_padr">
							<p class=" p_name"><?php echo ($vo["teacher_name"]); ?></p>
							
						</dd>
					</dl>
				</li><?php endforeach; endif; else: echo "" ;endif; ?>

			</ul><?php endforeach; endif; else: echo "" ;endif; ?>
			<a href="<?php echo U('mobile.php/SIndex/Index',array('id'=>$id));?>">
			<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
			</a>
		</div>
		<script class="js_prejs" type="text/javascript" src="__STATIC__/lib/jquery.min.js"></script>
		<script type="text/javascript">
		</script>
	</body>

</html>