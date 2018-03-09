<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="renderer" content="webkit">
    <title>借阅</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/rili.css"/> 
<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/swiper.min.css"/> 

 
	<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
    <script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script> 
</head>
<body>
    <div class="wrap2">
        <div class="head-cate">
            <ul class="ul_2">
                <li  style="width:33%" 
                    <?php if(($circul_status) == "2"): ?>class="li_cur"<?php endif; ?>>
                    <a href="<?php echo U('mobile.php/TBorrow/index',array('teacher_id'=>$teacher_id,'circul_status'=>2));?>">
                        <span>借阅 </span>
                    </a>
                </li>
                <li  style="width:34%" 
                    <?php if(($circul_status) == "1"): ?>class="li_cur"<?php endif; ?>>
                    <a href="<?php echo U('mobile.php/TBorrow/index',array('teacher_id'=>$teacher_id,'circul_status'=>1));?>">
                        <span>归还</span>
                    </a>
                </li>
                <li style="width:33%" <?php if(($circul_status) == "3"): ?>class="li_cur"<?php endif; ?>>
                    <a href="<?php echo U('mobile.php/TBorrow/evaluate',array('teacher_id'=>$teacher_id,'circul_status'=>3));?>">
                        <span>评价</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="line_hr"></div>
        <div id="div1">
            <ul class="cart_list order_list">
				<?php if(empty($list)): ?><div class="data-empty">
					<p><img src="/Public/images/mobiles/empty.png"><p>
					<p>暂无数据</p>
					</div><?php endif; ?>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_cart">
                        <dl class="cart_pro">
                            <dt class="dt_tx"><img src="<?php echo ((isset($vo['book_thumb']) && ($vo['book_thumb'] !== ""))?($vo['book_thumb']):'Public/images/mobiles/default.png'); ?>" alt="" /></dt>
                            <dd class="dd_pname2">
								<p>&nbsp;</p>
                                <p><strong>NO.<?php echo ($vo["book_no"]); ?></strong> - <?php echo ($vo['book_name']); ?></p>
                                <p><a href="javascript:;" cid="<?php echo ($circulation_id); ?>" bid="<?php echo ($vo['book_id']); ?>" class="ibtn_1 change_book" style="float:right;">确认更换</a></p>
                            </dd>
                        </dl>
                        <div class="i_btn"></div>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </div>
      <a href="<?php echo U('mobile.php/TIndex/index',array('teacher_id'=>$teacher_id));?>">
<span class="f_index" style="bottom:15px;"><span class="iconfont icon-shouyeshouye"></span></span>
</a> 

<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script type="text/javascript">
$(function(){
    $(".change_book").click(function(){
        var cid = "<?php echo ($circulation_id); ?>";
		var tid = "<?php echo ($teacher_id); ?>";
		var sid = "<?php echo ($student_id); ?>";
		var cs = "<?php echo ($circul_status); ?>";
		var bid = $(this).attr("bid");
        $.post("<?php echo U('mobile.php/TBorrow/randombook');?>",{circulation_id:cid,book_id:bid},function(ret){
            if(ret > 0){
                layer.open({
                    title:false,
                    content:'更换图书成功',
                    btn:['关闭'],
                    yes:function(index){
                        layer.closeAll();
						window.location.href="/mobile.php?m=mobile.php&c=TBorrow&a=getborrow&circulation_id="+cid+"&teacher_id="+tid+"&circul_status="+cs+"&student_id="+sid;
                    },
                });
            }
        },'json');
    });
})
</script>
</body>
</html>