<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="renderer" content="webkit">
    <title>损坏未赔偿情况</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
    <script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
    <script src="/Public/js/morris-chart/morris.js"></script>
    <script src="/Public/js/morris-chart/raphael-min.js"></script>
    <link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body>
    <div class="wrap2">
        <div class="address_t" style="text-align:center;"><span>损坏未赔偿情况</span></div>
        <div class="line_hr"></div>
        <div class="head-cate">
            <ul class="ul_3">
                <li><span><i class="i_ic">班级</i> </span></li>
                <li><span><i class="i_ic">任课老师</i></span></li>
                <li><span><i class="i_ic">损坏数量</i></span></li>
            </ul>
        </div>
        <div class="line_hr"></div>
        <style type="text/css">
        .cart_pro{height:30px;line-height:30px;margin-bottom:5px;}
        .dd_pname{padding-left:40px!important;}
        </style>
        <div class="head-cate" style="">
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><ul class="ul_3 head-cate"  style="border-bottom: 1px solid #f7f5f5">
                <li style="height:30px;"><span style="border:none;"><?php echo ($vo["class_name"]); ?> </span></li>
                <li style="height:30px;"><span style="border:none;"><?php echo ($vo["teacher_name"]); ?></span></li>
                <li style="height:30px;"><span style="border:none;"><?php echo ($vo["total_num"]); ?>本</span></li>
            </ul><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>        
        <a href="<?php echo ($index_url); ?>">
            <span class="f_index" style="bottom:15px;"><span class="iconfont icon-shouyeshouye"></span></span>
        </a>
</div>
<script type="text/javascript">
Morris.Donut({
    element: 'myChart',
    resize:true,
    data: [
        {value: <?php echo ($borrow_total); ?>, label: '已借阅', formatted: '<?php echo ($borrow_total_per); ?>' },
        {value: <?php echo ($noborrow_total); ?>, label: '未借阅', formatted: '<?php echo ($noborrow_total_per); ?>' },
        {value: <?php echo ($compen_total); ?>, label: '损坏未赔偿', formatted: '<?php echo ($compen_total_per); ?>' }
    ],
    backgroundColor: false,
    labelColor: '#000',
    colors: [
        '#6a8bc0','#5ab6df','#fe8676'
    ],
    formatter: function (x, data) { return data.formatted; }
});
</script>    
</body>
</html>