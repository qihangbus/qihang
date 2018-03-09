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
		<a href="<?php echo U('mobile.php/TIndex/address/',array('user_id'=>$user_id,'user_flag'=>$user_flag));?>">
			<li class="li_item"><i class="fa fa-map-marker" style="color:#b4de86;font-size:24px;padding:11px;width:40px;"></i><span style="padding-left:0px;">收货地址</span></li>
		</a>

		<a href="<?php echo U('mobile.php/TIndex/earn/',array('user_id'=>$user_id,'user_flag'=>$user_flag));?>">
			<li class="li_item"><i class="fa fa-credit-card-alt" style="color:#b4de86;font-size:16px;padding:11px;width:40px;"></i><span style="padding-left:0px;">我的收益</span></li>
		</a>


		<a href="<?php echo U('mobile.php/TIndex/students/',array('user_id'=>$user_id,'user_flag'=>$user_flag));?>">
			<li class="li_item"><i class="fa fa-user" style="color:#b4de86;font-size:24px;padding:11px;width:40px;"></i><span style="padding-left:0px;">学生管理</span></li>
		</a>

		<a href="<?php echo U('mobile.php/TIndex/editPwd');?>">
			<li class="li_out"><i class="fa fa-key" style="color:#b4de86;font-size:24px;padding:11px;width:40px;"></i><span style="padding-left:0px;">修改密码</span></li>
		</a>

		<a href="<?php echo U('mobile.php/TIndex/signOut',array('user_id'=>$user_id));?>">
			<li class="li_out"><i class="fa fa-sign-out" style="color:#b4de86;font-size:24px;padding:11px;width:40px;"></i><span style="padding-left:0px;">退出登录</span></li>
		</a>
	</ul>
	 <a href="<?php echo U('mobile.php/TIndex/index',array('teacher_id'=>$user_id));?>">
<span class="f_index" style="bottom:15px;"><span class="iconfont icon-shouyeshouye"></span></span>
</a> 
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script type="text/javascript">
	 
</script>
</body>
</html>