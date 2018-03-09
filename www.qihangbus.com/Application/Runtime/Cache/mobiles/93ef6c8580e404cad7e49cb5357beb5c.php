<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="renderer" content="webkit">
    <title>轮换日期</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
    <script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/js/mobiles/jquery.datePicker-min.js"></script>
    <link type="text/css" href="/Public/css/mobiles/rili.css" rel="stylesheet" />
    <link type="text/css" href="/Public/css/mobiles/iconfont.css" rel="stylesheet" />
	<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body>
    <div class="wrap2">
        <style type="text/css">
        .cart_pro{height:30px;line-height:30px;margin-bottom:5px;}
        .ddpname{padding-left:10px!important;}
        .dd_pname{padding-left:40px!important;}
        </style>

        <ul class="cart_list order_list">
            <li class="li_cart" style="padding-bottom:0px;background:#ffd887;color:#fff;">
                <dl class="cart_pro">
                <dd class="ddpname">图书轮换提醒：</dd>
                </dl>
            </li>
        </ul>
        <div class="head-cate clearfix" style="margin:0;height:auto;">
            <ul class="ul_3" style="border-bottom:1px solid #F2F2F2;padding:0;">
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li style="width:100%;text-align:left;">
                        <span style="margin-left:20px;">
                            <?php echo ($vo["grade_name"]); ?> 调整日期为：<label id="grade_time<?php echo ($vo["grade_id"]); ?>"><?php echo (date('Y-m-d',$vo["change_time"])); ?></label>
                        </span></li>
                    <?php if(is_array($vo["lt"])): $i = 0; $__LIST__ = $vo["lt"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div style="margin-left:20px;height:40px;line-height:40px;float:left;clear:both;"><?php echo ($v["class_name"]); ?> <span class="iconfont icon-youjiantou"></span> <?php echo ($v["next_name"]); ?></div><?php endforeach; endif; else: echo "" ;endif; ?>
                    <div class="line_hr"></div><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>    
<a href="<?php echo U('mobile.php/TIndex/index',array('teacher_id'=>$id));?>">
<span class="f_index" style="bottom:15px;"><span class="iconfont icon-shouyeshouye"></span></span>
</a>		
</div>
<script type="text/javascript">
$(function(){
    $(".ibtn_1").click(function(){
        var gid = $(this).attr('gid'); 
        $("#grade_time"+gid).datePicker({
            inline:true,
            selectMultiple:false,
            renderCallback:function(y, m, B, p){ 
                y.bind("click",function(event){
                    var da=new Date(y.find(".td_td").attr("da"));
                    var str =da.getFullYear()+"-"+(da.getMonth()+1)+"-"+da.getDate(); 

                    $.ajax({
                        type: "get",
                        url: "/mobile.php?m=mobile.php&c=MIndex&a=ajax_time&str_date="+encodeURI(str)+"&grade_id="+gid,
                        async: false,
                        dataType: "json",
                        success: function(data) {
                            $("#grade_time"+gid).text(str);
                            //$(".p_title").text(data.task_title);
                            //$(".p_desc").text(data.task_desc);
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown)
                         {
                            alert(textStatus);
                        }
                    });
                });
            }
        });
    });
});
</script>    
</body>
</html>