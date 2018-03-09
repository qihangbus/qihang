<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>修改头像</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>
<body>
<div class="wrap2 mbg"> 
<dl class="dl_up js_uploadBox">
    <form name="myform" id="myform" enctype="multipart/form-data" method="post" action="<?php echo U('mobile.php/SIndex/update_avatar');?>">
	<input type="hidden" name="user_id" value="<?php echo ($user_id); ?>">
	<input type="hidden" name="user_flag" value="1">
    <input type="hidden" name="avatar_old" value="<?php echo ($avatar); ?>">
	<dt><span class="js_showBox ">
        <input type="hidden" name="avatars" value="<?php echo ($avatar); ?>">
        <img class="js_logoBox" id="avatar" src="<?php echo ($avatar); ?>" width="100px" ></span></dt>
	<dd class="dd_upp"><a class="a_upload " ><div class="">选择图片<input type="file" name="avatar" class="input_file js_upFile" value="浏览" /></div></a><br><br><a class="a_upload " ><div class="btn_p">开始上传</div></a>  </dd>	
    </form>
</dl>
<a href="<?php echo U('mobile.php/SIndex/Index',array('id'=>$user_id));?>">
<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
</a>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/jquery.uploadView.js"></script>
<script src="/Public/js/mobiles/layer/layer.js"></script>
<script type="text/javascript">
$(function(){
    $(".btn_p").click(function(){
        $("#myform").submit();
    });
})	
$(".js_upFile").uploadView({
    uploadBox: '.js_uploadBox',//设置上传框容器
    showBox : '.js_showBox',//设置显示预览图片的容器
    width : 100, //预览图片的宽度，单位px
    height : 100, //预览图片的高度，单位px
    allowType: ["gif", "jpeg", "jpg", "bmp", "png"], //允许上传图片的类型
    maxSize :2, //允许上传图片的最大尺寸，单位M
    success:function(e){
        /*var avatar = $('#avatar').attr('src') + "";
        var user_id = $("#user_id").val();
        var user_flag = $("#user_flag").val();
        $.post("<?php echo U('mobile.php/SIndex/update_avatar');?>",{user_id:user_id,user_flag:user_flag,avatars:avatar},function(ret){
            if(ret){
                layer.alert('修改头像成功', {
                    title: false,
                    closeBtn: 0,
                    btn: ['关闭']
                },function(){
                    layer.closeAll();
                });
            }
        },'json');*/
    }
});

</script>
</body>
</html>