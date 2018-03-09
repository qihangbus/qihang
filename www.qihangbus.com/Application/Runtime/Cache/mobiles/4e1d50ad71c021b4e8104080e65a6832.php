<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>订购率排名</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
</head>
<body>

<div class="wrap2"> 
<div class="iban"><img src="/Public/images/mobiles/ban_mx.jpg" alt="" /></div>
<div class="line_hr"></div>



        <div class="head-cate">
            <ul class="ul_0">
                <li style="width:33.3%"><span><i class="i_ic">班级</i> </span></li>
                <li style="width:33.3%"><span><i class="i_ic">订购率</i></span></li>
                <li style="width:33.3%"><span><i class="i_ic">排名</i></span></li>
            </ul>
        </div>
        <div class="line_hr"></div>

<!--         <div class="head-cate" style="margin:0;height:30px;">
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "暂无数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><ul class="ul_0"  style="height:30px;">
                <li style="height:30px;width:25%;"><span style="border:none;"><?php echo ($vo["grade_name"]); echo ($vo["class_name"]); ?> </span></li>
                <li style="height:30px;width:25%;"><span style="border:none;"><?php echo ($vo["teacher_name"]); ?></span></li>
                <li style="height:30px;width:15%;"><span style="border:none;"><?php echo ($vo["total_num"]); ?></span></li>
                <li style="height:30px;width:15%;"><span style="border:none;"><?php echo ($vo["num1"]); ?></span></li>
                <li style="height:30px;width:20%;"><span style="border:none;">
                <?php if($vo["percent"] < 50): ?><font color="red"><?php echo ($vo["percent"]); ?>%</font>
                <?php else: echo ($vo["percent"]); ?>%<?php endif; ?>
                </span></li>
            </ul><?php endforeach; endif; else: echo "暂无数据" ;endif; ?> -->
<?php if(is_array($cla_ids)): $i = 0; $__LIST__ = $cla_ids;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="head-cate" style="margin:0;height:30px;">
            <ul class=""  style="height:30px;">
                <li style="height:30px;width:33.3%;"><span style="border:none;"><?php echo ($vo["class_name"]); ?> </span></li>
                <li style="height:30px;width:33.3%;"><span style="border:none;"><?php echo ($vo["percent"]); ?>% </span></li>
                <li style="height:30px;width:33.3%;"><span style="border:none;"> 第<?php echo ($i); ?>名</span></li>
            </ul>
</div><?php endforeach; endif; else: echo "" ;endif; ?>
 <a href="<?php echo U('mobile.php/TIndex/index',array('teacher_id'=>$user_id));?>">
<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
</a> 
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script type="text/javascript">

</script>
</body>
</html>