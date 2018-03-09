<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="renderer" content="webkit">
    <title>图书馆</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
    <link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
    <script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>

</head>
<body>
<div class="wrap2">
    <div class="head-cate" <?php if($f > '0'): ?>style="height:84px;"<?php endif; ?>>
    <ul class="ul_3">
        <input type="hidden" id="types" value="<?php echo ($type); ?>"/>
        <?php if(is_array($grade_list)): $i = 0; $__LIST__ = $grade_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if($type == $vo['grade_id']): ?>class="li_cur"<?php endif; ?> type="<?php echo ($vo["grade_id"]); ?>">
            <a href="<?php echo U('mobile.php/Library/detail',array('id'=>$id,'type'=>$vo['grade_id']));?>">
                <span><?php echo ($vo["grade_name"]); ?></span>
            </a>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
<div class="line_hr"></div>
<div id="div1">
    <ul class="cart_list list_banj">
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "暂无数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart">
                <dl class="cart_pro">
                    <dt class="dt_tx"><img src="<?php echo ($vo['book_thumb']); ?>" alt="" /></dt>
                    <dd class="dd_pname2 p_padr">
                        <p class=" p_name"><?php echo ($vo['book_name']); ?></p>
                        <p><b>所在班级 </b><?php echo ($vo["grade_name"]); echo ($vo["class_name"]); ?></p>
                    </dd>
                    <dd class="dd_r">
                        <a href="<?php echo U('mobile.php/TClass/readhistory',array('student_id'=>$vo['student_id']));?>" class="ibtn_1" style="display:none;">借阅历史</a>
                    </dd>
                </dl>
            </li><?php endforeach; endif; else: echo "暂无数据" ;endif; ?>
        <div style="vertical-align:central"></div>
    </ul>
</div>
<a href="<?php echo U('mobile.php/SIndex/Index',array('id'=>$id));?>">
    <span class="f_index" style="bottom:15px;"><span class="iconfont icon-shouyeshouye"></span></span>
</a>
</div>
</body>
</html>