<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>购物车</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
</head>
<body>

<div class="wrap"> 
 <div class="head-cate" style="display:none;">
 	<ul class="ul_2">
 		<li style="display:none;" <?php if($ret_type == 2): ?>class="li_cur"<?php endif; ?>><a href="<?php echo U('mobile.php/Cart/index',array('user_id'=>$user_id,'ret_type'=>2,'user_flag'=>$user_flag));?>"><span>宝宝商城 </span></a></li>
 		<li style="display:none;" <?php if($ret_type == 1): ?>class="li_cur"<?php endif; ?>><a href="<?php echo U('mobile.php/Cart/index',array('user_id'=>$user_id,'ret_type'=>1,'user_flag'=>$user_flag));?>"><span>萌星商城</span></a></li>
 	</ul>
 </div>
 
 <ul class="cart_list ck_list" id="cart_list">
	<?php if(empty($cart_list)): ?><div class="data-empty">
		<p><img src="/Public/images/mobiles/empty.png"><p>
		<p>暂无数据</p>
		</div><?php endif; ?>
	<?php if(is_array($cart_list)): $i = 0; $__LIST__ = $cart_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart li_cart_ck" recid="<?php echo ($vo["rec_id"]); ?>" rettype="<?php echo ($vo["ret_type"]); ?>" <?php if($i == '1'): ?>style="padding-top:10px;"<?php endif; ?>>
			<dl class="cart_pro">
			<dt><img src="<?php echo ($vo["thumb"]); ?>" alt="" /></dt>
			<dd class="dd_pname"><?php echo ($vo["book_name"]); ?></dd>
			<dd class="dd_price">
				<?php if($ret_type == 2): ?><span class="s_price"><?php echo ($vo["shop_price"]); ?></span> 元 
				<?php elseif($ret_type == 1): ?>
					<span class="s_price"><?php echo ($vo["points_price"]); ?></span> 金豆<?php endif; ?>	
				
			<span class="s_plus"><a class="e_jian">-</a><span class="s_num" id="s_num2"><?php echo ($vo["book_number"]); ?></span><a class="e_jia">+</a></span> </dd>
			</dl>
		</li><?php endforeach; endif; else: echo "" ;endif; ?>
	
</ul>
 
 <a href="<?php echo ($url); ?>">
<span class="f_index" style="bottom:60px;">
	<span class="iconfont icon-shouyeshouye"></span>
</span>
</a> 
	<div class="c_del" id="a_del"></div>
</div>
 <div class="footer2">
    <ul class="footer_pbtn">
      <input type="hidden" id="user_id" value="<?php echo ($user_id); ?>">	
      <input type="hidden" id="user_flag" value="<?php echo ($user_flag); ?>">
      <li class="fbtn_l"><span class="pay_quan pay_quan_ed" id="a_ckall">全选</span></li>
      <li class="fbtn_c">合计：<span id="cart_amount" class="s_price"><?php echo ($cart_amount); ?></span> 金豆<?php if(ret_type == 1): ?>金豆<?php elseif(ret_type == 2): ?>元<?php endif; ?><div class="d_yunf" style="display:none;">包含运费：<span>0</span>元</div></li>
      <li class="fbtn_r"><a href="javascript:void(0);" class="btn_js">去结算（<span id="ck_val"><?php echo ($cart_number); ?></span>）</a> </li>
    </ul>
  </div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script type="text/javascript">
$(function(){

	$(".btn_js").click(function(){
		var list = $("#cart_list li");
		var values = [];
		var key = 0;
		for (var i=0;i<list.length;i++) {
			f = list.eq(i).hasClass('li_cart_ck');
			if(f){
				values[key] = list.eq(i).attr('recid');
				key++;
			}
		};
		var uid = "<?php echo ($user_id); ?>";
		var uflag = "<?php echo ($user_flag); ?>";
		//alert("/mobile.php?m=mobile.php&c=Order&a=checkout&user_id="+uid+"&user_flag="+uflag+"&recid="+values.join(','));
		location.href="/mobile.php?m=mobile.php&c=Order&a=checkout&user_id="+uid+"&user_flag="+uflag+"&recid="+values.join(',');
	});

	addCkVal();
	$("#a_ckall").bind("click",function(){
		if($(this).hasClass("pay_quan_ed")){
			$(this).removeClass("pay_quan_ed");
			$.each($("#cart_list .li_cart"),function(b, c) { 
				$(c).removeClass("li_cart_ck");		
			});
			$("#cart_amount").html(0);
			$("#ck_val").html(0);
		}else{
			if($("#cart_list .li_cart").length>0){
				$(this).addClass("pay_quan_ed");
				$.each($("#cart_list .li_cart"),function(b, c) { 
					$(c).addClass("li_cart_ck");		
				});

				var rec_id = 0;
				var ret_type = 1;
				var num = 0;
				var user_id = $("#user_id").val();
				var user_flag = $("#user_flag").val();

				$.post("<?php echo U('mobile.php/Cart/ajax_cart');?>",{rec_id:rec_id,cart_num:num,ret_type:ret_type,user_id:user_id,user_flag:user_flag},function(result){
					if(result){
						$("#cart_amount").text(result.cart_amount);
						$("#ck_val").text(result.cart_number);
					}
				},'json');

			}else{
				alert("购物车为空！");
			}
		}
		addCkVal();
	});
	
	$("#a_del").bind("click",function(){
	
		layer.open({
            title: false,
            content: '确定删除选中的图书?',
            btn:['确定','取消'],
            yes:function(index,layero){
                
                $.each($("#cart_list .li_cart_ck"),function(b, c) { 
					var rec_id = $(c).attr('recid');
					$.post("<?php echo U('mobile.php/Cart/del_cart');?>",{rec_id:rec_id},function(result){
						if(result > 0){
							$(c).remove();
							location.reload();
						}
					});				
				});

				$("#a_ckall").removeClass("pay_quan_ed");
				addCkVal();

            },
            btn2:function(index,layero){
                layer.closeAll();
            }
        }); 

		
	});
	$("#cart_list .li_cart").click(function(event){
		$(this).toggleClass("li_cart_ck")
		
		var l=$("#cart_list .li_cart").length;
		var l2=$("#cart_list .li_cart_ck").length;
		if(l2==l){
			$("#a_ckall").addClass("pay_quan_ed");	
		}else{
			$("#a_ckall").removeClass("pay_quan_ed");	
		}
		
		addCkVal();
		event.stopPropagation();

		//var rec_id = $(this).parent().parent().parent().parent("li").attr('recid');
		//var ret_type = $(this).parent().parent().parent().parent("li").attr('rettype');
		var list = $("#cart_list li");
		var values = [];
		var key = 0;
		for (var i=0;i<list.length;i++) {
			f = list.eq(i).hasClass('li_cart_ck');
			if(f){
				values[key] = list.eq(i).attr('recid');
				key++;
			}
		};
		var user_id = $("#user_id").val();
		var user_flag = $("#user_flag").val();
		$.post("<?php echo U('mobile.php/Cart/ajax_cart_two');?>",{rec_id:values.join(','),user_id:user_id,user_flag:user_flag},function(result){
			if(result){
				$("#cart_amount").text(result.cart_amount);
				$("#ck_val").text(result.cart_number);
			}
		},'json');
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
		var rec_id = $(this).parent().parent().parent().parent("li").attr('recid');
		var ret_type = $(this).parent().parent().parent().parent("li").attr('rettype');
		var user_id = $("#user_id").val();
		var user_flag = $("#user_flag").val();
		$.post("<?php echo U('mobile.php/Cart/ajax_cart');?>",{rec_id:rec_id,cart_num:num,ret_type:ret_type,user_id:user_id,user_flag:user_flag},function(result){
			if(result){
				$("#cart_amount").text(result.cart_amount);
				$("#ck_val").text(result.cart_number);
			}
		},'json');
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

		var rec_id = $(this).parent().parent().parent().parent("li").attr('recid');
		var ret_type = $(this).parent().parent().parent().parent("li").attr('rettype');
		var user_id = $("#user_id").val();
		var user_flag = $("#user_flag").val();
		$.post("<?php echo U('mobile.php/Cart/ajax_cart');?>",{rec_id:rec_id,cart_num:num,ret_type:ret_type,user_id:user_id,user_flag:user_flag},function(result){
			if(result){
				$("#cart_amount").text(result.cart_amount);
				$("#ck_val").text(result.cart_number);
			}
		},'json');
	});
	
	
})
function addCkVal(){
	var n=$("#cart_list").find(".li_cart_ck").length;
	//$("#ck_val").text(n);
	if(n>0){
		$("#a_del").fadeIn();
	}
}

</script>
</body>
</html>