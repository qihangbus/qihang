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
</head>
<body>
<div class="wrap">
    <div class="pro-show">
        <div class="pro-slider">
            <!-- Swiper -->
            <div class="swiper-pro swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="<?php echo ($info["book_img"]); ?>">
                    </div>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
            </div>
            <!-- Swiper -->
        </div>
        <div class="line_hr"></div>
        <ul class="pro-ul-show">
            <li class="li_name"><strong class="s_name" style="font-size: 16px;"><?php echo ($info["book_name"]); if(!empty($info["sub_name"])): ?>【<?php echo ($info["sub_name"]); ?>】<?php endif; ?></strong></li>
            <li class="li_price"><strong style="color:#999"><?php echo ($info["book_author"]); ?> </strong></li>
        </ul>
        <div class="line_hr"></div>
        <dl class="dl_protoggle">
            <dt class="am-accordion-title">作者简介</dt>
            <dd class="am-accordion-bd">
                <div class="xx_pro"><p style="text-indent: 2em;"><?php echo ($info["author_desc"]); ?></p></div>
            </dd>
        </dl>
        <div class="line_hr"></div>
        <dl class="dl_protoggle">
            <dt class="am-accordion-title">绘本简介</dt>
            <dd class="am-accordion-bd">
                <div class="xx_pro"><p  style="text-indent:2em;"><?php echo ($info["book_desc"]); ?></p></div>
            </dd>
        </dl>
    </div>
    <a href="<?php echo ($url); ?>">
        <span class="f_index" style="bottom:55px;"><span class="iconfont icon-shouyeshouye"></span></span>
    </a>
</div>
<div class="footer2">

    <ul class="footer_pbtn">
        <li class="fbtn_l"><span class="s_plus"><a class="e_jian">-</a><span class="s_num" id="s_num3">1</span><a
                class="e_jia">+</a></span></li>

        <input type="hidden" id="book_id" value="<?php echo ($info["book_id"]); ?>"/>
        <input type="hidden" id="user_id" value="<?php echo ($user_id); ?>"/>
        <input type="hidden" id="user_flag" value="<?php echo ($user_flag); ?>"/>
        <li class="fbtn_r2"><a href="javascript:;" id="addtocart" class="btn_buy">加入购物车</a></li>
    </ul>
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
            var user_flag = 4;
            $.post("<?php echo U('mobile.php/Book/add_to_cart');?>", {
                // book_id: book_id,
                // book_num: book_num,
                // user_id: user_id,
                // user_flag: user_flag
                product_id: book_id,
                product_num: book_num,
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
</body>
</html>