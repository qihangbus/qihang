<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="renderer" content="webkit">
    <title>评价</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
	<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
    <script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script> 
</head>
<body>
    <div class="wrap2">
        <div class="head-cate">
            <ul class="ul_2">
                <li  style="width:33%" 
                    <?php if(($circul_status) == "2"): ?>class="li_cur"<?php endif; ?>>
                    <a href="<?php echo U('mobile.php/TBorrow/index',array('teacher_id'=>$teacher_id,'circul_status'=>2));?>">
                        <span>借阅 </span>
                    </a>
                </li>
                <li  style="width:34%" 
                    <?php if(($circul_status) == "1"): ?>class="li_cur"<?php endif; ?>>
                    <a href="<?php echo U('mobile.php/TBorrow/index',array('teacher_id'=>$teacher_id,'circul_status'=>1));?>">
                        <span>归还</span>
                    </a>
                </li>
                <li style="width:33%" <?php if(($circul_status) == "3"): ?>class="li_cur"<?php endif; ?>>
                    <a href="<?php echo U('mobile.php/TBorrow/evaluate',array('teacher_id'=>$teacher_id,'circul_status'=>3));?>">
                        <span>评价</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="line_hr"></div>
        <div id="div1">
            <ul class="cart_list order_list">
                <form name="myform" id="myform" action="<?php echo U('mobile.php/TBorrow/comment_handle');?>" method="post">
                <input type="hidden" name="eval_type" value="<?php echo ($eval_type); ?>"/>
                <input type="hidden" name="student_id" value="<?php echo ($student_id); ?>"/>
                <input type="hidden" name="teacher_id" value="<?php echo ($teacher_id); ?>"/>
				<input type="hidden" name="flag" value="<?php echo ($flag); ?>"/>
                <input type="hidden" name="circul_status" value="<?php echo ($circul_status); ?>"/>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li style="padding:0px 10px;">
                    <dl style="border-bottom: 1px solid #F2F2F2;background-color: #fff;position: relative;padding: 10px 0;">
                        <dd class="dd_pname2 p_padr">
                            <p class=" p_name"><input type="radio" name="content" value="<?php echo ($vo); ?>"><?php echo ($vo); ?></p>
                        </dd>
                    </dl>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                <br>
                <div class="btn_box "><a href="javascript:void(0);"  class="btn btn2 btn_comment">提交</a></div>
                </form>
            </ul>
            
        </div>
    </div>
      <a href="<?php echo U('mobile.php/TIndex/index',array('teacher_id'=>$teacher_id));?>">
<span class="f_index" style="bottom:15px;"><span class="iconfont icon-shouyeshouye"></span></span>
</a> 

<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $(".btn_comment").click(function(){
        $("#myform").submit();
    });

    
});
</script>
</body>
</html>