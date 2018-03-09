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
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body>

<div class="wrap2"> 

	<div class="address_t"><a href="javascript:history.back(-1);"><span>取消</span></a></div>
<div class="line_hr"></div>
<form name="myform" id="myform" action="<?php echo U('mobile.php/TIndex/edit_address');?>" method="post" onsubmit="return check(this);">
<input type="hidden" name="address_id" value="<?php echo ($address_id); ?>">
<input type="hidden" name="user_id" value="<?php echo ($user_id); ?>">	
<input type="hidden" name="user_flag" value="<?php echo ($user_flag); ?>">	
<ul class="ul_form">
	<li><span class="s_input "><input type="text" id="consignee" name="consignee" value="<?php echo ($address_info["consignee"]); ?>" placeholder="收货人姓名" maxlength="5" /></span></li>
	<li><span class="s_input "><input type="tel" id="mobile" name="mobile" value="<?php echo ($address_info["mobile"]); ?>" placeholder="手机号码" maxlength="11"  /></span></li>
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
	<li><span class="s_input "><input type="text" id="address" name="address" value="<?php echo ($address_info["address"]); ?>" placeholder="街道地区" /></span></li>
</ul>	
<div class="line_hr"></div>
<ul class="ul_form">
	<li>
		<span class="s_label">设置为默认地址</span> 
		<div class="s_r ">
			<span class="s_checkbox">
				<input type="checkbox" value="1" <?php if($aid == $address_id): ?>checked<?php endif; ?> class="icheckbox" name="default">
				<label class="iradio" for="checkbox"></label>
			</span>
		</div>
	</li>
</ul>	

<br><br><br>
<div class="btn_box "><a href="javascript:void(0);"  class="btn btn2 btn_address">保存</a></div>
</form>

 <a href="<?php echo U('mobile.php/TIndex/index',array('teacher_id'=>$user_id));?>">
<span class="f_index" style="bottom:15px;"><span class="iconfont icon-shouyeshouye"></span></span>
</a> 
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script type="text/javascript">
function check()
{
	var consignee = $("#consignee").val();
	var mobile = $("#mobile").val();
	var province = $("#province").val();
	var city = $("#city").val();
	var district = $("#district").val();
	var address = $("#address").val();
	if(consignee == ''){

		layer.open({
            title:false,
            content:'收货人姓名不能为空',
            btn:['关闭'],
            yes:function(index){
                layer.closeAll();
            },
        });
		return false;
	}

	if(mobile == ''){

		layer.open({
            title:false,
            content:'手机号码不能为空',
            btn:['关闭'],
            yes:function(index){
                layer.closeAll();
            },
        });
		return false;
	}

	if(province == ''){

		layer.open({
            title:false,
            content:'请选择省',
            btn:['关闭'],
            yes:function(index){
                layer.closeAll();
            },
        });
		return false;
	}

	if(city == ''){

		layer.open({
            title:false,
            content:'请选择市',
            btn:['关闭'],
            yes:function(index){
                layer.closeAll();
            },
        });
		return false;
	}

	if(district == ''){

		layer.open({
            title:false,
            content:'请选择区',
            btn:['关闭'],
            yes:function(index){
                layer.closeAll();
            },
        });
		return false;
	}

	if(address == ''){

		layer.open({
            title:false,
            content:'详细地址不能为空',
            btn:['关闭'],
            yes:function(index){
                layer.closeAll();
            },
        });
		return false;
	}
	return true;
}
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