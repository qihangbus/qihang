<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>家长列表</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
<link rel="stylesheet" href="/Public/css/mobiles/font-awesome/css/font-awesome.min.css">
</head>
<body>

<div class="wrap2"> 

	<div class="address_t" style="display:none;"><span>编辑</span></div>
<div class="line_hr"></div>
	<div class="address_list zj_list">
		<?php if(is_array($parent_list)): $i = 0; $__LIST__ = $parent_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['flag'] < 1): ?><a href="javascript:void(0);" class="adrdel" pid="<?php echo ($vo["parent_id"]); ?>">
				<i class="adr_del">&nbsp;</i>
			</a><?php endif; ?>
			<a href="<?php echo U('mobile.php/Ucenter/parent_edit/',array('parent_id'=>$vo['parent_id'],'user_flag'=>$user_flag));?>">
				<dl class="adr_oitem">
					<dt class="dt_otit"><span class="s_name"><i class="fa fa-graduation-cap" style="color:#b4de86;margin-right: 5px;"></i><?php echo (msubstr($vo["parent_name"],0,4)); ?></span> <span><i class="ic_ad ic_ad_cell"><?php echo ($vo["parent_mobile"]); ?></i></span></dt>
					<dd class="dd_dinfo color2"><?php if($vo['parent_sex'] == 1): ?>孩子父亲<?php elseif($vo['parent_sex'] == 2): ?>孩子母亲<?php else: ?>其他<?php endif; ?></dd>
				</dl>
			</a><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
<br><br><br>
<div class="btn_box btn_boxadd"><a href="<?php echo U('mobile.php/Ucenter/add_parent/',array('student_id'=>$student_id,'user_flag'=>$user_flag));?>" class="btn btn2 btn_addjz">添加新家长</a></div>

<a href="<?php echo U('mobile.php/Ucenter/index');?>">
<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
</a>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script type="text/javascript">
$(function(){
	$(".adrdel").click(function(){
		var pid = $(this).attr('pid');

		layer.open({
            title: false,
            content: '确定删除此家长信息？',
            btn:['确定','取消'],
            yes:function(index,layero){
                
                $.post("<?php echo U('mobile.php/Ucenter/del_parent');?>",{parent_id:pid},function(result){
					if(result == 1){

						layer.open({
                            title:false,
                            content:'删除成功',
                            btn:['关闭'],
                            yes:function(index){
                                layer.closeAll();
                                location.reload();
                            },
                        });
					}else if(result == 99){

						layer.open({
                            title:false,
                            content:'删除失败',
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