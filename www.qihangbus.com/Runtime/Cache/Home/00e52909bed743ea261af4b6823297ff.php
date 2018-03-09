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
    <title><?php echo C('site_title');?></title>
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
        <div id="indexPage">
            <div id="mslider">
                <ul class="slider">
                    <?php if(is_array($banner)): foreach($banner as $key=>$v): ?><li style="background-image:url(<?php echo ($v["image"]); ?>)">
                        <a href="<?php echo ($v["url"]); ?>">
                            <div><p class="title ellipsis"><?php echo ($v["name"]); ?></p></div>
                        </a>
                    </li><?php endforeach; endif; ?>
                </ul>
            </div>
            <div id="mproject" class="module">
                <div class="content">
                    <div class="header">
                        <p class="title">启航动态</p>
                        <p class="subtitle">Company dynamics</p>
                    </div>
                    <div id="projectlist">
                        <div class="wrapper plr5">
                            <?php if(is_array($company_news)): foreach($company_news as $key=>$v): ?><div class="projectitem wow fadeIn">
                                <a href="<?php echo U('List/view',['id'=>$v['id']]);?>">
                                    <img src="<?php echo ($v["image"]); ?>" width="500" height="320"/>
                                    <div class="project_info">
                                        <div>
                                            <p class="title"><?php echo ($v["title"]); ?></p>
                                        </div>
                                    </div>
                                </a>
                            </div><?php endforeach; endif; ?>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <a href="<?php echo U('List/index',['id'=>48]);?>" id="projectmore">MORE</a></div>
            </div>
            <div id="mnews" class="module">
                <div class="content">
                    <div class="header">
                        <p class="title">行业资讯</p>
                        <p class="subtitle">Industry news</p>
                    </div>
                    <div id="newslist">
                        <?php if(is_array($industry_news)): foreach($industry_news as $key=>$v): ?><div class="newstitem plr10 wow fadeIn" data-wow-delay="0.0s">
                            <a class="newsinfo" href="<?php echo U('List/view',['id'=>$v['id']]);?>">
                                <div class="newsdate">
                                    <p class="md"><?php echo (date('m-d',$v["addtime"])); ?></p>
                                    <p class="year"><?php echo (date('Y',$v["addtime"])); ?></p>
                                </div>
                                <div class="newsbody">
                                    <p class="title ellipsis"><?php echo ($v["title"]); ?></p>
                                    <p class="description">
                                        <?php echo ($v["description"]); ?>
                                    </p>
                                </div>
                            </a>
                        </div><?php endforeach; endif; ?>
                    </div>
                    <div class="clear"></div>
                    <a href="<?php echo U('List/index',['id'=>49]);?>" class="more">MORE</a>
                    <div style="height:0">&nbsp;</div>
                </div>
            </div>
            <div id="mpage" class="module">
                <div class="content">
                    <div class="plr10">
                        <div class="header">
                            <p class="title">关于我们</p>
                            <p class="subtitle">About US</p>
                        </div>
                        <div class="description">
                            <?php echo ($about); ?>
                        </div>
                    </div>
                    <a href="<?php echo U('List/index',['id'=>56]);?>" class="more">MORE</a>
                    <div class="fimg wow fadeIn" style="background-image:url()"></div>
                    <div style="height:0">&nbsp;</div>
                </div>
            </div>
            <div id="mcontact" class="module">
                <div class="content plr10 wow fadeIn">
                    <div class="header">
                        <p class="title">招商加盟</p>
                        <p class="subtitle">JOIN US</p>
                    </div>
                    <div id="contactlist">
                        <div id="contactinfo">
                            <h3 class="ellipsis"><?php echo C('site_name');?></h3>
                            <p class="ellipsis">客服电话：<a href="tel:<?php echo C('site_tel');?>"><?php echo C('site_tel');?></a></p>
                            <p class="ellipsis">加盟专线：<a href="tel:<?php echo C('site_join');?>"><?php echo C('site_join');?></a></p>
                            <p class="" style="text-overflow: ellipsis;display: inline-block;max-width: 100%;">地点：<?php echo C('site_addr');?></p>

                        </div>
                    </div>
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
<script type="text/javascript" src="/Public/2017/js/org.min.js" data-main="IndexMain"></script>
</html>