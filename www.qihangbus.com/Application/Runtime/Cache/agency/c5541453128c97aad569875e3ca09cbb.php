<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="renderer" content="webkit">
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/iconfont.css"/> 
    <title>供应商首页</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
    <style type="text/css">
       .d_signin{margin-top: 25px;} 
    </style>
</head>
<body>

    <div class="wrap2 have-bottom-swiper">
		<div class="jiaos_t jiaos_t_j">	
			<div class="head-top"><?php echo ($info["name"]); ?></div>
			<dl>
				<dt>
					<a href="<?php echo U('agency.php/AIndex/avatar',array('id'=>$info['id']));?>">
					<img src="<?php echo ((isset($info["agency_avatar"]) && ($info["agency_avatar"] !== ""))?($info["agency_avatar"]):'/Public/images/mobiles/default.png'); ?>" alt="" />
					</a>
				</dt>
				<dd>
					&nbsp;
				</dd>
			</dl>
		</div>
	
		<div class="head-cate">
			<ul class="ul_3">
				<li><a href="<?php echo U('agency.php/Baseinfo/index',array('id'=>$info['id']));?>">
					<span class="iconfont icon-shiliangzhinengduixiang-01"></span>
					<span class="head-cate-txt">个人信息</span>
				</a></li>
				<li><a href="<?php echo U('agency.php/Account/index',array('id'=>$info['id']));?>">
					<span class="iconfont icon-paiming"></span>
					<span class="head-cate-txt">账户信息</span>
				</a></li>
				<li><a href="<?php echo U('agency.php/Book/index',array('id'=>$info['id']));?>">
					<span class="iconfont icon-duihuan"></span>
					<span class="head-cate-txt">绘本样本</span>
					</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="footer2">
		 <!-- Swiper -->
            <div class="swiper-home swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="/Public/images/mobiles/ban_mx.jpg">
                    </div>
                    <div class="swiper-slide">
                        <img src="/Public/images/mobiles/ban_mx.jpg">
                    </div>
                </div>
                <!-- Add Pagination -->
                <div class="swiperp_box"><div class="swiper-pagination"></div></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        <!-- Swiper -->
		</div>
    <script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
    <script src="/Public/js/mobiles/lib/swiper.min.js"></script>
    <script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
    <script type="text/javascript">
        $(function () {
            var swiper = new Swiper('.swiper-container', {
                pagination: '.swiper-pagination',
                nextButton: '.swiper-button-next',
                prevButton: '.swiper-button-prev',
                loop: true,
                paginationClickable: true
            });

            var bHeight = $('.swiper-home').height();
            $('.have-bottom-swiper').css("bottom",bHeight);

            $("#signin").click(function(){
                var uid = $(this).attr('uid');
                var num = $("#signnum").val();
                var uflag = 2;
                $.post("<?php echo U('mobile.php/TIndex/signin');?>",{user_id:uid,user_flag:uflag,signnum:num},function(ret){
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