<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="renderer" content="webkit">
    <title>损坏赔偿</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
    <link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body>
<div class="wrap2">
    <!--
    <div class="head-cate">
        <ul class="ul_2">
            <li class="li_cur">
            <a href="">
                <span>赔偿管理 </span>
            </a>
            </li>
            <li>
            <a href="<?php echo U('compenRecord');?>">
                <span>赔偿记录</span>
            </a>
            </li>
        </ul>
    </div>
    -->
    <div class="line_hr"></div>
    <ul class="cart_list ck_list" id="cart_list">
        <?php if(empty($borrows)): ?><div class="data-empty">
                <p><img src="/Public/images/mobiles/empty.png"><p>
                <p>暂无数据</p>
            </div><?php endif; ?>
        <?php if(is_array($borrows)): $i = 0; $__LIST__ = $borrows;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="" sid="<?php echo ($vo["student_id"]); ?>" style="margin-bottom:0px;padding-top:10px;border-bottom: 1px solid #F2F2F2;background-color: #fff;position: relative;">
                <dl class="cart_pro">
                    <dt class="dt_tx"><img src="<?php echo ((isset($vo['student_avatar']) && ($vo['student_avatar'] !== ""))?($vo['student_avatar']):'Public/images/mobiles/default.png'); ?>" alt="" /></dt>
                    <dd class="dd_pname2">
                        <p><?php if($vo["flag"] < '1'): ?><a href="javascript:void(0);" hf="<?php echo ($vo['hardcover_flag']); ?>" bid="<?php echo ($vo['book_id']); ?>" sid="<?php echo ($vo['student_id']); ?>" class="ibtn_1 compensate" style="background-color:#f00;float:right;">损坏赔偿</a>

                            <?php else: ?>
                            <a href="javascript:void(0);" style="float:right;" class="ibtn_1 ibtn_dis"><?php echo ($vo["compen"]); ?></a><?php endif; ?>
                            <b><?php echo ($vo['student_name']); ?></b>

                        </p>
                        <p><strong>NO.<?php echo ($vo["book_no"]); ?></strong> - <?php echo ($vo['book_name']); ?></p>
                    </dd>
                </dl>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/layer.js"></script>
<a href="<?php echo U('mobile.php/TIndex/index',array('teacher_id'=>$teacher_id));?>">
    <span class="f_index" style="bottom:15px;"><span class="iconfont icon-shouyeshouye"></span></span>
</a>
<script type="text/javascript">
    $(function(){


        $(".compensate").click(function(){
            var bid = $(this).attr("bid");
            var sid = $(this).attr("sid");
            var hf = 99;

            layer.open({
                title: false,
                content: '确定给家长发送损坏赔偿提醒？',
                btn:['确定','取消'],
                yes:function(index,layero){

                    $.post("<?php echo U('mobile.php/TBorrow/push_msg');?>",{bid:bid,sid:sid,hf:hf},function(result){
                        if(result == 99){
                            layer.open({
                                title:false,
                                content:'提醒成功',
                                btn:['关闭'],
                                yes:function(index){
                                    layer.closeAll();
                                    location.reload();
                                },
                            });
                        }
                    });

                },
                btn2:function(index,layero){
                    layer.closeAll();
                }
            });
        });
    })
</script>
</body>
</html>