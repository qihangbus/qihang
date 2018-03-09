<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="effect">
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
	<meta content="yes" name="apple-mobile-web-app-capable">
	<meta content="black" name="apple-mobile-web-app-status-bar-style">
	<meta content="telephone=no" name="format-detection">
	<meta name="keywords" content="">
	<meta name="description" content="">
	<meta name="author" content="UEMO">
	<link type="text/css" href="/Public/2017/css/font-awesome.min.css" rel="stylesheet">
	<link type="text/css" href="/Public/2017/css/bxslider.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/Public/2017/css/animate.min.css">
	<link type="text/css" href="/Public/2017/css/stylem.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/Public/2017/css/380m.css">
	<title>精选绘本_<?php echo C('site_title');?></title>
</head>
<body>
<div id="leftcontrol">
    <ul id="nav">
        <li>
            <div id="closelc" class="fr btn hide">
                <div class="lcbody">
                    <div class="lcitem top">
                        <div class="rect top"></div>
                    </div>
                    <div class="lcitem bottom">
                        <div class="rect bottom"></div>
                    </div>
                </div>
            </div>
        </li>
        <li class="navitem active">
            <a class="transform" href="/default.php">
                <span class="circle transform"></span>
                首页
            </a>
        </li>
        <?php if(is_array($column)): foreach($column as $key=>$v): ?><li class="navitem">
                <?php if(!empty($v["submenu"])): ?><a href="javascript:;" class="hassub">
                        <span class="circle transform"></span>
                        <?php echo ($v["name"]); ?>
                    <span class="more">
                        <span class="h"></span>
                        <span class="h v transform"></span>
                    </span>
                    </a>
                    <ul class="subnav transform" data-height="100">
                        <?php if(is_array($v["submenu"])): foreach($v["submenu"] as $key=>$vv): ?><li><a href="<?php echo U('List/index',['id'=>$vv['id']]);?>"><i class="fa fa-angle-right"></i><?php echo ($vv["name"]); ?></a></li><?php endforeach; endif; ?>
                    </ul>

                    <?php else: ?>
                    <a class="transform" href="<?php if(($v["type"]) == "2"): echo ($v["jump_url"]); else: echo U('List/index',['id'=>$v['id']]); endif; ?>">
                        <span class="circle transform"></span><?php echo ($v["name"]); ?>
                    </a><?php endif; ?>
            </li><?php endforeach; endif; ?>
    </ul>
</div>
<div id="sitecontent" class="transform">
	<div id="header">
		<div id="openlc" class="fl btn">
			<div class="lcbody">
				<div class="lcitem top">
					<div class="rect top"></div>
				</div>
				<div class="lcitem bottom">
					<div class="rect bottom"></div>
				</div>
			</div>
		</div>
		<a id="logo" href="/default.php"><img src="/Public/2017/image/mlogo.png"/></a>
	</div>
	<div class="scrollView">
		<div class="npagePage">
			<div class="content">
				<div id="category" class="dropmenu" style="margin-bottom: 0px;">
					<div class="label plr20"><i class="down fa fa-angle-down transform"></i>
						<div class="text">全部</div>
					</div>
					<ul class="transform" data-height_bak="164" data-height="auto" style="height: 0px;">
						<li><a href="/default.php/Home/Book/index" class="active">全部</a></li>

						<li><a href="/default.php/Home/Book/index/type/2">小班</a></li>

						<li><a href="/default.php/Home/Book/index/type/3">中班</a></li>

						<li><a href="/default.php/Home/Book/index/type/4">大班</a></li>

					</ul>
				</div>
				<div id="projectlist" style="margin-top:3px;">
					<div class="wrapper plr5">
						<?php if(is_array($data)): foreach($data as $key=>$v): ?><div class="projectitem wow fadeIn" style="visibility: visible; animation-name: slideInUp;">
							<a href="<?php echo U('view',['id'=>$v['book_id']]);?>">
								<img src="<?php echo ($v["book_thumb"]); ?>" width="500" height="320">
								<div class="project_info">
									<div>
										<p class="title"><?php echo ($v["book_name"]); ?></p>
										<p class="subtitle"><?php echo ($v["book_author"]); ?></p>
									</div>
								</div>
							</a>
						</div><?php endforeach; endif; ?>
					</div>
				</div>
				<div class="clear"></div>
				<div id="pages">
					<?php echo ($page); ?>
				</div>
			</div>
		</div>
		<div id="footer">
    <p class="plr10"><span>COPYRIGHT © 2016-2017 启航巴士幼儿亲子读书计划&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
</div>
		<div id="bgmask" class="iPage hide"></div>
	</div>
</div>
</body>
<script type="text/javascript">var YYConfig = {};</script>
<script type="text/javascript" src="/Public/2017/js/zepto.min.js"></script>
<script type="text/javascript" src="/Public/2017/js/zepto.bxslider.min.js"></script>
<script type="text/javascript" src="/Public/2017/js/wow.min.js"></script>
<script type="text/javascript" src="/Public/2017/js/masonry_4.min.js"></script>
<script type="text/javascript">$(function () {
	new WOW({scrollContainer: ".scrollView"}).init();
})</script>
<script type="text/javascript" src="/Public/2017/js/org.min.js" data-main="ListMain"></script>
</html>