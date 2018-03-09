<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>设置</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
<link rel="stylesheet" href="/Public/css/mobiles/font-awesome/css/font-awesome.min.css">
</head>
<body>
<div class="wrap2"> 
	<ul class="m_setting ">
		<a href="<?php echo U('editPwd');?>">
			<li class="li_out"><i class="fa fa-key" style="color:#b4de86;font-size:24px;padding:11px;width:40px;"></i><span style="padding-left:0px;">修改密码</span></li>
		</a>

		<a href="<?php echo U('signOut');?>">
			<li class="li_out"><i class="fa fa-sign-out" style="color:#b4de86;font-size:24px;padding:11px;width:40px;"></i><span style="padding-left:0px;">退出登录</span></li>
		</a>
	</ul>
		<a href="<?php echo U('mobile.php/Agent/index');?>">
			<span class="f_index">
				<span class="iconfont icon-shouyeshouye"></span>
			</span>
		</a>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script type="text/javascript">
	 
</script>
</body>
</html>