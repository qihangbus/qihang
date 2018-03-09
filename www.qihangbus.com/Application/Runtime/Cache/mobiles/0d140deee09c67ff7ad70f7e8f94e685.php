<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-dns-prefetch-control" content="on">
	<meta name="viewport"
		  content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<meta name="renderer" content="webkit">
	<title>园长-首页</title>

	<link rel="stylesheet" href="/Public/css/mobiles/base.css">
	<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
	<link rel="stylesheet" href="/Public/css/mobiles/app.css">
	<link rel="stylesheet" href="/Public/css/mobiles/swiper.min.css">
	<style type="text/css">
		.icon-huan{position:relative;top:3px;}
	</style>
	<link rel="stylesheet" href="/Public/css/mobiles/font-awesome/css/font-awesome.min.css">
</head>

<body>

<div class="wrap2 have-bottom-swiper">
	<div class="yuanzhang_t">
		<dl>
			<dt>
				<a href="<?php echo U('mobile.php/SIndex/avatar',array('user_id'=>$userinfo['student_id'],'user_flag'=>1));?>">
					<img src="<?php echo ((isset($data["teacher_avatar"]) && ($data["teacher_avatar"] !== ""))?($data["teacher_avatar"]):'/Public/images/mobiles/default.png'); ?>" alt="background_image" />
				</a>
			</dt>
			<dd>

				<div class="d_name">
					<?php echo ($data["school_leader"]); ?>
				</div>

			</dd>
		</dl>
		<div class="head-top"><?php echo ($data["school_name"]); ?></div>
	</div>
	<ul class="m_menu clearfix">
		<li class="li_item">
			<a href="mobile.php?m=mobile.php&c=Library&a=detail&id=<?php echo ($data["school_id"]); ?>">
				<span class="iconfont icon-4259"></span>
				<span class="m_menu-txt">图书馆</span>
			</a>
		</li>
		<li class="li_item">
			<a href="mobile.php?m=mobile.php&c=Teacher&a=index&id=<?php echo ($data["school_id"]); ?>">
				<span class="iconfont icon-jiaoshifengcai"></span>
				<span class="m_menu-txt">教师信息</span>
			</a>
		</li>
		<li class="li_item">
			<a href="mobile.php?m=mobile.php&c=Student&a=index&id=<?php echo ($data["school_id"]); ?>">
				<span class="iconfont icon-xuesheng"></span>
				<span class="m_menu-txt">学生信息</span>
			</a>
		</li>
		<li class="li_item">
			<a href="<?php echo U('Datainfo/index',array('id'=>$id));?>">
				<span class="iconfont icon-shuju1"></span>
				<span class="m_menu-txt">数据统计</span>
			</a>
		</li>
	<!--
		<li class="li_item ">
			<a href="mobile.php?m=mobile.php&c=Cart&a=index&user_id=<?php echo ($userinfo["student_id"]); ?>&user_flag=1">
				<span class="iconfont icon-mangouwuche"></span>
				<span class="m_menu-txt">购物车</span>
			</a>
		</li>
		<li class="li_item">
			<a href="mobile.php?m=mobile.php&c=Order&a=index&user_id=<?php echo ($userinfo["student_id"]); ?>&user_flag=1">
				<span class="iconfont icon-dingdan"></span>
				<span class="m_menu-txt">订单</span>
			</a>
		</li>
	-->
		<li class="li_item ">
			<a href="<?php echo U('mobile.php/SIndex/circul',array('id'=>$userinfo['student_id']));?>">
				<span class="iconfont icon-huan"></span>
				<span class="m_menu-txt">轮换日期</span>
			</a>
		</li>
		<li class="li_item <?php if($userinfo["message_num"] > '0'): ?>li_item_p<?php endif; ?>">
		<a href="<?php echo U('SIndex/get_message_list',array('user_id'=>$userinfo['student_id'],'user_flag'=>1));?>">
			<span class="iconfont icon-xiaoxi"></span>
			<span class="m_menu-txt">消息</span>
		</a>
		</li>

		<li class="li_item ">
			<a href="/default.php/Live">
				<span class="fa fa-graduation-cap" style="font-size:39px;margin-top: 3px;color:#ff9d4d"></span>
				<span class="m_menu-txt">专家讲堂</span>
			</a>
		</li>

		<li class="li_item ">
			<a href="/sns.php?m=sns.php&c=forum&a=bbs">
				<span class="fa fa-group" style="font-size:39px;margin-top: 3px;color:#C492DC"></span>
				<span class="m_menu-txt">论坛</span>
			</a>
		</li>
		<li class="li_item ">
			<a href="<?php echo U('SIndex/setting',array('user_id'=>$userinfo['student_id'],'user_flag'=>1));?>">
				<span class="iconfont icon-shezhi"></span>
				<span class="m_menu-txt">设置</span>
			</a>
		</li>
	</ul>

</div>
<!--
<div class="footer2">
	<div class="swiper-home swiper-container">
		<div class="swiper-wrapper">
			<div class="swiper-slide">
				<img src="/Public/images/mobiles/ban_mx.jpg">
			</div>
		</div>
		<div class="swiperp_box"><div class="swiper-pagination"></div></div>
		<div class="swiper-button-next"></div>
		<div class="swiper-button-prev"></div>
	</div>
</div>
-->
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/lib/swiper.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script type="text/javascript">
	$(function() {
		var swiper = new Swiper('.swiper-container', {
			pagination : '.swiper-pagination',
			nextButton : '.swiper-button-next',
			prevButton : '.swiper-button-prev',
			loop : true,
			paginationClickable : true
		});
		var bHeight = $('.swiper-home').height();
		$('.have-bottom-swiper').css("bottom",bHeight);


		$("#signin").click(function(){
			var uid = $(this).attr('uid');
			var num = $("#signnum").val();
			var uflag = 1;
			$.post("<?php echo U('mobile.php/SIndex/signin');?>",{user_id:uid,user_flag:uflag,signnum:num},function(ret){
				if(ret.code > 0){


					layer.open({
						title:false,
						content:'签到成功,本次签到获得'+ret.user_points+'金豆',
						btn:['关闭'],
						yes:function(index){
							layer.closeAll();
							location.reload();
						},
					});
				}
			},'json');
		});
	})
</script>
</body>

</html>