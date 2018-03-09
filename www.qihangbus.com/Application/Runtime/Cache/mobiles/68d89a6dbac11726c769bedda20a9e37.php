<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="renderer" content="webkit">
    <title>借阅历史</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
    <script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
</head>
<body>
    <div class="wrap2">
        <ul class="cart_list" style="display:none;">
            <li class="li_cart">
                <dl class="cart_pro">
                    <dt class=""><img src="<?php echo ($book["book_thumb"]); ?>" alt="" /></dt>
                    <dd class="dd_pname2 p_padr">
                        <p><b><?php echo ($book["book_name"]); ?></b></p>
                        <p><b>书架位置 </b>A-1-999</p>
                        <p><b>入库时间 </b> <span class="color1"><?php echo ($book["add_date"]); ?></span></p>
                    </dd>
                    <dd class="dd_r dd_r2">
                        <?php if(($book["book_status"]) == "1"): ?>在库
                            <?php else: ?>
                            出库<?php endif; ?>
                    </dd>
                </dl>
            </li>
        </ul>
        <div class="line_hr"></div>
        <ul class="cart_list ">
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "暂无借阅数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart">
                    <dl class="cart_pro">
                        <dt class="dt_tx"><img src="<?php echo ($vo['book_thumb']); ?>" alt="" /></dt>
                        <dd class="dd_pname2">
                            <p><b><?php echo ($vo['book_name']); ?></b></p>
                            <p><b>借阅时间 </b><?php echo ($vo['borrow_time']); ?></p>
                            <p><b>归还时间 </b><?php echo ($vo['return_time']); ?></p>
                        </dd>
                    </dl>
                </li><?php endforeach; endif; else: echo "暂无借阅数据" ;endif; ?>
        </ul>
    </div>
     <a href="<?php echo U('mobile.php/SIndex/Index',array('id'=>$id));?>">
<span class="f_index" style="bottom:15px;">首页</span>
</a> 
</body>
</html>