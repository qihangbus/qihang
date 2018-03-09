<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta name="renderer" content="webkit">
    <title>数据统计</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
    <link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
    <script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
    <!--[if IE]>
    <script language="javascript" type="text/javascript" src="/Public/js/flot-chart/excanvas.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="/Public/js/flot-chart/jquery.flot.min.js"></script>
    <script type="text/javascript" src="/Public/js/flot-chart/jquery.flot.pie.min.js"></script>

</head>
<body>
<div class="wrap2">
    <div class="head-cate">
        <ul class="ul_2">
            <li style="width:33.33%"
            <?php if(($t) == "1"): ?>class="li_cur"<?php endif; ?>
            >
            <a href="<?php echo U('mobile.php/Datainfo/index',array('admin_id'=>$admin_id,'id'=>$id,'t'=>1));?>">
                <span>图书情况 </span>
            </a>
            </li>
            <li style="width:33.33%"
            <?php if(($t) == "2"): ?>class="li_cur"<?php endif; ?>
            >
            <a href="<?php echo U('mobile.php/Datainfo/order_list',array('admin_id'=>$admin_id,'id'=>$id,'t'=>2));?>">
                <span>订购情况</span>
            </a>
            </li>
            
            <li style="width:33.33%" <?php if(($t) == "3"): ?>class="li_cur"<?php endif; ?>>
                    <a href="<?php echo U('mobile.php/Datainfo/register',array('admin_id'=>$admin_id,'id'=>$id,'t'=>3));?>">
                        <span>注册情况 </span>
                    </a>
            </li>

        </ul>
    </div>
    <div class="line_hr"></div>
    <div id="div1" style="">
        <div id="pieChart" style="width:400px;height:300px"></div>
    </div>

    <style type="text/css">
        .cart_pro {
            height: 35px;
            border-bottom: 1px solid #eee;
        }

        .dd_pname {
            padding-left: 10px !important;
        }

        .legend div, .legend table {
            right: 25px !important;
        }
    </style>

    <ul class="cart_list order_list">

        <li class="li_cart" style="padding-top: 6px;">
            <dl class="cart_pro">
                <dd class="dd_pname">绘本总量：<?php echo ($total); ?>本</dd>
            </dl>
            <dl class="cart_pro">
                <dd class="dd_pname">已借阅：<?php echo ($borrow_total); ?>本
                    <!--<a href="<?php echo U('mobile.php/Datainfo/borrow',array('id'=>$id));?>" style="margin-right:10px;float:right;color:#dea6e5;">查看详情</a>--></dd>
            </dl>
            <dl class="cart_pro">
                <dd class="dd_pname">未借阅：<?php echo ($noborrow_total); ?>本
                    <!--<a href="<?php echo U('mobile.php/Datainfo/noborrow',array('id'=>$id));?>" style="margin-right:10px;float:right;color:#f4917d;">查看详情</a>--></dd>
            </dl>
            <dl class="cart_pro">
                <dd class="dd_pname">损坏已赔偿：<?php echo ($shypc); ?>本</dd>
            </dl>
            <dl class="cart_pro">
                <dd class="dd_pname">损坏未赔偿：<?php echo ($compen_total); ?>本 <a
                        href="<?php echo U('mobile.php/Datainfo/compensate',array('id'=>$id));?>"
                        style="margin-right:10px;float:right;color:#facd7f;">查看详情</a></dd>
            </dl>
        </li>

    </ul>
    <a href="<?php echo ($url); ?>">
        <span class="f_index" style="bottom:15px;"><span class="iconfont icon-shouyeshouye"></span></span>
    </a>
</div>
<script type="text/javascript">
    var optionPie = {
    ﻿ ﻿
    series: {
    ﻿ ﻿ ﻿ pie: {
        ﻿ ﻿ ﻿ ﻿ show: true,
﻿ ﻿ ﻿ ﻿ radius: 6 / 10,﻿
        ﻿ ﻿ ﻿ ﻿ offset: {
            ﻿ ﻿ ﻿ ﻿ ﻿ top: 10,
﻿ ﻿ ﻿ ﻿ ﻿ left: 'auto'
﻿ ﻿ ﻿ ﻿
            }
        ,
        ﻿ ﻿ ﻿ ﻿ stroke: {
            ﻿ ﻿ ﻿ ﻿ ﻿ color: '#FFF',
﻿ ﻿ ﻿ ﻿ ﻿ width: 1
﻿ ﻿ ﻿ ﻿
            }
        ,
        ﻿ ﻿ ﻿ ﻿ label: {
            ﻿ ﻿ ﻿ ﻿ ﻿ show: true,
﻿ ﻿ ﻿ ﻿ ﻿ radius: 2 / 5,
﻿ ﻿ ﻿ ﻿ ﻿ formatter: function (label, series) {
                    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                }
            ,
            ﻿ ﻿ ﻿ ﻿ ﻿ threshold: 0﻿
            ﻿ ﻿ ﻿ ﻿
            }
﻿ ﻿ ﻿
        }
﻿ ﻿
    }
    ,
    colors: ["#dea6e5", "#f4917d", "#facd7f"],
    ﻿
    }
    ;
    var pieData = [
        {label: "已借阅", data: [[1, <?php echo ($borrow_total_per); ?>]]},
        {label: "未借阅", data: [[1, <?php echo ($noborrow_total_per); ?>]]},
        {label: "损坏未赔偿", data: [[1, <?php echo ($compen_total_per); ?>]]}
    ];

    $(function () {
        $.plot($("#pieChart"), pieData, optionPie);
    });
</script>
</body>
</html>