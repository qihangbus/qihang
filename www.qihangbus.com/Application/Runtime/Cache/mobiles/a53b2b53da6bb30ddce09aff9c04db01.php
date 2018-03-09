<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>收益记录</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
</head>
<body>

<div class="wrap2"> 
<div class="iban"><img src="/Public/images/mobiles/ban_mx.jpg" alt="" /></div>
<!-- <div class="mxjilu_t"><span>当前可用金豆<b class="color2"><?php echo ($userinfo["student_points"]); ?></b>颗</span></div> -->
<div class="line_hr"></div>
<div class="mxjilu_list">
	<?php if(empty($list)): ?><div class="data-empty">
		<p><img src="/Public/images/mobiles/empty.png"><p>
		<p>暂无收益记录</p>
		</div><?php endif; ?>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dl>
	<dt><?php echo ($vo["change_desc"]); ?> <span><?php echo (date("Y-m-d",$vo["change_time"])); ?></span></dt>
	<dd><span class="iconfont icon-dadou"></span>&nbsp;<span class="color<?php echo ($vo["change_type"]); ?>"><?php echo ($vo["student_points"]); ?></span></dd>
	</dl><?php endforeach; endif; else: echo "" ;endif; ?>
</div>
 <a href="<?php echo U('mobile.php/TIndex/index',array('teacher_id'=>$user_id));?>">
<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
</a> 
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script type="text/javascript">

</script>
</body>
</html>