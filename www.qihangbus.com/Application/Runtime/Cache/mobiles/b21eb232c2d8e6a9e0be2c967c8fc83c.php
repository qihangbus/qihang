<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp" />
<meta name="renderer" content="webkit">
<title>首页</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
<link rel="stylesheet" href="/Public/css/mobiles/swiper.min.css">
<style type="text/css">
	.icon-huan{position:relative;top:3px;}
	.badge {
		display: inline-block;
		font-weight: 700;
		color: #fff;
		line-height: 1;
		border-radius: 10px;
		font-size: 12px;
		padding-top: 1px;
		padding-bottom: 3px;
		position: absolute;
		top: 41%;
		left: 57%;
		padding-right: 5px;
		padding-left: 5px;
	}
	.badge-success, .label-success {
		background-color: #82af6f!important;
	}
</style>
<link rel="stylesheet" href="/Public/css/mobiles/font-awesome/css/font-awesome.min.css">
</head>
<body>
	<div class="wrap2 have-bottom-swiper">
		<div class="yuanzhang_t">
			<dl>
				<dt>
					<a href="<?php echo U('avatar');?>">
					<img src="<?php echo ((isset($data["avatar"]) && ($data["avatar"] !== ""))?($data["avatar"]):'/Public/images/mobiles/default.png'); ?>" alt="background_image" />
					</a>
				</dt>
				<dd>
					<div class="d_name">
						<?php echo ($data["name"]); ?>
					</div>
				</dd>
			</dl>
		</div>
		<ul class="m_menu clearfix">
			<li class="li_item">
			<a href="<?php echo U('school');?>">
				<!--<span class="iconfont icon-4259"></span>-->
				<i class="fa fa-university" style="color:#f087a2;font-size:32px;margin-top: 6px;"></i>
				<span class="badge badge-success"><?php echo ($school_count); ?></span>
				<span class="m_menu-txt">幼儿园</span>
			</a>
			</li>
			<li class="li_item">
				<a href="<?php echo U('school',array('type'=>1));?>">
					<i class="fa fa-line-chart" style="color:#F69A27;font-size:32px;margin-top: 6px;"></i>
					<span class="badge badge-success" style="background-color:#f66!important"><?php echo ($not_count); ?></span>
					<span class="m_menu-txt">未达标</span>
				</a>
			</li>
			<li class="li_item <?php if($userinfo["message_num"] > '0'): ?>li_item_p<?php endif; ?>">
				<a href="<?php echo U('message',array('user_id'=>$userinfo['student_id'],'user_flag'=>1));?>">
				<span class="iconfont icon-xiaoxi"></span>
					<span class="m_menu-txt">消息</span>
				</a>
			</li>
			<li class="li_item ">
				<a href="<?php echo U('setting');?>">
				<span class="iconfont icon-shezhi"></span>
					<span class="m_menu-txt">设置</span>
				</a>
			</li>

			<li class="li_item ">
				<a href="/default.php/Live">
					<span class="fa fa-graduation-cap" style="font-size:39px;margin-top: 3px;color:#ff9d4d"></span>
					<span class="m_menu-txt">专家讲堂</span>
				</a>
			</li>

			<li class="li_item ">
				<a href="/default.php/Live/Index/trainAgent">
					<span class="fa fa-group" style="font-size:39px;margin-top: 3px;color:#FF9A9A"></span>
					<span class="m_menu-txt">在线培训</span>
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