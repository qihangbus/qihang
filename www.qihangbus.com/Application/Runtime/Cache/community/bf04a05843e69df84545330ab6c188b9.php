<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html data-dpr="2" style="font-size: 64.6875px;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title>启航巴士幼儿亲子读书计划，<?php echo ($info["title"]); ?></title>
<link href="/Public/css/mobiles/main.min.css?v=<?php echo rand(0,100);?>" rel="stylesheet">
<link href="/Public/css/mobiles/swiper.min.css" rel="stylesheet">
<script type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script type="text/javascript" src="/Public/js/mobiles/lib/swiper.min.js"></script>

</head>
<body>
<div id="default-wrapper" class="default-wrapper" style="visibility: visible;">
	<div class="default-timeline-wrapper">
		<div class="contentArea">
			<div id="msg-list-wrapper" class="msg-list-wrapper">
				
			<ul id="msgList" class="msg-wrap">
					
					<li class="msg j-msg">
						<div class="po-over-test">
							<header class="msg-infor">
								<div class="user layout-box">

									<a class="avatar" href="#" style="width:0.8rem;margin:0 0;text-align: center;">
										<img class="avatar-img logo-w35" src="<?php echo ((isset($info["avatar"]) && ($info["avatar"] !== ""))?($info["avatar"]):'/Public/images/mobiles/default.png'); ?>">
										<span style="color: #98a1a8;font-size: .2rem;position: relative;top: -3px;"><?php echo ((isset($info["name"]) && ($info["name"] !== ""))?($info["name"]):'启航巴士'); ?></span>
									</a>
									<div class="msgmsg-corner-tip -con itemList box-col">
										<a href="#" class="name ellipsis">
											<span class="namedom"><?php echo ($info["title"]); ?></span>
										</a>
										<div>
											<span class="time" style="height: auto;"> <?php echo (date('Y-m-d H:i:s',$info["add_time"])); ?></span>
										</div>
									</div>
									<div class="zhanwei"></div>
								</div>
							</header>
							<style>.content img{width: 100%}</style>
							<div class="weibo-detail">
								<div class="forum-name othercss">
									<div class="content" style="margin-top:2px;" >
										<span class="msg-content"><?php echo ($info["content"]); ?></span>
									</div>
									<div style="clear: both;">
										<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo ($vo['fp_pic']); ?>"><img src="<?php echo ($vo['fp_pic']); ?>" style="width:33.33%;margin:0 1px 0 1px;height:90px;"></a><?php endforeach; endif; else: echo "" ;endif; ?>
									</div>
								</div>
							</div>
							<footer class="user-compose" style="clear:both;">
								<div class="tplTo"></div>
								<ul class="count more-detail">
									<li><?php if($user_flag == 4): ?><a href="javascript:;" class="bbs_del" id="<?php echo ($info["forum_id"]); ?>"><img src="/Public/images/mobiles/del.png" style="width:0.4rem;margin-bottom:-7px;"></a><?php endif; ?></li>
									<li><div class="comment-btn heig"><i class="cmt-icon iconfont"></i><span class="like-count cmt-count"><?php echo ($num); ?></span></div></li>
									<li><div class="like heig"><span class="like-txt  b-s-contain "></span><span class="like-count"><?php echo ($info["zan"]); ?></span></div></li>
								</ul>
							</footer>
						</div>
						<div class="cmt-card none"><ul class="cmt media-main"></ul></div>
					</li>
					
					<?php if(!empty($comments)): ?><li class="j-msg">
						<div class="po-over-test" style="margin-bottom: 60px;">
								<div class="comments">
									<div class="comments_sort"><span class="sequenceBtn"></span>正序</div>
									<div class="comments-num"><span><?php echo ($num); ?></span>条评论</div>
									
								</div>
								<?php if(is_array($comments)): foreach($comments as $key=>$data): ?><div class="user layout-box" style="padding:5px 15px;border-bottom:1px solid #e9ecf1;">			
									
									<img class="avatar-img logo-w35" style="width:0.7rem;height:0.7rem;margin-right:0.1rem;" src="<?php echo ((isset($data["avatar"]) && ($data["avatar"] !== ""))?($data["avatar"]):'/Public/images/mobiles/default.png'); ?>">
									
									<div class=" -con itemList box-col" style="display:inline;">
											<font style="color:#ff964b"><?php echo ($data["name"]); ?></font> <!--回复 <font style="color:#ff964b"><?php echo ($data["title"]); ?></font>-->
											<span class="namedom" style="margin:10px 0px;display:block;"><?php echo ($data["content"]); ?></span>
										
										<div>
												<span style="color: #98a1a8;font-size: .2rem;display: block;margin-top: 3px;overflow: hidden;"><?php echo (date('Y-m-d H:i',$data["add_time"])); ?></span>
											<span class="bktis" style="display:none;">版块：<a class="topic-block j-section-block ellipsis"><?php echo ($info["cate_name"]); ?></a></span>
										</div>
									</div>
									<div class="zhanwei"></div>
								</div><?php endforeach; endif; ?>
							
						</div>
						<div class="cmt-card none"><ul class="cmt media-main"></ul></div>
					</li><?php endif; ?>
			</ul>	
		</div>
	</div>
</div>
</div>
<div class="foot" style="display: block; transform-origin: 0px 0px 0px; opacity: 1; transform: scale(1, 1);">
	<a href="<?php echo U('sns.php/Forum/bbs/',array('user_id'=>$user_id,'user_flag'=>$user_flag,'school_id'=>$school_id,'fake_id'=>$fake_id));?>" class="backHome b-s-contain">
	<span class=""></span>
	</a>
	<a id="showSendMsg" href="<?php echo U('sns.php/Forum/show_comments/',array('id'=>$info['forum_id'],'user_id'=>$user_id,'user_flag'=>$user_flag,'school_id'=>$school_id,'fake_id'=>$fake_id));?>" class="b-s-contain showSendMsg1">
	<div class="comment-btn heig"><i class="cmt-icon iconfont"></i><span class="like-count cmt-count" style="font-size:15px;">评论</span></div>
	</a>
	
	<a href="#" class="personal b-s-contain" style="top: 0.31rem;">
		<span class="like-txt  b-s-contain"></span>
	</a>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script src="/Public/js/mobiles/layer/layer.js"></script>
<script type="text/javascript">
$(function(){
	$(".bbs_del").on("click",function(){
		layer.open({
			title:false,
			content:'删除当前帖子？',
			btn:['确定','取消'],
			yes:function(index){
				var id = $(this).attr("id");
		
				$.ajax({
					type: 'GET',
					url: "/sns.php?m=sns.php/forum.php&c=Forum&a=forum_del&forum_id="+fid,
					dataType: 'text',
					success: function(data){
						layer.open({
							title:false,
							content:'删除成功',
							btn:['关闭'],
							yes:function(index){
								layer.closeAll();
								location.reload();
							},
						});
					},
				});
			},
			btn2:function(index){
				layer.closeAll();
			},
		});
	});

	$(".like-txt").click(function(){
		var fid = "<?php echo ($info["forum_id"]); ?>";
		
		$.ajax({
                type: 'GET',
                url: "/sns.php?m=sns.php/forum.php&c=Forum&a=ajax_zan&forum_id="+fid,
                dataType: 'text',
                success: function(data){
					layer.msg('感谢您的支持！',{icon:6,time:1000},function(){
						location.reload();
					});
                },
            });
	});
});	
</script>	
</body>
</html>