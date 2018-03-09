<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta name="renderer" content="webkit">
    <title>修改密码</title>
    <link rel="stylesheet" href="/Public/css/mobiles/base.css">
    <link rel="stylesheet" href="/Public/css/mobiles/app.css">
    <link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body>

<div class="wrap2">

    <div class="address_t"><a href="javascript:history.back(-1);"><span>取消</span></a></div>
    <div class="line_hr"></div>
    <form name="myform" id="myform" method="post" onsubmit="return check(this);">
        <ul class="ul_form ul_form_jz">
            <li><span class="s_label">旧密码</span><span class="s_input "><input type="password" name="old_pwd" id="old_pwd" value="<?php echo ($parent_info["parent_mobile"]); ?>"/></span></li>
            <li><span class="s_label">新密码</span><span class="s_input "><input type="password" name="new_pwd" id="new_pwd" value="<?php echo ($parent_info["parent_name"]); ?>"/></span></li>
            <li><span class="s_label">重复密码</span><span class="s_input "><input type="password" name="new_pwd2" id="new_pwd2" value="<?php echo ($parent_info["parent_name"]); ?>"/></span></li>
        </ul>

        <br><br><br>
        <div class="btn_box btn_boxadd"><a href="javascript:void(0);" id="btn_save" class="btn btn2 btn_addjz">保存</a></div>

    </form>

    <a href="<?php echo U('mobile.php/Ucenter/index');?>">
        <span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
    </a>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script src="/Public/js/mobiles/layer/layer.js"></script>
<script type="text/javascript">
    function check()
    {
        var old_pwd = $("#old_pwd").val();
        var new_pwd = $("#new_pwd").val();
        var new_pwd2 = $("#new_pwd2").val();

        if(old_pwd == ''){
            layer.msg('请输入旧密码',{time:800});
            return false;
        }

        if(new_pwd == ''){
            layer.msg('请输入新密码',{time:800});
            return false;
        }else if(new_pwd.length < 6){
            layer.msg('新密码不能少于6位',{time:800});
            return false;
        }

        if(new_pwd != new_pwd2){
            layer.msg('两次密码不一样',{time:800});
            return false;
        }
        return true;
    }

    $(function(){
        $("#btn_save").click(function(){
            $("#myform").submit();
        });
    })
</script>
</body>
</html>