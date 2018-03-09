<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>管理学生</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
<link rel="stylesheet" href="/Public/css/mobiles/font-awesome/css/font-awesome.min.css">
</head>
<body>

<div class="wrap2"> 
	<?php if($list != ''): ?><div class="address_t" style="display:none;"><span>编辑</span></div>
		<div class="line_hr"></div>
		<div class="address_list">
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="javascript:void(0);" class="adrdel" aid="<?php echo ($vo["student_id"]); ?>">
					<i class="adr_del">&nbsp;</i>
				</a>
				<a href="<?php echo U('mobile.php/TIndex/student_edit/',array('student_id'=>$vo['student_id'],'user_id'=>$user_id,'user_flag'=>$user_flag));?>">	
					<dl class="adr_oitem">
						<dt class="dt_otit"><span class="s_name"><i class="fa fa-graduation-cap" style="color:#b4de86;margin-right: 5px;"></i><?php echo ($vo["student_name"]); ?></span></dt>
						<dd class="dd_dinfo">家长:<?php echo ($vo["parent_name"]); ?> <span><i class="ic_ad ic_ad_cell"><?php echo ($vo["parent_mobile"]); ?></i></span></dd>
					</dl>
				</a><?php endforeach; endif; else: echo "" ;endif; ?>	
		</div>
		<div class="btn_box "><a href="<?php echo U('mobile.php/TIndex/add_students/',array('user_id'=>$user_id,'user_flag'=>$user_flag));?>" class="btn btn2 btn_address">添加学生</a></div>
	<?php else: ?>
		<dl class="dl_null dl_null_address">
			<dt><img src="/Public/images/mobiles/ic_null_address.png" alt="" /></dt>
			<dd class="dd_tips">目前还没有学生哦~</dd>
			<dd class="dd_btn"><a href="<?php echo U('mobile.php/TIndex/add_students/',array('user_id'=>$user_id,'user_flag'=>$user_flag));?>" class="btn btn2 btn_address">添加学生</a>  </dd>
		</dl><?php endif; ?>
 <a href="<?php echo U('mobile.php/TIndex/index',array('teacher_id'=>$user_id));?>">
<span class="f_index" style="bottom:15px;"><span class="iconfont icon-shouyeshouye"></span></span>
</a> 	
</div>


<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script src="/Public/layer/layer.js"></script>
<script type="text/javascript">
$(function(){
	$(".adrdel").click(function(){
		var aid = $(this).attr('aid');

		layer.open({
            title: false,
            content: '确定删除？',
            btn:['确定','取消'],
            yes:function(index,layero){
                
                $.post("<?php echo U('mobile.php/Student/del');?>",{id:aid},function(result){
					if(result == 1){
                        layer.msg('删除成功!',{time:1000},function(){
                            location.reload();
                        });
					}else if(result == 99){

						layer.open({
                            title:false,
                            content:'删除失败，还有未归还的图书',
                            btn:['关闭'],
                            yes:function(index){
                                layer.closeAll();
                                location.reload();
                            },
                        });
					}else if(result == 98){

						layer.open({
                            title:false,
                            content:'删除失败，还有损坏未赔偿的图书',
                            btn:['关闭'],
                            yes:function(index){
                                layer.closeAll();
                                location.reload();
                            },
                        });
					}else if(result == 97){

						layer.open({
                            title:false,
                            content:'删除失败，参数错误',
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