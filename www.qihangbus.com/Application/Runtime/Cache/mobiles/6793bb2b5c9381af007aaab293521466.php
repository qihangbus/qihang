<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-dns-prefetch-control" content="on">
	<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<meta name="renderer" content="webkit">
	<title>学生信息</title>
	<link rel="stylesheet" href="/Public/css/mobiles/base.css">
	<link rel="stylesheet" href="/Public/css/mobiles/app.css">
	<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body>

<div class="wrap2">

	<div class="address_t"><a href="javascript:history.back(-1);"><span>取消</span></a></div>
	<div class="line_hr"></div>
	<form name="myform" id="myform" action="<?php echo U('mobile.php/TIndex/student_handle');?>" method="post" onsubmit="return check(this);">
		<ul class="ul_form ul_form_jz">
			<li><span class="s_label">学生姓名</span><span class="s_input "><input type="text" name="student_name" id="student_name" value="<?php echo ($info["student_name"]); ?>" placeholder=""/></span></li>
			<li>
				<span class="s_label">孩子关系</span>
			<span class="s_radiobox ">
				<b data-id="1" <?php if($info["parent_sex"] == 1): ?>class="b_checked"<?php endif; ?>>爸爸</b>
				<b data-id="2" <?php if($info["parent_sex"] == 2): ?>class="b_checked"<?php endif; ?>>妈妈</b>
				<b data-id="3" <?php if($info["parent_sex"] == 3): ?>class="b_checked"<?php endif; ?>>其他</b>
			</span>
				<input type="hidden" id="guanx" name="parent_sex" value="<?php echo ($info["parent_sex"]); ?>" />
				<input type="hidden" id="parent_id" name="parent_id" value="<?php echo ($info["parent_id"]); ?>"/>
				<input type="hidden" id="student_id" name="student_id" value="<?php echo ($student_id); ?>"/>
				<input type="hidden" id="teacher_id" name="teacher_id" value="<?php echo ($user_id); ?>"/>
			</li>
			<li><span class="s_label">手机号码</span><span class="s_input "><input type="tel" name="parent_mobile" id="parent_mobile" value="<?php echo ($info["parent_mobile"]); ?>" placeholder="" maxlength="11"  /></span></li>
			<li><span class="s_label">家长姓名</span><span class="s_input "><input type="text" name="parent_name" id="parent_name" value="<?php echo ($info["parent_name"]); ?>" placeholder=""/></span></li>
		</ul>

		<br><br><br>
		<div class="btn_box btn_boxadd"><a href="javascript:void(0);" id="btn_save" class="btn btn2 btn_addjz">保存</a></div>

	</form>

	<a href="<?php echo U('mobile.php/TIndex/index',array('teacher_id'=>$user_id));?>">
		<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
	</a>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script src="/Public/js/mobiles/layer/layer.js"></script>
<script type="text/javascript">
	function check()
	{
		var student_name = $("#student_name").val();
		var guanx = $("#guanx").val();
		var parent_mobile = $("#parent_mobile").val();
		var parent_name = $("#parent_name").val();

		if(student_name == ''){
			layer.msg('请填写学生姓名',{time:1000});
			return false;
		}
		return true;
	}

	$(function(){
		$(".s_radiobox b").click(function(){
			$("#guanx").val($(this).attr("data-id"));
			$(this).addClass("b_checked").siblings("b").removeClass("b_checked");
		})

		$("#btn_save").click(function(){
			$("#myform").submit();
		});
	})
</script>
</body>
</html>