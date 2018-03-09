<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="renderer" content="webkit">
    <title>图书管理</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
    <script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
	<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body>
    <div class="wrap2">
<!--         <div class="head-cate">
            <ul class="ul_2">
                <li style="width:33.33%" <?php if(($t) == "1"): ?>class="li_cur"<?php endif; ?>>
                    <a href="<?php echo U('mobile.php/MIndex/index',array('id'=>$id,'t'=>1));?>">
                        <span>轮换管理 </span>
                    </a>
                </li>
                <li style="width:33.33%" <?php if(($t) == "2"): ?>class="li_cur"<?php endif; ?>>
                    <a href="<?php echo U('mobile.php/MIndex/books',array('id'=>$id,'t'=>2));?>">
                        <span>图书管理</span>
                    </a>
                </li>
                <li style="width:33.33%" <?php if(($t) == "3"): ?>class="li_cur"<?php endif; ?>>
                    <a href="<?php echo U('mobile.php/MIndex/compen',array('id'=>$id,'t'=>3));?>">
                        <span>损毁管理</span>
                    </a>
                </li>
            </ul>
            </div> -->
<!--             <div class="line_hr"></div> -->
            <div class="address_t" style="text-align:center;">
                <span id="month_stat" style="margin-left:20px;padding:2px 8px;color:#6cc">图书总数：<?php echo ($total); ?>本</span>
            </div>
            <div class="line_hr"></div>
            <div class="head-cate">
                <ul class="ul_3">
                    <li style="width:25%"><span><i class="i_ic">班级</i></span></li>
                    <li style="width:25%"><span><i class="i_ic">任课老师</i></span></li>
                    <li style="width:25%"><span><i class="i_ic">数量</i></span></li>
                    <li style="width:25%"><span><i class="i_ic">操作</i></span></li>
                </ul>
            </div>

            <table class="item-table table-center">
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "暂无数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    <td><?php echo ($vo["class_name"]); ?></td>
                    <td><?php echo ($vo["teacher_name"]); ?></td>
                    <td><?php echo ($vo["total_num"]); ?></td>
                    <td><a href="<?php echo U('mobile.php/MIndex/book_list',array('cid'=>$vo['class_id'],'id'=>$id,'t'=>2));?>" class="ibtn_1 compensate">详情</a><br>
                    </td>
                </tr><?php endforeach; endif; else: echo "暂无数据" ;endif; ?>
            </table>    

            <div class="line_hr"></div>
            <style type="text/css">
            .cart_pro{height:30px;line-height:30px;margin-bottom:5px;}
            .dd_pname{padding-left:40px!important;}
            </style>
            <!-- <div class="head-cate" style="margin:0;height:76px;">
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "暂无数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><ul class="ul_3" style="height:68px;border-bottom:1px solid #F2F2F2;padding:8px 0;">
                    <li style="width:25%"><span style="border:none;"><?php echo ($vo["class_name"]); ?></span></li>
                    <li style="width:25%"><span style="border:none;"><?php echo ($vo["teacher_name"]); ?></span></li>
                    <li style="width:25%"><span style="border:none;"><?php echo ($vo["total_num"]); ?></span></li>
                    <li style="width:25%"><span style="border:none;">
                        <a href="<?php echo U('mobile.php/MIndex/book_list',array('cid'=>$vo['class_id'],'id'=>$id,'t'=>2));?>" class="ibtn_1 compensate">详情</a>     
                    </span></li>
                </ul><?php endforeach; endif; else: echo "暂无数据" ;endif; ?>
                
            </div>         -->
          <a href="<?php echo U('mobile.php/MIndex/index',array('id'=>$id));?>">
<span class="f_index" style="bottom:15px;"><span class="iconfont icon-shouyeshouye"></span></span>
</a> 
    </div>
</body>
</html>