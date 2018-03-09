<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-dns-prefetch-control" content="on">
	<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<meta name="renderer" content="webkit">
	<title>任务列表</title>
	<link rel="stylesheet" href="/Public/css/mobiles/base.css">
	<link rel="stylesheet" href="/Public/css/mobiles/app.css">
	<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
	<link rel="stylesheet" href="/Public/css/mobiles/font-awesome/css/font-awesome.min.css">
	<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
	<script type="text/javascript" src="/Public/2017/js/LocalResizeIMG.js"></script>
	<script type="text/javascript" src="/Public/2017/js/patch/mobileBUGFix.mini.js"></script>
	<script type="text/javascript" src="/Public/layer/layer.js"></script>
	<script type="text/javascript" src="/Public/layer/plugin/layer.js"></script>
	<script src="/Public/2017/assets/js/jquery.validate.js" type="text/javascript"></script>
	<style>
		.img {
			width: 5rem;
			height: 5rem;
			background: url(/Public/images/mobiles/z_add.png) no-repeat;
			background-size: 100% 100%;
			margin-right: 0.1rem;
		}
		.img input{
			display:none;
		}
	</style>
</head>
<body>

<div class="wrap2">
	<div class="jiaos_t jiaos_t_j">
		<dl>
			<dt>
				<a href="<?php echo U('mobile.php/Ucenter/avatar',array('user_id'=>$userinfo['student_id'],'user_flag'=>3));?>">
					<img src="<?php echo ((isset($userinfo["student_avatar"]) && ($userinfo["student_avatar"] !== ""))?($userinfo["student_avatar"]):'/Public/images/mobiles/default.png'); ?>" alt=""/>
				</a>
			</dt>
			<dd class="dd_renw">
				<div class="d_name"><?php echo ($userinfo["student_name"]); ?><!-- <?php if($userinfo["student_sex"] == '1'): ?><img src="/Public/images/mobiles/ic_sex_m.png" alt="" />
		<?php else: ?>
		<img src="/Public/images/mobiles/ic_sex_w.png" alt="" /><?php endif; ?> --></div>
			<div class="d_renw">
			<div class="dd_jind"><span class="s_jind"><i style=" width:<?php echo ($percent); ?>%;"></i></span></div>
			已累计打卡<?php echo ($day); ?>天</div>

			</dd>
		</dl>
	</div>



	<input type="hidden" id="day" value="<?php echo ($day); ?>">
	<input type="hidden" id="tan" value="<?php echo ($tan); ?>">

	
	<div class="line_hr"></div>
	<?php if($punch == 1): ?><div class="renw_t">
		<span>累计打卡第 <?php echo ($day); ?> 天</span>
	</div><?php endif; ?>
	<div class="line_hr"></div>
	<div class="renw_list">
		<form name="myform" id="myform" action="<?php echo U('mobile.php/Ucenter/punch_handle');?>" method="post">

		<?php if($punch == 1): ?><dl class="dl_rw">
						<dt>
							<strong>照片最好具备家长、
孩子、绘本3元素</strong>
						</dt>
						<dd class="dd_btn">
							<div style="background-color:#FFF;">
								<textarea name="content" rows="3" style="width:100%;border:none;" placeholder="请输入您的绘本名称"></textarea>
							</div>
						</dd>

						<dd class="dd_btn" style="display:inline-block;">
							<div class="img img1" data-id="pic" name="img1">
								<input name="image1" id="pic" type="file"/>
							</div>
						</dd>

<!-- 							<dd class="dd_btn" style="display:inline-block;">
							<div class="img img2" data-id="pic" name="img2">
								<input name="image2" id="pic1" type="file"/>
							</div>
						</dd> -->

						
					</dl>
					<div class="btn_box btn_boxadd"><a href="javascript:void(0);" class="btn btn2 btn_addjz">打卡</a></div>
					<?php else: ?>
					
					<dl class="dl_rw">
						<!-- <a href="'.U('punchShare',array('ids'=>$ids)).'"> -->
						<a href="<?php echo U('mobile.php/Ucenter/punchShare',array('student_id'=>$userinfo['student_id']));?>">
						<dt>
							<strong style="float:left;">打卡分享</strong><span style="float:right;color:#aee078;">查看详情</span>
						</dt>
						</a>
					</dl>

					<dl class="dl_rw">
						<dt>
							<strong style="float:left;">活动奖励</strong>
						</dt>
						<p style="padding:3px 14px;color:#FF9A9A;">1、成功坚持21天阅读签到和分享的启航巴士会员，可获赠“我是成长小天才”奖状一张及勋章一枚,同时可或赠500颗金豆。</p>
						<p style="padding:3px 14px;color:#FF9A9A;">2、成功坚持21天阅读签到和分享的启航巴士非会员注册用户，可获赠“我是成长小天才”奖状一张。</p>
						<p style="padding:3px 14px;color:#FF9A9A;">3、奖励领取方式：奖状及勋章启航巴士客服会定期发放到孩子幼儿园所在班级，由班主任发给孩子。</p>
					</dl><?php endif; ?>
				<input type="hidden" name='student_id' value="<?php echo ($userinfo['student_id']); ?>">
		</form>
	</div>
	<a href="<?php echo U('mobile.php/Ucenter/index');?>">
		<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
	</a>
</div>

<input type="hidden" id="refreshed" value="no">
<script type="text/javascript">
onload=function(){
var e=document.getElementById("refreshed");
if(e.value=="no")e.value="yes";
else{e.value="no";location.reload();}
}
</script >


<script>
var day = $("#day").val();
var tan = $("#tan").val();
// if(tan == 0){
// 	if(day == 2)
// 	{
// 		 layer.open({
//                     // title:false,
//                     // content:'签到成功,本次签到获得'+ret.user_points+'金豆',
//                     content:'好样的,千里之行始于足下,<br />一个缤纷多彩的绘本世界<br />正向你和孩子打开.别忘了分享<br />海报到朋友圈,让好友共同见证<br />孩子的成长',
//                     btn:['好的'],
//                     yes:function(index){
//                         layer.closeAll();
//                         // location.reload();
//                         location.href="<?php echo U('mobile.php/Ucenter/punchShare');?>";
//                     },
//                 });
// 	}
// }
              
</script>


<script type="text/javascript">
	$(function () {
		$(".btn_addjz").click(function () {
			var tupian = $('.tupian').val();
			if(tupian == undefined){
				layer.msg('请选择图片',{time:800});
					return false;
			}
			$("#myform").submit();
		});
	});

	$(".img1,.img2").click(function(){
		var now = $(this);
		var nm = $(this).prop("className");
		nm = nm.replace(/\s/g,"");
		var id = now.attr('data-id');
		$('#'+id).localResizeIMG({
			width: 500,
			quality: 1,
			success: function (result) {
				wait = layer.load(2);
				var submitData={
					base64_string:result.clearBase64,
				};
				$.ajax({
					type: "POST",
					url: "<?php echo U('uploadPic');?>",
					data: submitData,
					dataType:"json",
					success: function(data){
						layer.close(wait);
						if (0 == data.status) {
							alert(data.content);
							return false;
						}else{
							layer.msg('上传成功',{time:1000});
							var attstr= '<img style="width:60px;height:60px;vertical-align:baseline;" src="/'+data.url+'"/><input type="hidden" class="tupian" value="'+data.url+'" name="'+nm+'">';
							now.html(attstr);

							$(".img2 input").attr("id","pic");
							return false;
						}
					},
					complete :function(XMLHttpRequest, textStatus){
					},
					error:function(XMLHttpRequest, textStatus, errorThrown){ //上传失败
						layer.close(wait);
//						alert(XMLHttpRequest.status);
//						alert(XMLHttpRequest.readyState);
						alert(textStatus);
					}
				});
			}
		});
		pic.click();
	});
</script>

<script>
$(".btn_boxadd").click(function(){
	// var tupian = $('.tupian').val();
	// alert(tupian);
});

</script>

</body>
</html>