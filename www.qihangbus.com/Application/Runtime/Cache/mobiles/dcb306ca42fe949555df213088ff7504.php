<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="renderer" content="webkit">
    <title>图书管理员_轮换管理</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
    <script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/js/mobiles/jquery.datePicker-min.js"></script>
    <link type="text/css" href="/Public/css/mobiles/iconfont.css" rel="stylesheet" />
</head>
<body>
<div class="wrap2">
    <!--  <div class="yuanzhang_t">
         <dl>
             <dt>
                 <a href="<?php echo U('mobile.php/MIndex/avatar',array('user_id'=>$info['admin_id']));?>">
                 <img src="<?php echo ($info["admin_avatar"]); ?>" alt="background_image" />
                 </a>
             <dd>
                 <div class="d_name">
                     <?php echo ($info["admin_name"]); ?>
                 </div>
                 <div class="d_class"><?php echo ($info["school_name"]); ?></div>
             </dd>
             </dt>
         </dl>
     </div>
     <div class="head-cate">
         <ul class="ul_2">
             <li style="width:33.33%" <?php if(($t) == "1"): ?>class="li_cur"<?php endif; ?>>
                 <a href="<?php echo U('mobile.php/MIndex/index',array('id'=>$id,'t'=>1));?>">
                     <span>轮换管理 </span>
                 </a>
             </li>
             <li style="width:33.33%" <?php if(($t) == "2"): ?>class="li_cur"<?php endif; ?>>
                 <a href="<?php echo U('mobile.php/MIndex/books',array('id'=>$id,'t'=>2));?>">
                     <span>图书管理</span>
                 </a>
             </li>
             <li style="width:33.33%" <?php if(($t) == "3"): ?>class="li_cur"<?php endif; ?>>
                 <a href="<?php echo U('mobile.php/MIndex/compen',array('id'=>$id,'t'=>3));?>">
                     <span>损毁管理</span>
                 </a>
             </li>
         </ul>
     </div>
     <div class="line_hr"></div> -->
    <style type="text/css">
        .cart_pro{height:30px;line-height:30px;margin-bottom:5px;}
        .ddpname{padding-left:10px!important;}
        .dd_pname{padding-left:40px!important;}
    </style>

    
    <div class="head-cate clearfix" style="margin:0;height:auto;">
        <ul class="ul_3" style="padding:0;">
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "暂无数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li style="width:100%;text-align:left;border-bottom:1px solid #F2F2F2;">
                        <span style="margin-left:20px;">
                            <?php echo ($vo["grade_name"]); ?> 上次调整日期为：<label><?php if(empty($vo["pre_change_time"])): ?>未轮换<?php else: echo (date('Y-m-d',$vo["pre_change_time"])); endif; ?></label>
                        </span>
                </li>
                <li style="width:100%;text-align:left;border-bottom:1px solid #F2F2F2;">
                        <span style="margin-left:20px;">
                           下次调整日期为：<label id="grade_time<?php echo ($vo["grade_id"]); ?>"><?php echo (date('Y-m-d',$vo["change_time"])); ?></label>
                        </span>
                    <a href="javascript:void(0);" gid="<?php echo ($vo["grade_id"]); ?>" class="lh" style="float:right;margin-right:10px;margin-top:10px;">轮换</a>
                    <a href="javascript:void(0);" gid="<?php echo ($vo["grade_id"]); ?>" class="ibtn_1" style="float:right;margin-right:10px;margin-top:10px;">日期调整</a>
                </li>
                <?php if(is_array($vo["lt"])): $i = 0; $__LIST__ = $vo["lt"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div style="margin-left:20px;height:40px;line-height:40px;float:left;clear:both;"><?php echo ($v["pre_class"]); ?> <span class="iconfont icon-youjiantou"></span> <?php echo ($v["next_class"]); ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="#E3ABFF"><?php echo ($v["num"]); ?>本</font> </div><?php endforeach; endif; else: echo "暂无数据" ;endif; ?>
                <div class="line_hr"></div><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
    <a href="<?php echo U('mobile.php/MIndex/index',array('id'=>$id));?>">
        <span class="f_index" style="bottom:15px;"><span class="iconfont icon-shouyeshouye"></span></span>
    </a>
</div>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script src="/Public/layer/layer.js"></script>
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

                        layer.open({
                            title:false,
                            content:'确认修改，并发送消息给园长和教师？',
                            btn:['确定'],
                            yes:function(index){
                                $.ajax({
                                    type: "get",
                                    url: "/mobile.php?m=mobile.php&c=MIndex&a=ajax_time&str_date="+encodeURI(str)+"&grade_id="+gid,
                                    async: false,
                                    dataType: "json",
                                    success: function(data) {
                                        $("#grade_time"+gid).text(str);
                                        //$(".p_title").text(data.task_title);
                                        //$(".p_desc").text(data.task_desc);
                                        layer.open({
                                            title:false,
                                            content:'修改成功',
                                            btn:['关闭'],
                                            yes:function(index){
                                                layer.closeAll();
                                            },
                                        });
                                    },
                                    error: function(XMLHttpRequest, textStatus, errorThrown)
                                    {
                                        alert(textStatus);
                                    }
                                });
                            },
                        });


                    });
                }
            });
        });

        $('.lh').click(function(){
            var gid = $(this).attr('gid');
            layer.confirm('您确定要轮换图书吗?',{title:'',icon:3},function(){
                $.ajax({
                    type:'post',
                    url:"/mobile.php?m=mobile.php&c=MIndex&a=rotate",
                    data:{gid:gid},
                    dataType:'json',
                    success:function(result){
                        if(result.status == 1){
                            layer.msg(result.info,{time:2000},function(){
                                location.reload();
                            });
                        }else{
                            layer.msg(result.info,{time:1000},function(index){
                                layer.close(index);
                            });
                        }

                    },
                    error:function(){
                        layer.msg('操作失败,请联系管理员!');
                    }
                });
            });
        });
    });
</script>
</body>
</html>