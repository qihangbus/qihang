<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="renderer" content="webkit">
    <title>确认借阅图书</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
	<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body>

    <div class="wrap2">
        <div class="w_tit"><span>借阅信息</span></div>
        <ul class="ul_jiey clearfix">
            <li>
                <dl class="jieyu_shu">
                    <dt>
                        <img id="book_thumb" src="<?php echo ((isset($circulations["book_thumb"]) && ($circulations["book_thumb"] !== ""))?($circulations["book_thumb"]):'Public/images/mobiles/pp.png'); ?>" alt="" />
                    </dt>
                    <dd id="book_name"><?php echo ($circulations["book_name"]); ?></dd>
                </dl>
            </li>
            <li class="li_c"></li>
            <li>
                <dl class="jieyu_shu">
                    <dt class="dt_ren">
                        <img src="<?php echo ((isset($circulations["student_avatar"]) && ($circulations["student_avatar"] !== ""))?($circulations["student_avatar"]):'Public/images/mobiles/tx.png'); ?>" alt="" />
                    </dt>
                    <dd>
                        <?php echo ($circulations["student_name"]); ?>
                    </dd>
                </dl>
            </li>
        </ul>
        <div class="line_hr"></div>
        <ul class="jieyu_form"> 
            <li class="border_b"><a href="<?php echo U('mobile.php/TBorrow/setborrow',array('circulation_id'=>$circulations['circulation_id']));?>" class="btn btn2 ">确认借阅</a>
            </li> 
			
            <li><a href="<?php echo U('mobile.php/TBorrow/books',array('circulation_id'=>$circulation_id,'teacher_id'=>$user_id,'student_id'=>$student_id,'circul_status'=>$circul_status));?>" cid="<?php echo ($circulations['circulation_id']); ?>" class="btn btn3 ">更换图书</a></li>
			
        </ul>

    </div>
      <a href="<?php echo U('mobile.php/TIndex/index',array('teacher_id'=>$user_id));?>">
<span class="f_index" style="bottom:15px;"><span class="iconfont icon-shouyeshouye"></span></span>
</a> 
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script type="text/javascript">
$(function(){
    $("#change_book").click(function(){
        var cid = "<?php echo ($circulation_id); ?>";
        $.post("<?php echo U('mobile.php/TBorrow/randombook');?>",{circulation_id:cid},function(ret){
            if(ret.book_id > 0){
                $("#book_thumb").attr("src",ret.book_thumb);
                $("#book_name").text(ret.book_name);
                layer.open({
                    title:false,
                    content:'更换图书成功',
                    btn:['关闭'],
                    yes:function(index){
                        layer.closeAll();
                    },
                });
            }
        },'json');
    });
})
</script>
</body>
</html>