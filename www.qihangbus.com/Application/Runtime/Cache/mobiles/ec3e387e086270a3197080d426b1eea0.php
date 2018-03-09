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
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body>

<div class="wrap2"> 
	<?php if($address_list != ''): ?><div class="address_t" style="display:none;"><span>编辑</span></div>
		<div class="line_hr"></div>
		<div class="address_list">
			<?php if(is_array($address_list)): $i = 0; $__LIST__ = $address_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="javascript:void(0);" class="adrdel" aid="<?php echo ($vo["address_id"]); ?>">
					<i class="adr_del">&nbsp;</i>
				</a>
				<a href="<?php echo U('mobile.php/Ucenter/address_edit/',array('address_id'=>$vo['address_id'],'student_id'=>$student_id,'user_flag'=>$user_flag));?>">	
					<dl class="adr_oitem <?php if($vo["address_id"] == $userinfo[address_id]): ?>adr_oitem_ck<?php endif; ?>">
						<dt class="dt_otit"><span class="s_name"><i class="ic_ad ic_ad_r"><?php echo (msubstr($vo["consignee"],0,4)); ?></i></span> <span><i class="ic_ad ic_ad_cell"><?php echo ($vo["mobile"]); ?></i></span></dt>
						<dd class="dd_dinfo"><?php echo ($vo["province_name"]); echo ($vo["city_name"]); echo ($vo["district_name"]); echo ($vo["address"]); ?></dd>
					</dl>
				</a><?php endforeach; endif; else: echo "" ;endif; ?>	
		</div>
		<br><br><br>
		<div class="btn_box "><a href="<?php echo U('mobile.php/Ucenter/add_address/',array('student_id'=>$student_id,'user_flag'=>$user_flag));?>" class="btn btn2 btn_address">添加新地址</a></div>
	<?php else: ?>
		<dl class="dl_null dl_null_address">
			<dt><img src="/Public/images/mobiles/ic_null_address.png" alt="" /></dt>
			<dd class="dd_tips">目前还没有收货地址哦~</dd>
			<dd class="dd_btn"><a href="<?php echo U('mobile.php/Ucenter/add_address/',array('student_id'=>$student_id,'user_flag'=>$user_flag));?>" class="btn btn2 btn_address">添加地址</a>  </dd>
		</dl><?php endif; ?>
	<a href="<?php echo U('mobile.php/Ucenter/index');?>">
<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
</a>
</div>


<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script type="text/javascript">
$(function(){
	$(".adrdel").click(function(){
		var aid = $(this).attr('aid');

		layer.open({
            title: false,
            content: '确定删除此收货地址？',
            btn:['确定','取消'],
            yes:function(index,layero){
                
                $.post("<?php echo U('mobile.php/Ucenter/del_address');?>",{address_id:aid},function(result){
					if(result > 0){

						layer.open({
                            title:false,
                            content:'删除成功',
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