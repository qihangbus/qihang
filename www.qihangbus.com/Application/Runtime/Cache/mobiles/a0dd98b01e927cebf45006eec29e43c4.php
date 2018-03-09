<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-dns-prefetch-control" content="on">
	<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<meta name="renderer" content="webkit">
	<title>任务列表</title>
	<link rel="stylesheet" href="/Public/css/mobiles/base.css">
	<link rel="stylesheet" href="/Public/css/mobiles/app.css">
	<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
	<link rel="stylesheet" href="/Public/css/mobiles/font-awesome/css/font-awesome.min.css">
	<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
	<script type="text/javascript" src="/Public/2017/js/LocalResizeIMG.js"></script>
	<script type="text/javascript" src="/Public/2017/js/patch/mobileBUGFix.mini.js"></script>
	<script type="text/javascript" src="/Public/layer/layer.js"></script>
	<script type="text/javascript" src="/Public/layer/plugin/layer.js"></script>
	<script src="/Public/2017/assets/js/jquery.validate.js" type="text/javascript"></script>
	<style>
		.img {
			width: 5rem;
			height: 5rem;
			background: url(/Public/images/mobiles/z_add.png) no-repeat;
			background-size: 100% 100%;
			margin-right: 0.1rem;
		}
		.img input{
			display:none;
		}
	</style>
</head>
<body>

<div class="wrap2">
	<div class="jiaos_t jiaos_t_j">
		<dl>
			<dt>
				<a href="<?php echo U('mobile.php/Ucenter/avatar',array('user_id'=>$userinfo['student_id'],'user_flag'=>3));?>">
					<img src="<?php echo ((isset($userinfo["student_avatar"]) && ($userinfo["student_avatar"] !== ""))?($userinfo["student_avatar"]):'/Public/images/mobiles/default.png'); ?>" alt=""/>
				</a>
			</dt>
			<dd class="dd_renw">
				<div class="d_name"><?php echo ($userinfo["student_name"]); ?><!-- <?php if($userinfo["student_sex"] == '1'): ?><img src="/Public/images/mobiles/ic_sex_m.png" alt="" />
		<?php else: ?>
		<img src="/Public/images/mobiles/ic_sex_w.png" alt="" /><?php endif; ?> --></div>
			</dd>
		</dl>
	</div>
	<div class="line_hr"></div>
	<div class="renw_t">
		<span>累计完成任务 <?php echo ($task_number); ?> 个<?php if(empty($access)): ?>,答对本次任务可获得 <b style="color:#F57272"><?php echo ($points); ?></b> 金豆<?php endif; ?></span>
	</div>
	<div class="line_hr"></div>
	<div class="renw_list">
		<form name="myform" id="myform" action="<?php echo U('mobile.php/Ucenter/comment_handle');?>" method="post">
			<?php if(is_array($task)): $i = 0; $__LIST__ = $task;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dl class="dl_rw">
					<dt>
						<strong><?php echo ($vo["task_title"]); ?></strong><i style="float:right;color:green;display:none;" id="ch<?php echo ($vo["task_id"]); ?>" class="fa fa-check" aria-hidden="true"></i><i style="float:right;color:red;display:none;" id="cl<?php echo ($vo["task_id"]); ?>" class="fa fa-close" aria-hidden="true"></i>
					</dt>
					<dd class="dd_btn">
						<div style="background-color:#FFF;">
							<?php if(!empty($access)): ?><ul class="">
									<?php if(is_array($vo["option"])): $i = 0; $__LIST__ = $vo["option"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if(($v["correct_value"]) == "1"): ?><li style="line-height:30px;margin-left:10px;color:#82D22B;">
												<input type="radio" name="option_id[<?php echo ($vo["task_id"]); ?>]" value="<?php echo ($v["option_id"]); ?>" <?php if($v['option_id'] == $vo['select_op']): ?>checked<?php endif; ?> >
												<?php echo ($v["option_name"]); ?>
											</li>
											<?php if($v['option_id'] == $vo['select_op']): ?><script type="text/javascript">$("#ch<?php echo ($vo["task_id"]); ?>").show();</script><?php endif; ?>
											<?php else: ?>
											<li style='line-height:30px;margin-left:10px;<?php if($v['option_id'] == $vo['select_op']): ?>color:#EC0B0B;<?php endif; ?>'>
											<input type="radio" name="option_id[<?php echo ($vo["task_id"]); ?>]" value="<?php echo ($v["option_id"]); ?>"  <?php if($v['option_id'] == $vo['select_op']): ?>checked<?php endif; ?> >
											<?php echo ($v["option_name"]); ?>
											</li>
											<?php if($v['option_id'] == $vo['select_op']): ?><script type="text/javascript">$("#cl<?php echo ($vo["task_id"]); ?>").show();</script><?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
								</ul>
								<?php else: ?>
								<ul class="">
									<?php if(is_array($vo["option"])): $i = 0; $__LIST__ = $vo["option"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li style="line-height:30px;margin-left:10px;">
											<input type="radio" name="option_id[<?php echo ($vo["task_id"]); ?>]" value="<?php echo ($v["option_id"]); ?>">
											<?php echo ($v["option_name"]); ?>
										</li><?php endforeach; endif; else: echo "" ;endif; ?>
								</ul><?php endif; ?>
						</div>
					</dd>
				</dl><?php endforeach; endif; else: echo "" ;endif; ?>

			<?php if(!$task){ echo '<div class="btn_box btn_boxadd"><a href="javascript:void(0);" class="btn ibtn_cancel" style="width:100%;line-height:30px;">没有任务</a></div>'; }elseif($task && !$access){ echo '
					<dl class="dl_rw">
						<dt>
							<strong>读书分享</strong>
						</dt>
						<dd class="dd_btn">
							<div style="background-color:#FFF;">
								<textarea name="content" rows="3" style="width:100%;border:none;" placeholder="说几句吧..."></textarea>
							</div>
						</dd>
						<dd class="dd_btn">
							<div class="img" data-id="pic">
								<input id="pic" type="file"/>
							</div>
						</dd>
					</dl>
					'; echo '<div class="btn_box btn_boxadd"><a href="javascript:void(0);" class="btn btn2 btn_addjz">提交</a></div>'; }elseif($task && $access){ echo '
					<dl class="dl_rw">
						<a href="'.U('readShare',['ids'=>$ids]).'">
						<dt>
							<strong style="float:left;">读书分享</strong><span style="float:right;">查看详情</span>
						</dt>
						</a>
					</dl>
					'; echo '<div class="btn_box btn_boxadd"><a href="'.U('taskVideo').'" class="btn btn2" style="width:100%;line-height:30px;">查看额外奖励</a></div>'; } ?>
		</form>
	</div>

	<a href="<?php echo U('mobile.php/Ucenter/index');?>">
		<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
	</a>
</div>

<script type="text/javascript">
	$(function () {
		$(".btn_addjz").click(function () {
			<?php if(is_array($task)): $i = 0; $__LIST__ = $task;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>if(!$("input[name='option_id[<?php echo ($vo["task_id"]); ?>]']:checked").val()){
				layer.msg('请选择答案',{time:800});
					return false;
			}<?php endforeach; endif; else: echo "" ;endif; ?>
			$("#myform").submit();
		});
	});

	$(".img").click(function(){
		var now = $(this);
		var id = now.attr('data-id');
		$('#'+id).localResizeIMG({
			width: 500,
			quality: 1,
			success: function (result) {
				wait = layer.load(2);
				var submitData={
					base64_string:result.clearBase64,
				};
				$.ajax({
					type: "POST",
					url: "<?php echo U('uploadPic');?>",
					data: submitData,
					dataType:"json",
					success: function(data){
						layer.close(wait);
						if (0 == data.status) {
							alert(data.content);
							return false;
						}else{
							layer.msg('上传成功',{time:1000});
							var attstr= '<img src="/'+data.url+'"/><input type="hidden" value="'+data.url+'" name="'+id+'">';
							now.html(attstr);
							return false;
						}
					},
					complete :function(XMLHttpRequest, textStatus){
					},
					error:function(XMLHttpRequest, textStatus, errorThrown){ //上传失败
						layer.close(wait);
//						alert(XMLHttpRequest.status);
//						alert(XMLHttpRequest.readyState);
						alert(textStatus);
					}
				});
			}
		});
		pic.click();
	});
</script>


</body>
</html>