<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>收货地址</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
</head>
<body>

<div class="wrap2"> 
	<?php if($address_list != ''): ?><div class="address_t" style="display:none;"><span>编辑</span></div>
		<div class="line_hr"></div>
		<div class="address_list">
			<?php if(is_array($address_list)): $i = 0; $__LIST__ = $address_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('mobile.php/Order/checkout/',array('user_id'=>$user_id,'recid'=>$recid,'user_flag'=>$user_flag,'address_id'=>$vo['address_id']));?>" style="position:relative:z-index:1;">	
					<dl class="adr_oitem <?php if($vo["address_id"] == $userinfo[address_id]): ?>adr_oitem_ck<?php endif; ?>">
						<dt class="dt_otit"><span class="s_name"><i class="ic_ad ic_ad_r"><?php echo ($vo["consignee"]); ?></i></span> <span><i class="ic_ad ic_ad_cell"><?php echo ($vo["mobile"]); ?></i></span></dt>
						<dd class="dd_dinfo"><?php echo ($vo["province_name"]); echo ($vo["city_name"]); echo ($vo["district_name"]); echo ($vo["address"]); ?></dd>
					</dl>
				</a><?php endforeach; endif; else: echo "" ;endif; ?>	
		</div>
		<br><br><br>
		<div class="btn_box "><a href="<?php echo U('mobile.php/Order/add_address/',array('user_id'=>$user_id,'user_flag'=>$user_flag,'recid'=>$recid));?>" class="btn btn2 btn_address">添加新地址</a></div>
	<?php else: ?>
		<dl class="dl_null dl_null_address">
			<dt><img src="/Public/images/mobiles/ic_null_address.png" alt="" /></dt>
			<dd class="dd_tips">目前还没有收货地址哦~</dd>
			<dd class="dd_btn"><a href="<?php echo U('mobile.php/Order/add_address/',array('user_id'=>$user_id,'user_flag'=>$user_flag,'recid'=>$recid));?>" class="btn btn2 btn_address">添加地址</a>  </dd>
		</dl><?php endif; ?>
	
</div>


<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script type="text/javascript">

</script>
</body>
</html>