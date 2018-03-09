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
		<a href="<?php echo U('mobile.php/Ucenter/address/',array('student_id'=>$student_id,'user_flag'=>3));?>">
			<li class="li_item"><i class="fa fa-map-marker" style="color:#b4de86;font-size:24px;padding:11px;width:40px;"></i><span style="padding-left:0px;">收货地址</span></li>
		</a>
		<a href="<?php echo U('mobile.php/Ucenter/earn/',array('student_id'=>$student_id,'user_flag'=>3));?>">
		<li class="li_item"><i class="fa fa-credit-card-alt" style="color:#b4de86;font-size:16px;padding:11px;width:40px;"></i><span style="padding-left:0px;">我的收益</span></li>
		</a>

		<a href="<?php echo U('mobile.php/Ucenter/parent/',array('student_id'=>$student_id,'user_flag'=>3));?>">
			<li class="li_item"><i class="fa fa-user" style="color:#b4de86;font-size:24px;padding:11px;width:40px;"></i><span style="padding-left:0px;">家长信息</span></li>
		</a>
		<?php if(!empty($pay_url)): ?><a href="<?php echo ($pay_url); ?>">
				<li class="li_out"><i class="fa fa-ticket" style="color:#b4de86;font-size:24px;padding:11px;width:40px;"></i><span style="padding-left:0px;">交费开通</span></li>
			</a>
			<?php else: ?>
			<a href="#">
				<li class="li_out"><i class="fa fa-ticket" style="color:#b4de86;font-size:24px;padding:11px;width:40px;"></i><span style="padding-left:0px;">已开通 (<?php echo (date('Y-m-d',$paid_time)); ?> 至 <?php echo (date('Y-m-d',$end_time)); ?>)</span></li>
			</a><?php endif; ?>
		<a href="<?php echo U('mobile.php/Ucenter/editPwd');?>">
			<li class="li_out"><i class="fa fa-key" style="color:#b4de86;font-size:24px;padding:11px;width:40px;"></i><span style="padding-left:0px;">修改密码</span></li>
		</a>

		<a href="<?php echo U('mobile.php/Ucenter/signOut');?>">
			<li class="li_out"><i class="fa fa-sign-out" style="color:#b4de86;font-size:24px;padding:11px;width:40px;"></i><span style="padding-left:0px;">退出登录</span></li>
		</a>
	</ul>
	<a href="<?php echo U('mobile.php/Ucenter/index');?>">
<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
</a>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script type="text/javascript">
	 
</script>
</body>
</html>