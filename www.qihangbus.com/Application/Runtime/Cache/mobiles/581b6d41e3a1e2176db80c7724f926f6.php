<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="renderer" content="webkit">
    <title>数据统计</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
    <script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/js/mobiles/jquery.datePicker-min.js"></script>
    <script src="/Public/js/morris-chart/morris.js"></script>
    <script src="/Public/js/morris-chart/raphael-min.js"></script>
    <link type="text/css" href="/Public/css/mobiles/rili.css" rel="stylesheet" />
	<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body>
    <div class="wrap2">
        <div class="head-cate">
            <ul class="ul_2">
                <li style="width:33.33%" <?php if(($t) == "1"): ?>class="li_cur"<?php endif; ?>>
                    <a href="<?php echo U('mobile.php/Datainfo/index',array('admin_id'=>$admin_id,'id'=>$id,'t'=>1));?>">
                        <span>图书情况 </span>
                    </a>
                </li>
                <li style="width:33.33%" <?php if(($t) == "2"): ?>class="li_cur"<?php endif; ?>>
                    <a href="<?php echo U('mobile.php/Datainfo/order_list',array('admin_id'=>$admin_id,'id'=>$id,'t'=>2));?>">
                        <span>订购情况</span>
                    </a>
                </li>

                <li style="width:33.33%" <?php if(($t) == "3"): ?>class="li_cur"<?php endif; ?>>
                    <a href="<?php echo U('mobile.php/Datainfo/register',array('id'=>$id,'t'=>3));?>">
                        <span>注册情况</span>
                    </a>
                </li>

            </ul>
        </div>
        <div class="line_hr"></div>


        <div class="line_hr"></div>
        <style type="text/css">
        .btnbtn{border:1px solid #6cc;border-radius:5px;padding:2px 8px;margin:0 5px;color:#6cc;}
        .btnbtn1{border:1px solid #ccc;color:#ccc;}
        </style>
        <div style="height:25px;line-height:25px;margin-top:20px;text-align:center;">
                <a class='btnbtn' >注册率:&nbsp;<?php echo ($percent); ?>%</a>
            <div style="clear:both;"></div>
        </div>
        <div id="div1" style="">
            <div id="myChart" style="height:200px;width:100%;margin:0 auto;"></div>
        </div>
        <div class="line_hr"></div>
        <div class="head-cate">
            <ul class="ul_0">
                <li style="width:25%"><span><i class="i_ic">班级</i> </span></li>
                <li style="width:25%"><span><i class="i_ic">老师</i></span></li>
                <li style="width:15%"><span><i class="i_ic">人数</i></span></li>
                <li style="width:15%"><span><i class="i_ic">注册</i></span></li>
                <li style="width:20%"><span><i class="i_ic">注册率</i></span></li>
            </ul>
        </div>
        <div class="line_hr"></div>

        <div class="head-cate" style="margin:0;height:30px;">
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "暂无数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><ul class="ul_0"  style="background-color: #fff;border-bottom: 1px solid #eee;">
                <li style="height:30px;width:25%;"><span style="border:none;"><?php echo ($vo["grade_name"]); echo ($vo["class_name"]); ?> </span></li>
                <li style="height:30px;width:25%;"><span style="border:none;"><?php echo ($vo["teacher_name"]); ?></span></li>
                <li style="height:30px;width:15%;"><span style="border:none;"><?php echo ($vo["total_num"]); ?></span></li>
                <li style="height:30px;width:15%;"><span style="border:none;"><?php echo ($vo["num1"]); ?></span></li>
                <li style="height:30px;width:20%;"><span style="border:none;">
                <?php if($vo["percent"] < 50): ?><font color="red"><?php echo ($vo["percent"]); ?>%</font>
                <?php else: echo ($vo["percent"]); ?>%<?php endif; ?>
                </span></li>
            </ul><?php endforeach; endif; else: echo "暂无数据" ;endif; ?>
        </div>  
<!-- <a href="<?php echo U('mobile.php/SIndex/Index',array('id'=>$id));?>"> -->



<?php if(empty($admin_id)): ?><a href="<?php echo U('mobile.php/SIndex/Index',array('id'=>$id));?>">
<?php else: ?>
<a href="<?php echo U('mobile.php/MIndex/Index',array('id'=>$admin_id));?>"><?php endif; ?>

<!-- <a href="<?php echo U('mobile.php/MIndex/Index',array('id'=>$admin_id));?>"> -->
<span class="f_index" style="bottom:15px;"><span class="iconfont icon-shouyeshouye"></span></span>
</a> 
</div>
<script type="text/javascript">
Morris.Bar({
  element: 'myChart',
  data: <?php echo ($list_json); ?>,
  xkey: 'y',
  ykeys: ['a'],
  labels: ['Series A', 'Series B']
});
</script>    
</body>
</html>