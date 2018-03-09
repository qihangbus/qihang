<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>教师信息</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body>

<div class="wrap2"> 

	<div class="address_t"><a href="javascript:history.back(-1);"><span>取消</span></a></div>
<div class="line_hr"></div>
<form name="myform" id="myform" action="<?php echo U('mobile.php/MIndex/teacher_handle');?>" method="post" onsubmit="return check(this);">
	<ul class="ul_form ul_form_jz">
		
		<input type="hidden" id="teacher_id" name="teacher_id" value="<?php echo ($teacher_id); ?>"/>
		<input type="hidden" id="admin_id" name="admin_id" value="<?php echo ($user_id); ?>"/>
		<li>
			<span class="s_label">年级</span>
			<span class="s_input ">
				<select name="grade" id="grade">
					<?php if(is_array($grade_list)): $i = 0; $__LIST__ = $grade_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["grade_id"]); ?>" <?php if($vo['grade_id'] == $info['grade_id']): ?>selected<?php endif; ?>><?php echo ($vo["grade_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</span>
		</li>
		<li>
			<span class="s_label">班级</span>
			<span class="s_input">
				<select name="class" id="class">
					<?php if(is_array($class_list)): $i = 0; $__LIST__ = $class_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["class_id"]); ?>" <?php if($vo['class_id'] == $info['class_id']): ?>selected<?php endif; ?>><?php echo ($vo["class_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</span>
		</li>
		<li><span class="s_label">手机号码</span><span class="s_input "><input type="tel" name="teacher_mobile" id="teacher_mobile" value="<?php echo ($info["teacher_mobile"]); ?>" placeholder="" maxlength="11"  /></span></li>
		<li><span class="s_label">教师姓名</span><span class="s_input "><input type="text" name="teacher_name" id="teacher_name" value="<?php echo ($info["teacher_name"]); ?>" placeholder=""/></span></li>
	</ul>	

	<br><br><br>
	<div class="btn_box btn_boxadd"><a href="javascript:void(0);" id="btn_save" class="btn btn2 btn_addjz">保存</a></div>

</form>

<a href="<?php echo U('index',array('id'=>$user_id));?>">
<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
</a>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script type="text/javascript">
function check()
{
	var teacher_mobile = $("#teacher_mobile").val();
	var teacher_name = $("#teacher_name").val();

	if(teacher_mobile == ''){
		layer.open({
            title:false,
            content:'手机号码不能为空',
            btn:['关闭'],
            yes:function(index){
                layer.closeAll();
            },
        });
		return false;
	}

	if(teacher_name == ''){

		layer.open({
            title:false,
            content:'教师姓名不能为空',
            btn:['关闭'],
            yes:function(index){
                layer.closeAll();
            },
        });

		return false;
	}
	return true;
}

$(function(){

$("#grade").change(function(){
	var grade = $("#grade").val();
	$.post("<?php echo U('mobile.php/MIndex/ajax_class');?>",{grade_id:grade},function(result){
		$("#class")[0].options.length = 0;
		$("#class")[0].options.add(new Option("请选择",""));
		for (var i = 0; i < result.length; i++) {	
			var date = result[i];
            $("#class")[0].options.add(new Option(date.class_name, date.class_id));
		}
	},'json');
});

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