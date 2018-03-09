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
	<link href="/Public/css/mobiles/main.min.css?v=<?php echo rand(0,100);?>" rel="stylesheet">
	<link href="/Public/css/mobiles/app.css" rel="stylesheet">
	<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body>
<div id="default-wrapper" class="default-wrapper" style="visibility: visible;">
	<div class="default-timeline-wrapper">

		<div class="timeline-hd layout-box" style="background-color: rgb(248, 126, 78);">
			<div class="banner-cover"></div>
			<a class="shequ-img1 logo-w70 avatar"><img src="/Public/images/mobiles/default.png" class="community-logo"></a>
			<div class="shequ-detail box-col itemList">
				<div>
					<div class="shequ-info un-visible" style="visibility: visible;">
						<span class="detail-item">帖子 <?php echo ($num); ?></span>
						<span class="detail-item">成员 <?php echo ($member_num); ?></span>
						<span class="detail-item">访问 <?php echo ($hit_num); ?></span>
					</div>
				</div>
			</div>
		</div>

		<!--<div id="advertContent" style="height: 60px; background-position: center center; background-repeat: no-repeat; background-image: url('/Public/images/mobiles/picad.png'); transform-origin: 0px 0px 0px; opacity: 1; transform: scale(1, 1);" class="advertA"></div>-->
		<div class="contentArea">
			<div id="msg-list-wrapper" class="msg-list-wrapper" style="margin-bottom:1rem;">

				<ul id="msgList" class="msg-wrap">
					<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="msg j-msg">
							<div class="po-over-test">
								<header class="msg-infor">
									<div class="user layout-box">
										<div class="readNum heig">
											<a href="<?php echo U('sns.php/Forum/bbs_info',array('id'=>$vo['forum_id']));?>">
												<span class="look-number iconfont"></span>
												<span class="like-count"><?php echo ($vo["hit"]); ?></span>
											</a>
										</div>
										<a class="avatar" href="<?php echo U('sns.php/Forum/bbs_info',array('id'=>$vo['forum_id'],'school_id'=>$school_id,'user_id'=>$user_id,'user_flag'=>$user_flag,'fake_id'=>$fake_id));?>" style="width:0.8rem;margin:0 0;text-align: center;">
											<img class="avatar-img logo-w35" src="<?php echo ((isset($vo["thumb"]) && ($vo["thumb"] !== ""))?($vo["thumb"]):'/Public/images/mobiles/default.png'); ?>">
											<span style="color: #98a1a8;font-size: .2rem;position: relative;top: -3px;"><?php echo ($vo["name"]); ?></span>
										</a>
										<div class="msgmsg-corner-tip -con itemList box-col">
											<a href="<?php echo U('sns.php/Forum/bbs_info',array('id'=>$vo['forum_id'],'school_id'=>$school_id,'user_id'=>$user_id,'user_flag'=>$user_flag,'fake_id'=>$fake_id));?>" class="name ellipsis">
												<span class="namedom"><?php echo ($vo["title"]); ?></span>
											</a>
											<div>
												<span class="time" style="height: auto;"> <?php echo (date('Y-m-d H:i:s',$vo["add_time"])); ?></span>
											</div>
										</div>
										<div class="zhanwei"></div>
									</div>
								</header>
								<section class="weibo-detail">
								<pre class="forum-name">
									<a href="<?php echo U('sns.php/Forum/bbs_info',array('id'=>$vo['forum_id'],'school_id'=>$school_id,'user_id'=>$user_id,'user_flag'=>$user_flag,'fake_id'=>$fake_id));?>">
										<span class="msg-content" style="text-indent: 2em;margin-bottom: 0;position:relative;top:-10px;"><?php echo (msubstr($vo["description"],0,80)); ?></span>
									</a>
								</pre>
									<div class="pro-rt">           </div>
								</section>
								<footer class="user-compose">
									<div class="tplTo"></div>
									<ul class="count more-detail">
										<li><?php if($user_flag == 4): ?><a href="javascript:;" class="bbs_del" id="<?php echo ($vo['forum_id']); ?>"><img src="/Public/images/mobiles/del.png" style="width:0.4rem;margin-bottom:-7px;"></a><?php endif; ?></li>
										<li><div class="comment-btn heig"><i class="cmt-icon iconfont"></i><span class="like-count cmt-count"><?php echo ($vo["pj_num"]); ?></span></div></li>
										<li><div class="like heig"><span class="like-txt  b-s-contain" fid="<?php echo ($vo['forum_id']); ?>"></span><span class="like-count"><?php echo ($vo["zan"]); ?></span></div></a></li>
									</ul>
								</footer>
							</div>

							<div class="cmt-card none"><ul class="cmt media-main"></ul></div>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
				</footer>
			</div>

			<div class="cmt-card none"><ul class="cmt media-main"></ul></div>
			</li>
			</ul>
		</div>
	</div>
</div>
</div>
<div class="foot" style="display: block; transform-origin: 0px 0px 0px; opacity: 1; transform: scale(1, 1);">
	<a id="showSendMsg" href="<?php echo U('sns.php/Forum/send_forum',array('school_id'=>$school_id,'user_id'=>$user_id,'user_flag'=>$user_flag,'fake_id'=>$fake_id));?>" class="b-s-contain showSendMsg"><i class="iconfont goHomeIcon"></i><span>发帖子</span></a>
	<span class="searchBtn searchIconBtn"><i class="iconfont"></i><a href="#"></a></span>
	<a href="#" class="personal b-s-contain">
		<img src="<?php echo ((isset($thumb) && ($thumb !== ""))?($thumb):'/Public/images/mobiles/default.png'); ?>" class="logo-w35" style="width:100%;height:100%;">
		<span class="red-circle"></span>
	</a>
</div>

<a href="<?php echo ($index_url); ?>">
	<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
</a>

<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<link href="/Public/css/mobiles/dropload.css" rel="stylesheet">
<script type="text/javascript" src="/Public/js/mobiles/dropload.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script type="text/javascript">
	$(function(){
		$(".bbs_del").on("click",function(){
			var fid = $(this).attr("id");
			layer.open({
				title:false,
				content:'删除当前帖子？',
				btn:['确定','取消'],
				yes:function(index){
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
			var fid = $(this).attr("fid");

			$.ajax({
				type: 'GET',
				url: "/sns.php?m=sns.php/forum.php&c=Forum&a=ajax_zan&forum_id="+fid,
				dataType: 'text',
				success: function(data){
					layer.open({
						title:false,
						content:'操作成功',
						btn:['关闭'],
						yes:function(index){
							layer.closeAll();
							location.reload();
						},
					});
				},
			});
		});

		var counter = 0;
		var num = 4;
		var p = 2;
		var pageStart = 0,pageEnd = 0;
		var cate = "<?php echo ($cate); ?>";
		var number = "<?php echo ($number); ?>";

		if(number >= 5){
			$('#msg-list-wrapper').dropload({
				scrollArea : window,
				domUp : {
					domClass   : 'dropload-up',
					domRefresh : '<div class="dropload-refresh">下拉刷新</div>',
					domUpdate  : '<div class="dropload-update">释放更新</div>',
					domLoad    : '<div class="dropload-load"><span class="loading"></span>加载中...</div>'
				},
				domDown : {
					domClass   : 'dropload-down',
					domRefresh : '<div class="dropload-refresh">&nbsp;</div>',
					domLoad    : '<div class="dropload-load"><span class="loading"></span>加载中...</div>',
					domNoData  : '<div class="dropload-noData">&nbsp;</div>'
				},
				loadDownFn : function(me){
					$.ajax({
						type: 'GET',
						url: "/sns.php?m=sns.php/forum.php&c=forum&a=ajax_more&cate_id="+cate+"&p="+p,
						dataType: 'json',
						success: function(data){
							var result = '';
							for(var i = 0; i < data.lists.length; i++){
								result += '<li class="msg j-msg">'
										+'<div class="po-over-test">'
										+'<header class="msg-infor">'
										+'<div class="user layout-box">'
										+'<a class="avatar" href="'+data.lists[i].link+'">'
										+'<img class="avatar-img logo-w35" src="'+data.lists[i].pic+'">'
										+'</a>'
										+'<div class="msgmsg-corner-tip -con itemList box-col">'
										+'<a href="'+data.lists[i].link+'" class="name ellipsis">'
										+'<span class="namedom">'+data.lists[i].title+'</span>'
										+'</a>'
										+'<div>'
										+'<span class="time">'+data.lists[i].time_formated+'</span>'
										+'</div>'
										+'</div>'
										+'<div class="zhanwei"></div>'
										+'</div>'
										+'</header>'
										+'<div class="weibo-detail">'
										+'<div class="forum-name othercss">'
										+'<a href="'+data.lists[i].link+'"><span class="msg-content">'+data.lists[i].description+'</span></a>'
										+'</div>'
										+'</div>'
										+'</div>'
										+'<div class="cmt-card none"><ul class="cmt media-main"></ul></div>'
										+'</li>';

								if(data.lists.length < 1){
									// 锁定
									me.lock();
									// 无数据
									me.noData();
									break;
								}
							}

							if(data.lists.length < 1){
								me.lock();
								me.noData(false);
							}else{
								p++;
							}
							// 为了测试，延迟1秒加载
							setTimeout(function(){
								$('#msgList').append(result);
								// 每次数据加载完，必须重置
								me.resetload();
							},1000);
						},
						error: function(xhr, type){
							alert('加载数据出错!');
							// 即使加载出错，也得重置
							me.resetload();
						}
					});
				},
				threshold : 50
			});
		}
	})
</script>
</body>
</html>