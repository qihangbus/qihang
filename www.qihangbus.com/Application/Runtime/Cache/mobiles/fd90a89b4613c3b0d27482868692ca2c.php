<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>商品详情</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
<link rel="stylesheet" href="/Public/css/mobiles/swiper.min.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</style>
</head>
<body class="">

<div class="wrap"> 
<div class="pro-show">
<div class="pro-slider">
 <!-- Swiper -->
    <div class="swiper-pro swiper-container">
        <div class="swiper-wrapper">
<!--             <?php if(is_array($book_gallery)): $i = 0; $__LIST__ = $book_gallery;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
                	<img src="<?php echo ($vo["img_url"]); ?>">
                </div><?php endforeach; endif; else: echo "" ;endif; ?> -->
        	<div class="swiper-slide">
                	<!-- <img width="200" height="330" src="<?php echo ($product_info["img_url"]); ?>"> -->
                	<img style="width:100%;height:300px;" src="<?php echo ($product_info["img_url"]); ?>">
            </div>
        </div>

        <!-- Add Pagination -->
		<div class="swiper-pagination"></div>
    </div>
<!-- Swiper -->
</div>
<div class="line_hr"></div>
<ul class="pro-ul-show">
	<li class="li_name"><strong class="s_name" id="s_name"><?php echo ($product_info["product_name"]); ?></strong></li>
	<li class="li_price">金豆兑换价：<strong class="s_price"><?php echo ($product_info["product_price"]); ?> </strong>金豆</li>
	<li class="li_price">本店价：<strong class="s_price"><?php echo ($product_info["shop_price"]); ?></strong>元</li>
	<!-- <li class="li_desc color2" style="display:none;" id="togg_text"><?php echo ($book_info["book_desc"]); ?></li> -->
</ul>
<div class="line_hr"></div>
<dl class="dl_protoggle">
	<dt class="am-accordion-title">产品描述</dt>
	<dd class="am-accordion-bd" id="s_description">
	<!-- <div class="xx_pro"><?php echo ($book_info["book_author"]); echo ($book_info["author_desc"]); ?></div> -->
	<div class="xx_pro"><?php echo ($product_info["product_desc"]); ?></div>
	</dd>
</dl>






<?php if($product_info['is_cell'] == 1): ?><div class="line_hr"></div>
<ul class="ul_form">
	<li><span class="s_input"><input type="tel" id="mobile" name="mobile" value="请输入手机号码" maxlength="11"  /></span></li>
</ul><?php endif; ?>
<div class="line_hr"></div>
<?php if($product_info["content_desc"] != ''): ?><dl class="dl_protoggle">
	<dt class="am-accordion-title">内容简介</dt>
	<dd class="am-accordion-bd">
	<?php echo ($product_info["content_desc"]); ?>
	</dd>
</dl><?php endif; ?>

</div>
<a href="<?php echo ($url); ?>">
<span class="f_index" style="bottom:55px;"><span class="iconfont icon-shouyeshouye"></span></span>
</a> 
</div>
 <div class="footer2">
	
	<ul class="footer_pbtn">
<?php if($product_info['is_cell'] == 0): ?><li class="fbtn_l"><span class="s_plus"><a class="e_jian">-</a><span class="s_num" id="s_num3">1</span><a class="e_jia">+</a></span> </li><?php endif; ?>  
	  <input type="hidden" id="product_id" value="<?php echo ($product_id); ?>"/>
	  <input type="hidden" id="user_id" value="<?php echo ($user_id); ?>"/>
      <input type="hidden" id="user_flag" value="<?php echo ($user_flag); ?>"/>
    <?php if($product_info['is_cell'] == 0): ?><li class="fbtn_r2"><a href="javascript:;" id="addtocart" class="btn_buy">加入购物车</a> </li>

    <?php else: ?>  
      <li class="fbtn_r2"><a href="javascript:;" id="addtocart1" class="btn_buy">充值</a> </li><?php endif; ?>
    </ul>
  </div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/lib/swiper.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script type="text/javascript">
	$(function(){

		$(".title_zz").click(function(){
			$("#s_description").toggle();
		})

		var text = $("#togg_text").text();

      	var flag = text.length > 40 ? true : false;
      	if(flag){
          	$("#togg_text").html("");
          	$("#togg_text").append("<p>" + text.substring(0, 40) 
                    + "<span id='hide' style='display:none'>" + text.substring(40) + "</span>"
                    + "<a href='javascript:;' id='open' class='color3'> 显示全部</a></p>");
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
      	$("#addtocart1").click(function(){
      		var product_id = $("#product_id").val();
			var user_id = $("#user_id").val();
			var user_flag = $("#user_flag").val();
			var mobile = $('#mobile').val();
      	if(mobile == '请输入手机号码' || mobile.length<11)
			{
				            layer.open({
                            title:false,
                            content:'请输入手机号码',
                            btn:['关闭'],
                            yes:function(index){
                                layer.closeAll();
                                location.reload();
                            },
                        });
				return;       
			}	
      	 // window.location.href="mobile.php?m=mobile.php&c=book&a=checkout_cell&user_id="+user_id+"&user_flag="+user_flag+"&product_id="+product_id+"&mobile="+mobile;
      	});
		$("#addtocart").click(function(){
			// var book_num = $("#s_num3").text();
			// var book_id = $("#book_id").val();
			// var user_id = $("#user_id").val();
			var product_num = $("#s_num3").text();
			var product_id = $("#product_id").val();
			var user_id = $("#user_id").val();
			var user_flag = $("#user_flag").val();
			// 判断是否输入手机号
			var mobile = $('#mobile').val();
			if(mobile == '请输入手机号码')
			{
				            layer.open({
                            title:false,
                            content:'请输入手机号码',
                            btn:['关闭'],
                            yes:function(index){
                                layer.closeAll();
                                location.reload();
                            },
                        });
				return;
			}
			$.post("<?php echo U('mobile.php/Book/add_to_cart');?>",{product_id:product_id,product_num:product_num,user_id:user_id,user_flag:user_flag,mobile:mobile},function(result){
				if(result > 0){
					layer.open({
			            title: false,
			            content: '添加到购物车',
			            btn:['去购物车','继续购物'],
			            yes:function(index,layero){
			                window.location.href="mobile.php?m=mobile.php&c=Cart&a=index&user_id="+user_id+"&user_flag="+user_flag;
			            },
			            btn2:function(index,layero){
			                layer.closeAll();
			                $("#s_num3").html(1);
							//window.location.href="mobile.php?m=mobile.php&c=Book&a=index&user_id="+user_id+"&user_flag="+user_flag+"&user_points="+user_points;		
			            }
			        }); 

					//alert('添加到购物车');
					//window.location.href="<?php echo U('mobile.php/Cart/index/user_id/"+user_id+"/user_flag/"+user_flag+"');?>";
					//window.location.href="mobile.php?m=mobile.php&c=Cart&a=index&user_id="+user_id+"&user_flag="+user_flag;
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