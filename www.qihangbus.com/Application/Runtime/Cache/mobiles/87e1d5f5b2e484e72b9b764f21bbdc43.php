<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>等级</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body class="">

<div class="wrap2 "> 
<dl class="dengji_u">
<dt class="dt_tx"><img src="/Public/images/mobiles/dj_tx.png" alt="" /></dt>
<dd class="dd_name"><strong>当前等级 <?php echo ($student_rank); ?> 级</strong>  <p>累计获得金豆<?php echo ($student_points); ?>颗</p></dd> 
</dl>
<div class="line_hr"></div>

  <div class="dengj_tit">下一等级</div>
   <dl class="dl_dengj">
			<dt><img src="/Public/images/mobiles/ic_dengj.png" alt="" /></dt>
			<dd>等级 <?php echo ($nextrank); ?> 级<br><span class="s_text">累计获得金豆<?php echo ($next_points); ?>颗</span></dd>
	</dl>
	  <dl class="dl_dengj" style="display:none;">
			<dt><img src="style/images/ic_dengj.png" alt="" /></dt>
			<dd>等级 1 级<br><span class="s_text">累计获得金豆10颗</span></dd>
	</dl>
	  <dl class="dl_dengj" style="display:none;">
			<dt><img src="style/images/ic_dengj.png" alt="" /></dt>
			<dd>等级 1 级<br><span class="s_text">累计获得金豆10颗</span></dd>
	</dl>
	
  <div class="line_hr"></div>
	<div class="dengj_tit">勋章</div>
	<div class="c_xunz">
		<ul class="clearfix">
		<?php if(is_array($medal_list)): $i = 0; $__LIST__ = $medal_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><img src="<?php if($vo["flag"] > 0): echo ($vo["medal_high_pic"]); else: echo ($vo["medal_pic"]); endif; ?>" alt="<?php echo ($vo["medal_name"]); ?>" /> <span><?php echo ($vo["remark"]); ?></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
		</ul>
	</div>
	
	







<a href="<?php echo U('mobile.php/Ucenter/index');?>">
<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
</a>
</div>
</body>
</html>