<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>排名</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>
<body class="mbg">
<div id="share" style="display:none;">
	<div class="i"><img src="/Public/images/mobiles/share.png"></div>
	<div class="f">
		点击右上角，进行分享操作
		<p>点击关闭</p>
	</div>
</div>
<div class="wrap2" style="position:relative;z-index:1;"> 

<dl class="dl_paim">
	<dt><img src="<?php echo ((isset($userinfo["student_avatar"]) && ($userinfo["student_avatar"] !== ""))?($userinfo["student_avatar"]):'/Public/images/mobiles/default.png'); ?>" alt="" /></dt>
	<dd class="color2 dd_paim">您当前排名在<span class="color1"><?php echo ($student_per); ?></span>小朋友之前</dd>
	<dd class="dd_name"><?php echo ($userinfo["student_name"]); ?></dd>
	<dd class="dd_leij">累计拥有<span class="color2"><?php echo ($student_points); ?></span>颗金豆</dd>
	<dd class="dd_share"><span class="ic_share"></span></dd>
</dl>

<a href="<?php echo U('mobile.php/Ucenter/index');?>">
<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
</a>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script type="text/javascript">
$(function(){
	$(".ic_share").click(function(){
		layer.open({
			className: 'popuoshare',
			content: '<dl class="dl_share"><dt>点击右上角，进行分享操作</dt><dd class="dd_btn"><a href="javascript:;" onclick="layer.closeAll()" class="">点击关闭</a></dd></dl>',
			shadeClose: false,
			shade: 'background:url(/Public/images/mobiles/sharebg.png) no-repeat right top;background-size:40% auto;background-color: rgba(0,0,0,.8)'
		});
	});
})	

wx.config({
	debug: false,
	appId: "<?php echo ($signPackage['appId']); ?>",
	timestamp: "<?php echo ($signPackage['timestamp']); ?>",
	nonceStr: "<?php echo ($signPackage['nonceStr']); ?>",
	signature: "<?php echo ($signPackage['signature']); ?>",
	jsApiList: [
	  	'onMenuShareTimeline',
	  	'onMenuShareAppMessage',
		'onMenuShareQQ',
		'onMenuShareWeibo',
		'onMenuShareQZone'
	]
});

wx.ready(function () {
	wx.onMenuShareTimeline({
	    title: '扫码关注',
	    desc:'启萌巴士分享链接',
	    link: 'http://www.chiyingkeji.com/',
	    imgUrl: 'http://www.chiyingkeji.com/Public/images/mobiles/qrcode.jpg',
	    success: function () { 
	        layer.msg('分享成功', {
			 	time: 2000000,
			  	btn: ['关闭'],
			  	yes: function(){
				    layer.closeAll();
				    $(".share").hide();
				}
			});
	    },
	    cancel: function () { 
	        // 用户取消分享后执行的回调函数
	        $(".share").hide();
	    }
	}); 

	wx.onMenuShareAppMessage({
	    title: '扫码关注', // 分享标题
	    desc:'启萌巴士分享链接',
	    link: 'http://www.chiyingkeji.com/',
	    imgUrl: 'http://www.chiyingkeji.com/Public/images/mobiles/qrcode.jpg',
	    success: function () { 
	        layer.msg('分享成功', {
			 	time: 2000000, //20s后自动关闭
			  	btn: ['关闭'],
			  	yes: function(){
				    layer.closeAll();
				}
			});
	    },
	    cancel: function () { 
	        // 用户取消分享后执行的回调函数
	    }
	});	

	wx.onMenuShareQQ({
	    title: '扫码关注', // 分享标题
	    desc:'启萌巴士分享链接',
	    link: 'http://www.chiyingkeji.com/',
	    imgUrl: 'http://www.chiyingkeji.com/Public/images/mobiles/qrcode.jpg',
	    success: function () { 
	       layer.msg('分享成功', {
			 	time: 2000000, //20s后自动关闭
			  	btn: ['关闭'],
			  	yes: function(){
				    layer.closeAll();
				}
			});
	    },
	    cancel: function () { 
	       // 用户取消分享后执行的回调函数
	    }
	});

	wx.onMenuShareWeibo({
	   	title: '扫码关注', // 分享标题
	    desc:'启萌巴士分享链接',
	    link: 'http://www.chiyingkeji.com/',
	    imgUrl: 'http://www.chiyingkeji.com/Public/images/mobiles/qrcode.jpg',
	    success: function () { 
	       layer.msg('分享成功', {
			 	time: 2000000, //20s后自动关闭
			  	btn: ['关闭'],
			  	yes: function(){
				    layer.closeAll();
				}
			});
	    },
	    cancel: function () { 
	        // 用户取消分享后执行的回调函数
	    }
	});


	wx.onMenuShareQZone({
	    title: '扫码关注', // 分享标题
	    desc:'启萌巴士分享链接',
	    link: 'http://www.chiyingkeji.com/',
	    imgUrl: 'http://www.chiyingkeji.com/Public/images/mobiles/qrcode.jpg',
	    success: function () { 
	       layer.msg('分享成功', {
			 	time: 2000000, //20s后自动关闭
			  	btn: ['关闭'],
			  	yes: function(){
				    layer.closeAll();
				}
			});
	    },
	    cancel: function () { 
	        // 用户取消分享后执行的回调函数
	    }
	});
});
</script>
</body>
</html>