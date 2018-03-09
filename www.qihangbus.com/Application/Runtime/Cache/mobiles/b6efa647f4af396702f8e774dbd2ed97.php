<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>身份选择</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
</head>
<body class="mbg">
<div class="wrap2"> 
	<dl class="jz_check">
		<dt>您是孩子的？</dt>
		<dd class="dd_item" data-id="1"><span class="s_icon s_icon_bb"></span><b class="b_text">爸爸</b></dd>
		<dd class="dd_item" data-id="2"><span class="s_icon s_icon_mm"></span><b class="b_text">妈妈</b></dd>
		<dd class="dd_item" data-id="3"><span class="s_icon s_icon_qt"></span><b class="b_text">其他</b></dd>
		<input type="hidden" id="guanx" value="" />
		<input type="hidden" id="parent_id" value="<?php echo ($parent_id); ?>">
		<input type="hidden" id="student_id" value="<?php echo ($student_id); ?>">
	</dl>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script type="text/javascript">
 $(function(){
	$(".jz_check .dd_item").click(function(){
		$("#guanx").val($(this).attr("data-id"));
		$(this).addClass("dd_item_ck").siblings(".dd_item").removeClass("dd_item_ck");
		var sex = $(this).attr("data-id");
		var parent_id = $('#parent_id').val();
		var student_id = $('#student_id').val();
		$.post("<?php echo U('mobile.php/Parentlogin/edit_sex');?>",{sex:sex,parent_id:parent_id,student_id:student_id},function(result){
			if(result){
//				window.location.href = "/mobile.php?m=mobile.php&c=Parentlogin&a=getChildrenInfo&student_id="+result;
				window.location.href = "<?php echo U('mobile.php/Ucenter/index',['pay_tips' => 1]);?>";

			}
		});
	})
  })
</script>
</body>
</html>