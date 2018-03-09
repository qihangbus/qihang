<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
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

 
	<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
    <script class="js_prejs" type="text/javascript" src="__STATIC__/lib/jquery.min.js"></script>
</head>
<body>

    <div class="wrap2">
        <ul class="cart_list ">
            <?php if(empty($circulations)): ?><div class="data-empty">
					<p><img src="/Public/images/mobiles/empty.png"><p>
					<p>暂无数据</p>
					</div><?php endif; ?> 
            <?php if(is_array($circulations)): $i = 0; $__LIST__ = $circulations;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart" style="padding: 5px 0 0 0">
                    <dl class="cart_pro">
                        <dt><img src="<?php echo ($vo['book_thumb']); ?>" alt="" /></dt>
                        <dd class="dd_pname2">
                            <p><b><?php echo ($vo['book_name']); ?></b></p>
                            <p><b>借阅时间 </b><?php echo ($vo['borrow_time']); ?></p>
                            <p><b>归还时间 </b><?php echo ($vo['return_time']); ?></p>
                        </dd>
                    </dl>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>

        </ul>

  <a href="<?php echo U('mobile.php/TIndex/index',array('teacher_id'=>$teacher_id));?>">
<span class="f_index" style="bottom:15px;"><span class="iconfont icon-shouyeshouye"></span></span>
</a> 
    </div>
</body>
</html>