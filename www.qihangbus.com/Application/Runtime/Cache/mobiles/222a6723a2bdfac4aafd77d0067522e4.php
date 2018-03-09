<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>绘本详情</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
<link rel="stylesheet" href="/Public/css/mobiles/swiper.min.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/lib/swiper.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script src="/Public/js/mobiles/layer/layer.js"></script>
</head>
<body class="">

<div class="wrap"> 
<div class="pro-show">
<div class="pro-slider">
 <!-- Swiper -->
    <div class="swiper-pro swiper-container" style="height:auto;">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
            		<img style="width:100%;height:300px;" src="<?php echo ($info["book_img"]); ?>">
            </div>
        </div>
        <!-- Add Pagination -->
		<div class="swiper-pagination"></div>
    </div>
<!-- Swiper -->
</div>
<div class="line_hr"></div>
<ul class="pro-ul-show">
	<li class="li_name"><strong class="s_name" id="s_name"><?php echo ($info["book_name"]); if(!empty($info["sub_name"])): ?>【<?php echo ($info["sub_name"]); ?>】<?php endif; ?></strong></li>
	<li class="" style="color:#999;"><?php echo ($info["book_author"]); ?></li>
	<li class="li_price"><strong class="s_price"><?php echo ($info["points_price"]); ?> </strong>金豆</li>
</ul>
<div class="line_hr"></div>
<dl class="dl_protoggle">
	<dt class="am-accordion-title">作者简介</dt>
	<dd class="am-accordion-bd">
		<div class="xx_pro"><p style="text-indent: 2em;"><?php echo ($info["author_desc"]); ?></p></div>
	</dd>
</dl>
<div class="line_hr"></div>
<dl class="dl_protoggle">
	<dt class="am-accordion-title">绘本简介</dt>
	<dd class="am-accordion-bd">
		<div class="xx_pro"><p  style="text-indent:2em;"><?php echo ($info["book_desc"]); ?></p></div>
	</dd>
</dl>
</div>
<a href="<?php echo U('mobile.php/Ucenter/index');?>">
<span class="f_index" style="bottom:55px;"><span class="iconfont icon-shouyeshouye"></span></span>
</a>
</div>
 <div class="footer2">
 	<div class="c_cart" style="display:none;"></div>	
    <ul class="footer_pbtn">
      <li class="fbtn_l"><span class="s_plus"><a class="e_jian">-</a><span class="s_num" id="s_num3">1</span><a class="e_jia">+</a></span> </li>
	  <input type="hidden" id="book_id" value="<?php echo ($book_id); ?>"/>
      <input type="hidden" id="student_id" value="<?php echo ($userinfo["student_id"]); ?>"/>
      <input type="hidden" id="user_flag" value="<?php echo ($user_flag); ?>"/>
      <li class="fbtn_r2"><a href="javascript:;" id="addtocart" class="btn_buy">加入购物车</a> </li>
      <li class="fbtn_r"><a href="javascript:;" id="bookborrow" class="btn_js">预约借阅</a> </li>
    </ul>
  </div>
<script type="text/javascript">
	$(function(){

		$("#bookborrow").click(function(){
      		var book_id = $("#book_id").val();
			var student_id = $("#student_id").val();

			$.post("<?php echo U('mobile.php/Borrowtoread/add_to_borrow');?>",{book_id:book_id,student_id:student_id},function(result){
				if(result == 1){
					layer.msg('预约借阅成功',{time:2000});
				}else if(result == 99){
					layer.msg('绘本已预约，请勿重复预约',{time:3000});
				}else if(result == 98){
					layer.msg('绘本已被其他小朋友预约',{time:3000});
				}else if(result == 97){
					layer.msg('同一天内已超出预约限制',{time:3000});
				}else if(result == 96){
					layer.msg('不可预约正在阅读的绘本',{time:3000});
				}else if(result == 95){
					layer.msg('请先归还绘本',{time:3000});
				}else if(result == 94){
					layer.msg('您有损坏未赔偿的绘本',{time:3000});
				}else if(result == 93){
					layer.msg('有小朋友正在阅读此绘本，请选择其它绘本',{time:3000});
				}else if(result == 10001){
					layer.msg('开通会员后才能借阅',{time:3000});
				}else if(result == 10002){
					layer.msg('请您等待新学期开学',{time:3000});
				}else{
                    alert(result);
//					layer.msg('参数错误，请重新预约',{time:3000});
				}
			});
      	});

		var text = $("#togg_text").text();

      	var flag = text.length > 40 ? true : false;
      	if(flag){
          	$("#togg_text").html("");
          	$("#togg_text").append("<p>" + text.substring(0, 40) 
                    + "<span id='hide' style='display:none'>" + text.substring(40) + "</span>"
                    + "<a href='javascript:;' id='open' class='color1'> 显示全部</a></p>");
      	}

      	$("#open").click(function(){
          	if(flag){
              	$("#open").text("隐藏");
              	$("#hide").show();
              	flag = false;
          	} else{
              	$("#open").text("显示全部");
              	$("#hide").hide();
              	flag = true;
          	}       
         });

		$("#addtocart").click(function(){
			var book_num = $("#s_num3").text();
			var book_id = $("#book_id").val();
			var student_id = $("#student_id").val();
			var user_flag = $("#user_flag").val();
			$.post("<?php echo U('mobile.php/Mybag/add_to_cart');?>",{book_id:book_id,book_num:book_num,student_id:student_id},function(result){
				if(result > 0){
					//alert('添加到购物车');
					//window.location.href="/mobile.php?m=mobile.php&c=Cart&a=index&user_id="+student_id+"&user_flag=3"

					layer.open({
			            title: false,
			            content: '添加到购物车',
			            btn:['去购物车','继续购物'],
			            yes:function(index,layero){
			                
			            	window.location.href="mobile.php?m=mobile.php&c=Cart&a=index&user_id="+student_id+"&user_flag="+user_flag;

			            },
			            btn2:function(index,layero){
			                layer.closeAll();
			                $("#s_num3").html(1);
			            }
			        });

				}
			});
		});	

		$(".e_jian").click(function(event){
			var n=$(this).parent(".s_plus").find(".s_num").text();
			var num=parseInt(n)-1;
			if(num==0){ 
				event.stopPropagation();
				return
			}
			$(this).parent(".s_plus").find(".s_num").text(num);
			event.stopPropagation();
		});
		$(".e_jia").click(function(event){
			var n=$(this).parent(".s_plus").find(".s_num").text();
			var num=parseInt(n)+1;
			if(num>1000){ 
				event.stopPropagation();
				return
			}
			$(this).parent(".s_plus").find(".s_num").text(num);
			event.stopPropagation();
		});
		
		var swiper = new Swiper('.swiper-container', {
			pagination: '.swiper-pagination',
			nextButton: '.swiper-button-next',
			prevButton: '.swiper-button-prev',
			loop: true,
			 paginationType: 'fraction'
		});
	})
</script>
</body>
</html>