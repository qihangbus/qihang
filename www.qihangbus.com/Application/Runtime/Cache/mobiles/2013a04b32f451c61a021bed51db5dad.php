<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="renderer" content="webkit">
    <title>绘本列表</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
	<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
    <script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>

</head>
<body>
    <div class="wrap2">
        <div id="div1">
            <ul class="cart_list list_banj" >
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart" style="padding:10px 0;">
                        <a href="<?php echo U('mobile.php/MIndex/info',array('id'=>$vo[book_id],'user_id'=>$id));?>">
						<dl class="cart_pro">
                            <dt class="dt_tx"><img src="<?php echo ($vo['book_thumb']); ?>" alt="" /></dt>
                            <dd class="dd_pname2 p_padr">
                                <p class="p_name" style="padding-top:9px;"><strong>NO.<?php echo ($vo["book_no"]); ?></strong> - <?php echo ($vo['book_name']); ?></p>
                                <?php if(!empty($vo["sub_name"])): ?><p>【<?php echo ($vo['sub_name']); ?>】</p><?php endif; ?>
                            </dd>
                            <dd class="dd_r" style="display:none;">
                                <div class="d_zhungt"><span class="color1">在读</span></div>
                                <a href="<?php echo U('mobile.php/TClass/readhistory',array('student_id'=>$vo['student_id']));?>" class="ibtn_1">借阅历史</a>
                            </dd>
                        </dl>
						</a>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                <div style="vertical-align:central"></div>
            </ul>
        </div>
          <a href="<?php echo U('mobile.php/MIndex/index',array('id'=>$id));?>">
<span class="f_index" style="bottom:15px;">
<span class="iconfont icon-shouyeshouye"></span></span>
</a> 
    </div>
</body>
</html>