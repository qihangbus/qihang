<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="renderer" content="webkit">
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/iconfont.css"/>
    <title>教师中心首页</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
    <style type="text/css">
        .d_signin{margin-top: 25px;}
    </style>
    <link rel="stylesheet" href="/Public/css/mobiles/font-awesome/css/font-awesome.min.css">
</head>
<body>

<div class="wrap2 have-bottom-swiper">
    <div class="head-top"><?php echo ($teacher["school_name"]); ?></div>
    <div class="jiaos_t">
        <dl>
            <dt>
                <a href="<?php echo U('mobile.php/TIndex/avatar',array('user_id'=>$userinfo['student_id'],'user_flag'=>2));?>">
                    <img src="<?php echo ((isset($teacher["teacher_avatar"]) && ($teacher["teacher_avatar"] !== ""))?($teacher["teacher_avatar"]):'/Public/images/mobiles/default.png'); ?>" alt="" />
                </a>
            </dt>
            <dd>
                <div class="qiandao-div">
                    <?php if($signin < '0'): ?><div class="d_signin" id="signin" uid="<?php echo ($userinfo["student_id"]); ?>"><span class="iconfont icon-qiandao"></span></div>
                        <?php else: ?>
                        <div class="d_signin d_signin_d"><span class="iconfont icon-qiandao"></span></div><?php endif; ?>
                    <input type="hidden" id="signnum" value="<?php echo ($signnum); ?>">
                </div>
                <div class="d_name">
                    <?php echo ($teacher["teacher_name"]); ?>-<?php echo ($teacher["class_name"]); ?>
                </div>
            </dd>
        </dl>
    </div>
    <div class="head-cate">
        <ul class="ul_3">
            <!--
            <li>
                <a href="<?php echo U('mobile.php/TIndex/get_levle_info',array('user_id'=>$userinfo['teacher_id'],'user_points'=>$userinfo['student_points'],'teacher_rank'=>$userinfo['teacher_rank'],'user_flag'=>2));?>">
                    <span class="iconfont icon-shiliangzhinengduixiang-01"></span>
                    <span class="head-cate-txt">等级</span>
                </a>
            </li>
            -->
            <li class="">
                <a href="<?php echo U('mobile.php/TIndex/get_class_rank',array('user_id'=>$userinfo['teacher_id'],'user_points'=>$userinfo['student_points'],'school_id'=>$userinfo['school_id']));?>">
                    <span class="iconfont icon-paiming"></span>
                    <span class="head-cate-txt">订阅排名</span></a></li>
            </a>
            </li>
            <li>
                <a href="<?php echo U('mobile.php//Book/index',array('user_id'=>$userinfo['teacher_id'],'user_points'=>$userinfo['student_points'],'user_flag'=>2));?>">
                    <span class="iconfont icon-duihuan"></span>
                    <span class="head-cate-txt">兑换中心</span>
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" id="dist_book" data-id = "<?php echo ($userinfo["teacher_id"]); ?>">
                    <span class="fa fa-book" style="color: #aee078;font-size: 28px;margin-top:5px;"></span>
                    <span class="head-cate-txt">分配图书</span>
                </a>
            </li>
        </ul>
    </div>
    <ul class="m_menu clearfix">

        <li class="li_item">
            <a href="<?php echo U('mobile.php/TBookshelf/index',array('teacher_id'=>$teacher_id));?>">
                <span class="iconfont icon-shujia"></span>
                <span class="m_menu-txt">班级书架</span>
            </a>
        </li>


        <li class="li_item">
            <a href="<?php echo U('mobile.php/TBorrow/index',array('teacher_id'=>$teacher_id,'circul_status'=>2));?>">
                <span class="iconfont icon-jiebei"></span>
                <span class="m_menu-txt">借阅</span>
            </a>
        </li>
        <li class="li_item">
            <a href="<?php echo U('mobile.php/TClass/payStu',['teacher_id'=>$teacher_id]);?>">
                <span class="iconfont icon-banji"></span>
                <span class="m_menu-txt">班级管理</span>
            </a>
        </li>
        <li class="li_item <?php if($teacher["message_num"] > '0'): ?>li_item_p<?php endif; ?>">
        <a href="<?php echo U('mobile.php/TIndex/get_message_list',array('user_id'=>$userinfo['student_id'],'user_flag'=>2));?>">
            <span class="iconfont icon-xiaoxi"></span>
            <span class="m_menu-txt">消息</span>
        </a>
        </li>
        <li class="li_item ">
            <!--<a href="<?php echo U('mobile.php/Cart/index',array('user_id'=>$userinfo['student_id'],'user_flag'=>2));?>">-->
            <a href="<?php echo U('mobile.php/TBorrow/indemnify',array('teacher_id'=>$teacher_id));?>">
                <!--<span class="iconfont icon-mangouwuche"></span>-->
                <span class="fa fa-recycle" style="font-size:39px;margin-top: 3px;color:#ff9d4d"></span>
                <span class="m_menu-txt">损坏赔偿</span>
            </a></li>



        <li class="li_item ">
            <a href="/default.php/Live">
                <span class="fa fa-graduation-cap" style="font-size:39px;margin-top: 3px;color:#ff9d4d"></span>
                <span class="m_menu-txt">专家讲堂</span>
            </a>
        </li>

        <li class="li_item ">
            <a href="/default.php/Live/Index/trainTeach">
                <span class="fa fa-video-camera" style="font-size:39px;margin-top: 3px;color:#FF9A9A"></span>
                <span class="m_menu-txt">在线培训</span>
            </a>
        </li>

        <li class="li_item ">
            <a href="<?php echo U('mobile.php/TIndex/circul',array('user_id'=>$userinfo['student_id'],'user_flag'=>2));?>">
                <span class="iconfont icon-huan"></span>
                <span class="m_menu-txt">轮换日期</span>
            </a>
        </li>

        <li class="li_item ">
            <a href="/mobile.php/Rotate/index">
                <span class="fa fa-sign-in" style="font-size:39px;margin-top: 3px;color:#82CA87"></span>
                <span class="m_menu-txt">轮换接收</span>
            </a>
        </li>
    <!--
        <li class="li_item">
            <a href="<?php echo U('mobile.php/Order/index',array('user_id'=>$userinfo['student_id'],'user_flag'=>2));?>">
                <span class="iconfont icon-dingdan"></span>
                <span class="m_menu-txt">订单</span>
            </a>
        </li>
    -->
        <li class="li_item ">
            <a href="/sns.php?m=sns.php&c=forum&a=bbs">
                <span class="fa fa-group" style="font-size:39px;margin-top: 3px;color:#C492DC"></span>
                <span class="m_menu-txt">论坛</span>
            </a>
        </li>

        <li class="li_item ">
            <a href="<?php echo U('mobile.php/TIndex/setting',array('user_id'=>$userinfo['student_id'],'user_flag'=>2));?>">
                <span class="iconfont icon-shezhi"></span>
                <span class="m_menu-txt">设置</span>
            </a>
        </li>
    </ul>

</div>
<!--
<div class="footer2">
    <div class="swiper-home swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="/Public/images/mobiles/ban_mx.jpg">
            </div>
        </div>
        <div class="swiperp_box"><div class="swiper-pagination"></div></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>
-->
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/lib/swiper.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script src="/Public/layer/layer.js"></script>
<script type="text/javascript">
    $(function () {
        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
            loop: true,
            paginationClickable: true
        });

        var bHeight = $('.swiper-home').height();
        $('.have-bottom-swiper').css("bottom",bHeight);

        $("#signin").click(function(){
            var uid = $(this).attr('uid');
            var num = $("#signnum").val();
            var uflag = 2;
            $.post("<?php echo U('mobile.php/TIndex/signin');?>",{user_id:uid,user_flag:uflag,signnum:num},function(ret){
                if(ret.code > 0){

                    layer.open({
                        title:false,
                        content:'签到成功,本次签到获得'+ret.user_points+'金豆',
                        btn:['关闭'],
                        yes:function(index){
                            layer.closeAll();
                            location.reload();
                        },
                    });
                }
            },'json');
        });

        $("#dist_book").click(function(){
            var id = $(this).attr('data-id');
            layer.alert('您确定要分配图书？',{icon:3,title:'提示'},function() {
                var load = layer.load(3);
                $.ajax({
                    url: "<?php echo U('mobile.php/TIndex/distributeBook');?>",
                    type: 'get',
                    data: {teacher_id: id},
                    success: function (data) {
                        layer.close(load);
                        layer.msg(data.info, {time: 2500});
                    },
                    error: function (responseText, statusText) {
                        layer.close(load);
                        if (statusText == 'timeout') {
                            layer.msg("服务器繁忙，请稍后再试", {time: 3000});
                            return;
                        } else {
                            layer.msg(statusText, {time: 3000});
                        }
                    },
                    timeout: 5000,
                    dataType: 'json'

                });
            });
        });
    })
</script>
</body>
</html>