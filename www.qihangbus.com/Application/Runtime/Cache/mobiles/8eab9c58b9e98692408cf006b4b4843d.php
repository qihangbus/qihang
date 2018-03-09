<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>通知消息</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
<link rel="stylesheet" href="/Public/css/mobiles/font-awesome/css/font-awesome.min.css">
</head>
<body class="">
<header class="demo-header">
    <div class=" " data-am-widget="navbar" style="height:45px;line-height:35px;"> <span class="head_plus">+</span></div>
</header>
 <div class="a_menu">
 <ul>
 	<li><a href="javascript:void(0);" id="teacher"><span>教师</span></a></li>
 </ul>
 </div>
<div class="wrap2"> 
	
	<div class="xx_list">
	<input type="hidden" id="user_id" value="<?php echo ($user_id); ?>">
	<input type="hidden" id="class_id" value="<?php echo ($class_id); ?>">
	<?php if(empty($list)): ?><div class="data-empty">
		<p><img src="/Public/images/mobiles/empty.png"><p>
		<p>暂无数据</p>
		</div><?php endif; ?>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="d_time"><?php echo ($key); ?></div>
		<?php if(is_array($vo["lt"])): $i = 0; $__LIST__ = $vo["lt"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><dl <?php if(($v["type"]) == "2"): ?>onclick="update_message(<?php echo ($v["message_id"]); ?>);"<?php endif; ?> id="mid<?php echo ($v["message_id"]); ?>" class="dd_xx <?php if($v['readed'] < 1): ?>dd_xx_s<?php endif; ?>">
			<dt>
				<?php if(($v["type"]) == "2"): ?><i class="fa fa-envelope-o"></i> <?php echo ((isset($v["sender_name"]) && ($v["sender_name"] !== ""))?($v["sender_name"]):'系统消息'); ?>
					<?php else: ?>
					<i class="fa fa-paper-plane-o"></i> <?php echo ($v["receiver_name"]); endif; ?>
				<span><?php echo (date("m-d H:i",$v["sent_time"])); ?></span>
			</dt>
		<dd class="dd_p"><?php echo ($v["message"]); ?> <label class="lab" style="display:none;"><?php echo ($v["sub_message"]); ?></label>
		<?php if($v['sub_message'] != ''): ?><a href="javascript:void(0);" class="detail">更多</a><?php endif; ?>
		</dd>
		</dl><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
	</div>
	<a href="<?php echo U('mobile.php/Ucenter/index');?>">
		<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
	</a>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script type="text/javascript">
function update_message(mid)
{
	$.post("<?php echo U('mobile.php/Ucenter/ajax_message');?>",{message_id:mid},function(result){
			if(result > 0){
				$('#mid'+mid).removeClass('dd_xx_s');
			}
	});
}

$(function(){

	$(".detail").click(function(){
		if($(this).text() == '隐藏'){
			$(this).text('更多');
		}else{
			$(this).text('隐藏');
		}
		$(this).prev(".lab").toggle();
	});


	$("#teacher").bind("click",function(){
		var uid = $('#user_id').val();
		var cid = $('#class_id').val();
		window.location.href="/mobile.php?m=&&m=mobile.php&c=Ucenter&a=select_teacher&user_id="+uid+"&class_id="+cid;	
	});
	$(".head_plus").click(function(){
		$(".a_menu").toggle();
	})
})
</script>
</body>
</html>