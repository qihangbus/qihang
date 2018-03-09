<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-dns-prefetch-control" content="on">
	<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<meta name="renderer" content="webkit">
	<title>家长信息</title>
	<link rel="stylesheet" href="/Public/css/mobiles/base.css">
	<link rel="stylesheet" href="/Public/css/mobiles/app.css">
	<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
	<style type="text/css">
		.getcode {
			background-color: orange;
			border-radius: 20px 20px;
			color: #fff;
			padding: 8px 10px;
			border: none;
			float: right;
			margin: 5px 10px 0 0;
		}
		#code {
			width:60%;
		}
	</style>
</head>
<body>

<div class="wrap2">

	<div class="address_t"><a href="javascript:history.back(-1);"><span>取消</span></a></div>
	<div class="line_hr"></div>
	<form name="myform" id="myform" action="<?php echo U('mobile.php/Ucenter/edit_handle');?>" method="post" onsubmit="return check(this);">
		<ul class="ul_form ul_form_jz">

			<li>
				<span class="s_label">孩子关系</span>
			<span class="s_radiobox ">
				<b data-id="1" <?php if($parent_info["parent_sex"] == 1): ?>class="b_checked"<?php endif; ?>>爸爸</b>
				<b data-id="2" <?php if($parent_info["parent_sex"] == 2): ?>class="b_checked"<?php endif; ?>>妈妈</b>
				<b data-id="3" <?php if($parent_info["parent_sex"] == 3): ?>class="b_checked"<?php endif; ?>>其他</b>
			</span>
				<input type="hidden" id="guanx" name="parent_sex" value="2" />
				<input type="hidden" id="parent_id" name="parent_id" value="<?php echo ($parent_info["parent_id"]); ?>"/>
				<input type="hidden" id="student_id" name="student_id" value="<?php echo ($student_id); ?>"/>
			</li>
			<li>
				<span class="s_label">手机号码</span>
			<span class="s_input ">
				<input type="tel" name="parent_mobile" id="parent_mobile" value="<?php echo ($parent_info["parent_mobile"]); ?>" placeholder="" maxlength="11" <?php if(($type) != "1"): ?>readonly<?php endif; ?> />
			</span>
			</li>
			<?php if(($type) == "1"): ?><li>
					<span class="s_label">验证码</span>
			<span class="s_input ">
				<input type="text" id="code" name="code"/>
				<button type="button" id="sendcode" class="getcode">获取验证码</button>
			</span>
				</li><?php endif; ?>
			<li><span class="s_label">家长姓名</span><span class="s_input "><input type="text" name="parent_name" id="parent_name" value="<?php echo ($parent_info["parent_name"]); ?>" placeholder=""/></span></li>
		</ul>

		<br><br><br>
		<div class="btn_box btn_boxadd"><a href="javascript:void(0);" id="btn_save" class="btn btn2 btn_addjz">保存</a></div>

	</form>

	<a href="<?php echo U('mobile.php/Ucenter/index');?>">
		<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
	</a>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script src="/Public/js/mobiles/layer/layer.js"></script>
<script type="text/javascript">
	//验证码
	var countdown = 60;

	function settime() {
		var obj = $("#sendcode");
		if (countdown == 0) {
			obj.removeAttr("disabled");
			obj.html("获取验证码");
			countdown = 60;
			return;
		} else {
			obj.attr("disabled", 'true');
			obj.html("重新发送(" + countdown + ")");
			countdown--;
		}
		setTimeout(function () {
			settime();
		}, 1000);
	}

	$('#sendcode').click(function () {

		var mobile = $("#parent_mobile").val();
		if (!mobile.match(/^((1[3-8]{1})+\d{9})$/)) {
			layer.msg('手机号不正确',{time:800});
		} else {
			$.post("<?php echo U('mobile.php/Parentregister/sendSMS');?>", {mobile: mobile}, function (data) {
				if (data.status == 1) {
					layer.msg('发送成功',{time:800});
					settime();
				} else {
					layer.msg(data.msg,{time:800});
				}
			}, "json");
		}
	});

	function check()
	{
		var guanx = $("#guanx").val();
		var parent_mobile = $("#parent_mobile").val();
		var parent_name = $("#parent_name").val();

		if(guanx == ''){
			layer.msg('请选择孩子关系',{time:800});
			return false;
		}

		if(parent_mobile == ''){
			layer.msg('请填写手机号',{time:800});
			return false;
		}
		<?php if(($type) == "1"): ?>var code = $('#code').val();
			if(code == ''){
			layer.msg('请输入验证码',{time:800});
			return false;
		}<?php endif; ?>
		if(parent_name == ''){
			layer.msg('请填写家长姓名',{time:800});
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
	});
</script>
</body>
</html>