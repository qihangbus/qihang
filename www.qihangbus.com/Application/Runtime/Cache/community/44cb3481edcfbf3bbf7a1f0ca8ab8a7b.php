<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html style="font-size: 250px;" data-dpr="1">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title><?php echo ($school_name); ?></title>
<link href="/Public/css/mobiles/main.min.css" rel="stylesheet">
</head>
<body>
<div id="default-wrapper" class="default-wrapper" style="visibility: visible;">
	<form name="form1" action="<?php echo U('sns.php/Forum/comments_handle');?>" method="post">
	<input type="hidden" name="forum_id" value="<?php echo ($forum_id); ?>">
	<input type="hidden" name="user_id" value="<?php echo ($user_id); ?>">
	<input type="hidden" name="school_id" value="<?php echo ($school_id); ?>">
	<input type="hidden" name="user_flag" value="<?php echo ($user_flag); ?>">
	<input type="hidden" name="fake_id" value="<?php echo ($fake_id); ?>">
	<div class="default-timeline-wrapper">
		
		<textarea name="content" style="width:100%;margin-bottom:5px;height:120px;padding:5px 15px;border:none;font-size:13px;" placeholder="你正在回复<?php echo ($title); ?>"></textarea>	
		
		<div style="border-bottom:1px solid #e9ecf1;height:35px;line-height:35px;background-color:#FFFFFF;">
			<input type="submit" style="float:right;border:none;border-radius:5px;height:25px;line-height:25px;margin:5px 0;width:0.8rem;margin-right:15px;color:#ffffff;background-color:#ff964b;" value="发送">
			<input type="reset" style="float:right;border:none;border-radius:5px;height:25px;line-height:25px;margin:5px 0;width:0.8rem;margin-right:15px;color:#ffffff;background-color:#ccc;" value="取消">
		</div>	
	</div>
	
	</form>
</div>
</body>
</html>