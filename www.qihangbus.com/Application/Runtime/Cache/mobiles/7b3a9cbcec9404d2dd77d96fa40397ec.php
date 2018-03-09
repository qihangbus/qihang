<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta name="renderer" content="webkit">
    <title>个人中心</title>
    <link rel="stylesheet" href="/Public/css/mobiles/base.css">
    <link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
    <link rel="stylesheet" href="/Public/css/mobiles/app.css">
    <link rel="stylesheet" href="/Public/css/mobiles/swiper.min.css">
    <link rel="stylesheet" href="/Public/css/mobiles/font-awesome/css/font-awesome.min.css">
</head>
<body>

<div class="wrap2 have-bottom-swiper">
    <div class="jiaos_t jiaos_t_j">
        <div class="head-top"><?php echo ($userinfo["school_name"]); ?></div>
        <dl>
            <dt>
                <a href="<?php echo U('mobile.php/Ucenter/avatar',array('user_id'=>$userinfo['student_id'],'user_flag'=>3));?>">
                    <img src="<?php echo ((isset($userinfo["student_avatar"]) && ($userinfo["student_avatar"] !== ""))?($userinfo["student_avatar"]):'/Public/images/mobiles/default.png'); ?>" alt="" />
                </a>
            </dt>
            <dd>
                <div class="qiandao-div">
                    <?php if($signin < '0'): ?><div class="d_signin" id="signin" uid="<?php echo ($userinfo["student_id"]); ?>"><span class="iconfont icon-qiandao"></span></div>
                        <?php else: ?>
                        <div class="d_signin d_signin_d">
                            <span class="iconfont icon-qiandao"></span>
                        </div><?php endif; ?>
                    <input type="hidden" id="signnum" value="<?php echo ($signnum); ?>">
                </div>
                <div class="d_name">

                <?php if($day == 21): ?><a href="<?php echo U('mobile.php/Ucenter/medal');?>"><img src="/Public/images/medal-bronze.png" alt="" /></a><?php endif; ?>

                <?php echo ($userinfo["student_name"]); ?>-<?php echo ($userinfo["class_name"]); ?>
                </div>
            </dd>
        </dl>
    </div>
    <div class="head-cate">
        <ul class="ul_4">
            <li><a href="<?php echo U('mobile.php/Ucenter/get_levle_info',array('student_id'=>$userinfo['student_id'],'student_points'=>$userinfo['student_points'],'student_rank'=>$userinfo['student_rank']));?>">
                <span class="iconfont icon-shiliangzhinengduixiang-01"></span>
                <span class="head-cate-txt">等级</span>
            </a>
            </li>
            <li class=""><a href="<?php echo U('mobile.php/Ucenter/get_class_rank',array('student_id'=>$userinfo['student_id'],'student_points'=>$userinfo['student_points'],'class_id'=>$userinfo['class_id']));?>">
                <span class="iconfont icon-paiming"></span>
                <span class="head-cate-txt">排名</span>
            </a>
            </li>
            <li><a href="<?php echo U('mobile.php/Book/index',array('user_id'=>$userinfo['student_id'],'user_points'=>$userinfo['student_points'],'user_flag'=>3));?>">
                <span class="iconfont icon-duihuan"></span>
                <span class="head-cate-txt">兑换中心</span>
            </a>
            </li>
            <li>
                <?php if(!empty($pay_url)): ?><a href="<?php echo ($pay_url); ?>">
                        <i class="fa fa-user" style="font-size:28px;margin-top:6px;color:#AEE078"></i>
                        <span class="head-cate-txt">开通会员</span>
                    </a>
                    <?php else: ?>
                    <a href="javascript:void(0);" id="pay">
                        <i class="fa fa-user" style="font-size:28px;margin-top:6px;color:#AEE078"></i>
                        <span class="head-cate-txt">开通会员</span>
                    </a><?php endif; ?>
            </li>
        </ul></div>
    <ul class="m_menu clearfix">
        <li class="li_item"><a href="<?php echo U('mobile.php/Mybag/index',array('user_flag'=>3,'user_id'=>$userinfo['student_id']));?>">
            <span class="iconfont icon-shujia"></span>
            <span class="m_menu-txt">班级书架</span>
        </a></li>
        <li class="li_item"><a href="<?php echo U('mobile.php/Borrowtoread/index',array('student_id'=>$userinfo['student_id'],'user_flag'=>3));?>">
            <span class="iconfont icon-jiebei"></span>
            <span class="m_menu-txt">借阅</span>
        </a></li>
        <li class="li_item"><a href="<?php echo U('mobile.php/Ucenter/task',array('student_id'=>$userinfo['student_id']));?>">
            <span class="iconfont icon-renwu"></span>
            <span class="m_menu-txt">任务</span>
        </a></li>
        <li class="li_item"><a href="<?php echo U('mobile.php/Borrowtoread/history',array('student_id'=>$userinfo['student_id']));?>">
            <span class="iconfont icon-yuedu2"></span>
            <span class="m_menu-txt">阅历</span>
        </a></li>
        <li class="li_item <?php if($userinfo['message_num'] > 0): ?>li_item_p<?php endif; ?>"><a href="<?php echo U('mobile.php/Ucenter/get_message_list',array('student_id'=>$userinfo['student_id']));?>">
        <span class="iconfont icon-xiaoxi"></span>
        <span class="m_menu-txt">消息</span>
    </a></li>
        <li class="li_item ">
            <a href="/default.php/Live/Index">
                <span class="fa fa-graduation-cap" style="font-size:39px;margin-top: 3px;color:#ff9d4d"></span>
                <span class="m_menu-txt">专家讲堂</span>
            </a>
        </li>
        <li class="li_item ">
            <a href="/sns.php?m=sns.php&c=forum&a=bbs">
                <span class="fa fa-group" style="font-size:39px;margin-top: 3px;color:#C492DC"></span>
                <span class="m_menu-txt">论坛</span>
            </a>
        </li>
        <li class="li_item "><a href="<?php echo U('mobile.php/Ucenter/setting',array('student_id'=>$userinfo['student_id'],'user_flag'=>3));?>">
            <span class="iconfont icon-shezhi"></span>
            <span class="m_menu-txt">设置</span>
        </a></li>
        
<!--         <?php if($userinfo['student_id'] == 1616): ?><li class="li_item"><a href="<?php echo U('mobile.php/Ucenter/punch',array('student_id'=>1616));?>">
            <span class="iconfont icon-yuedu2"></span>
            <span class="m_menu-txt">打卡</span>
        </a></li><?php endif; ?> -->
    </ul>

    <div class="line_hr"></div>
    </div>

     <div class="footer2">
         <!-- Swiper -->
            <div class="swiper-home swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <a href="<?php echo U('mobile.php/Ucenter/punch',array('student_id'=>$userinfo['student_id']));?>"><img style="height:130.2px;" src="/Public/images/mobiles/644147122725027632.png"></a>
                        <!-- <a><img src="/Public/images/mobiles/ban_mx.jpg"></a> -->
                    </div>
                    <!-- <div class="swiper-slide">
                        <img src="/Public/images/mobiles/ban_mx.jpg">
                    </div> -->
                    <div class="swiper-slide">
                        <a href="https://www.wjx.cn/jq/19156772.aspx"><img style="height:130.2px;" src="/Public/images/mobiles/394816686640877477.png"></a>
                    </div>
                </div>
                <!-- Add Pagination -->
                <div class="swiperp_box"><div class="swiper-pagination"></div></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        <!-- Swiper -->
        </div>

<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/lib/swiper.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script src="/Public/js/mobiles/layer/layer.js"></script>
<script type="text/javascript">
    $(function(){
        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
            autoplay: 2500,
            loop: true,
            paginationClickable: true
        });
        var bHeight = $('.swiper-home').height();
        $('.have-bottom-swiper').css("bottom",bHeight);

        $("#signin").click(function(){
            var uid = $(this).attr('uid');
            var num = $("#signnum").val();
            var uflag = 3;
            $.post("<?php echo U('mobile.php/Ucenter/signin');?>",{user_id:uid,user_flag:uflag,signnum:num},function(ret){
                if(ret.code > 0){
                    layer.open({
                        title:false,
                        content:'签到成功,本次签到获得'+ret.user_points+'金豆',
                        btn:['关闭'],
                        yes:function(index){
                            layer.closeAll();
                            location.reload();
                        }
                    });
                }
            },'json');
        });
        <?php if(($pay_tips) == "1"): ?>setTimeout("tips()",500);<?php endif; ?>
        <?php if(empty($pay_url)): ?>$('#pay').click(function(){
                    layer.alert("已开通(<?php echo (date('Y-m-d',$paid_time)); ?> 至 <?php echo (date('Y-m-d',$end_time)); ?>)",{title:'会员信息'});
                });<?php endif; ?>
    });
    function tips(){
        layer.open({
            title: '',
            content: '您是否要开通会员',
            icon: 3,
            btn: ['开通'],
            yes: function(index, layero){
                location.href = "<?php echo ($turn); ?>";
            },
            cancel: function(){
                location.href = "<?php echo U('Ucenter/index');?>";
            }
        });
    }
</script>
</body>
</html>