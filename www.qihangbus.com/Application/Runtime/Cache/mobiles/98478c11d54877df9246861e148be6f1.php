<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>地址信息</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
</head>
<body>

<div class="wrap2"> 

	<div class="address_t"><a href="javascript:history.back(-1);"><span>取消</span></a></div>
<div class="line_hr"></div>
<form name="myform" id="myform" action="<?php echo U('mobile.php/Order/edit_address');?>" method="post">
<input type="hidden" name="address_id" value="<?php echo ($address_id); ?>">
<input type="hidden" name="user_id" value="<?php echo ($user_id); ?>">	
<input type="hidden" name="user_flag" value="<?php echo ($user_flag); ?>">	
<input type="hidden" name="recid" value="<?php echo ($recid); ?>">	
<ul class="ul_form">
	<li><span class="s_input "><input type="text" name="consignee" value="<?php echo ($address_info["consignee"]); ?>" placeholder="收货人姓名" maxlength="4" /></span></li>
	<li><span class="s_input "><input type="tel" name="mobile" value="<?php echo ($address_info["mobile"]); ?>" placeholder="手机号码" maxlength="11"  /></span></li>
	<li><span class="s_input ">
		<select name="province" id="province">
			<?php if(is_array($province_list)): $i = 0; $__LIST__ = $province_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pl): $mod = ($i % 2 );++$i;?><option value="<?php echo ($pl["region_id"]); ?>" <?php if($pl["region_id"] == $address_info.province): ?>selected<?php endif; ?>><?php echo ($pl["region_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>	
		</select>
		<select name="city" id="city">
			<?php if(is_array($city_list)): $i = 0; $__LIST__ = $city_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cl): $mod = ($i % 2 );++$i;?><option value="<?php echo ($cl["region_id"]); ?>" <?php if($cl["region_id"] == $address_info.city): ?>selected<?php endif; ?>><?php echo ($cl["region_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>		
		</select>
		<select name="district" id="district">
			<?php if(is_array($district_list)): $i = 0; $__LIST__ = $district_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dl): $mod = ($i % 2 );++$i;?><option value="<?php echo ($dl["region_id"]); ?>" <?php if($dl["region_id"] == $address_info.district): ?>selected<?php endif; ?>><?php echo ($dl["region_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>		
		</select>
	</span></li>
	<li><span class="s_input "><input type="text" name="address" value="<?php echo ($address_info["address"]); ?>" placeholder="街道地区" /></span></li>
</ul>		

<br><br><br>
<div class="btn_box "><a href="javascript:void(0);"  class="btn btn2 btn_address">保存</a></div>
</form>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#province").change(function(){
		var region_id = $(this).val();
		$.post("<?php echo U('mobile.php/TIndex/ajax_address');?>",{region_id:region_id,region_level:2},function(result){
			$("#city")[0].options.length = 0;
			$("#city")[0].options.add(new Option("请选择",""));
			for (var i = 0; i < result.length; i++) {	
				var date = result[i];
                $("#city")[0].options.add(new Option(date.region_name, date.region_id));
			}
			$("#district")[0].options.length = 0;
			$("#district")[0].options.add(new Option("请选择",""));
		},'json');	
	});

	$("#city").change(function(){
		var region_id = $(this).val();
		$.post("<?php echo U('mobile.php/TIndex/ajax_address');?>",{region_id:region_id,region_level:3},function(result){
			$("#district")[0].options.length = 0;
			$("#district")[0].options.add(new Option("请选择",""));
			for (var i = 0; i < result.length; i++) {	
				var date = result[i];
                $("#district")[0].options.add(new Option(date.region_name, date.region_id));
			}
		},'json');	
	});

	$(".btn_address").click(function(){
		$("#myform").submit();	
	});
});
</script>
</body>
</html>