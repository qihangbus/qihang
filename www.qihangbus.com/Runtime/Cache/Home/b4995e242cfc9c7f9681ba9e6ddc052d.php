<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta name="renderer" content="webkit">
    <title>绘本详情</title>
    <link rel="stylesheet" href="/Public/css/mobiles/base.css">
    <link rel="stylesheet" href="/Public/css/mobiles/app.css">
    <link rel="stylesheet" href="/Public/css/mobiles/swiper.min.css">
    <link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">

    <link type="text/css" href="/Public/2017/css/font-awesome.min.css" rel="stylesheet">
    <link type="text/css" href="/Public/2017/css/bxslider.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/Public/2017/css/animate.min.css">
    <link type="text/css" href="/Public/2017/css/stylem.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/Public/2017/css/380m.css">
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
                <div class="wrap" style="top:50px;bottom:0px;">
                    <div class="pro-show">
                        <div class="pro-slider">
                            <!-- Swiper -->
                            <div class="swiper-pro swiper-container">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <img src="<?php echo ($data["book_img"]); ?>">
                                    </div>
                                </div>
                                <!-- Add Pagination -->
                                <div class="swiper-pagination"></div>
                            </div>
                            <!-- Swiper -->
                        </div>
                        <div class="line_hr"></div>
                        <ul class="pro-ul-show">
                            <li class="li_name"><strong class="s_name" style="font-size: 16px;"><?php echo ($data["book_name"]); if(!empty($info["sub_name"])): ?>【<?php echo ($data["sub_name"]); ?>】<?php endif; ?></strong></li>
                            <li class="li_price"><strong style="color:#999"><?php echo ($data["book_author"]); ?> </strong></li>
                        </ul>
                        <div class="line_hr"></div>
                        <dl class="dl_protoggle">
                            <dt class="am-accordion-title">作者简介</dt>
                            <dd class="am-accordion-bd">
                                <div class="xx_pro"><p style="text-indent: 2em;"><?php echo ($data["author_desc"]); ?></p></div>
                            </dd>
                        </dl>
                        <div class="line_hr"></div>
                        <dl class="dl_protoggle">
                            <dt class="am-accordion-title">绘本简介</dt>
                            <dd class="am-accordion-bd">
                                <div class="xx_pro"><p  style="text-indent:2em;"><?php echo ($data["book_desc"]); ?></p></div>
                            </dd>
                        </dl>
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
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/lib/swiper.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script type="text/javascript">
    $(function () {

        $(".title_zz").click(function () {
            $("#s_description").toggle();
        })

        var text = $("#togg_text").text();

        var flag = text.length > 40 ? true : false;
        if (flag) {
            $("#togg_text").html("");
            $("#togg_text").append("<p>" + text.substring(0, 40)
                    + "<span id='hide' style='display:none'>" + text.substring(40) + "</span>"
                    + "<a href='javascript:;' id='open' class='color3'> 显示全部</a></p>");
        }

        $("#open").click(function () {
            if (flag) {
                $("#open").text("隐藏");
                $("#hide").show();
                flag = false;
            } else {
                $("#open").text("显示全部");
                $("#hide").hide();
                flag = true;
            }
        });

        $("#addtocart").click(function () {
            var book_num = $("#s_num3").text();
            var book_id = $("#book_id").val();
            var user_id = $("#user_id").val();
            var user_flag = $("#user_flag").val();
            $.post("<?php echo U('mobile.php/Book/add_to_cart');?>", {
                book_id: book_id,
                book_num: book_num,
                user_id: user_id,
                user_flag: user_flag
            }, function (result) {
                if (result > 0) {

                    layer.open({
                        title: false,
                        content: '添加到购物车',
                        btn: ['去购物车', '继续购物'],
                        yes: function (index, layero) {
                            window.location.href = "mobile.php?m=mobile.php&c=Cart&a=index&user_id=" + user_id + "&user_flag=" + user_flag;
                        },
                        btn2: function (index, layero) {
                            //alert("11111");
                            layer.closeAll();
                            $("#s_num3").html(1);
                            //window.location.href="mobile.php?m=mobile.php&c=Book&a=index&user_id="+user_id+"&user_flag="+user_flag+"&user_points="+user_points;


                        }
                    });

                    //alert('添加到购物车');
                    //window.location.href="<?php echo U('mobile.php/Cart/index/user_id/"+user_id+"/user_flag/"+user_flag+"');?>";
                    //window.location.href="mobile.php?m=mobile.php&c=Cart&a=index&user_id="+user_id+"&user_flag="+user_flag;
                }
            });
        });

        $(".e_jian").click(function (event) {
            var n = $(this).parent(".s_plus").find(".s_num").text();
            var num = parseInt(n) - 1;
            if (num == 0) {
                event.stopPropagation();
                return
            }
            $(this).parent(".s_plus").find(".s_num").text(num);
            event.stopPropagation();
        });
        $(".e_jia").click(function (event) {
            var n = $(this).parent(".s_plus").find(".s_num").text();
            var num = parseInt(n) + 1;
            if (num > 1000) {
                event.stopPropagation();
                return
            }
            $(this).parent(".s_plus").find(".s_num").text(num);
            event.stopPropagation();
        });

        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
            loop: true,
            paginationType: 'fraction'
        });
    })
</script>
<script type="text/javascript">var YYConfig = {};</script>
<script type="text/javascript" src="/Public/2017/js/zepto.min.js"></script>
<script type="text/javascript" src="/Public/2017/js/zepto.bxslider.min.js"></script>
<script type="text/javascript" src="/Public/2017/js/wow.min.js"></script>
<script type="text/javascript" src="/Public/2017/js/masonry_4.min.js"></script>
<script type="text/javascript">$(function () {
    new WOW({scrollContainer: ".scrollView"}).init();
})</script>
<script type="text/javascript" src="/Public/2017/js/org.min.js" data-main="ListMain"></script>
</body>
</html>