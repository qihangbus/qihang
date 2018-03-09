<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="#" type="image/png">
    <title>用户登陆管理后台_<?php echo L('_WEBSITE_NAME_');?></title>
    <link href="/Public/css/style.css" rel="stylesheet">
    <link href="/Public/css/style-responsive.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/Public/js/html5shiv.js"></script>
    <script src="/Public/js/respond.min.js"></script>
    <![endif]-->
</head>

<body class="login-body">

<div class="container">

    <form class="form-signin" method="post" id="myform"  name="myform" action="<?php echo U('Login/validate');?>"  onsubmit="return checkForm()">
        <div class="form-signin-heading text-center">
            <h1 class="sign-title">登陆管理后台</h1>
            <img src="/Public/images/login_logo.png" alt="" style="width:118px;"/>
        </div>
        <div class="login-wrap">
            <input type="text" name="username" data-original-title="Tooltip on bottom" title="" data-placement="bottom" data-toggle="tooltip"  id="username" class="form-control  tooltips" placeholder="请输入用户名" autofocus>
            <input type="password" name="password" id="password" class="form-control" placeholder="请输入密码">

            <input type="submit" value="登陆" class="btn btn-lg btn-login btn-block"/>

            <div class="registration">
                <a class="" href="">
                    返回首页
                </a>
            </div>
            <label class="checkbox" style="display:none;">
                <input type="checkbox" name="remember_me" id="remember_me" checked value="1"> 保存登陆信息
                <span class="pull-right">
                    <a data-toggle="modal" href="#myModal"> 忘记密码?</a>

                </span>
            </label>

        </div>

        <!-- Modal -->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">忘记登陆密码 ?</h4>
                    </div>
                    <div class="modal-body">
                        <p>输入Email地址，重置登陆密码.</p>
                        <input type="text" name="email" placeholder="请输入您的Email" autocomplete="off" class="form-control placeholder-no-fix">

                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                        <button class="btn btn-primary" type="button">重置</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal -->
    </form>
</div>
<script src="/Public/js/jquery-1.10.2.min.js"></script>
<script src="/Public/js/bootstrap.min.js"></script>
<script src="/Public/js/modernizr.min.js"></script>
<!--common scripts for all pages-->
<script src="/Public/js/scripts.js"></script>
<script type="text/javascript">
    function checkForm(){
        var username = $('#username').val();
        var pwd = $('#password').val();
        var remember = $("#remember_me").val();
        if(username == '' || username == null){
            $("#username").attr('placeholder','用户名不能为空');
            $("#username").focus();
            return false;
        }
        if(pwd == '' || pwd == null){
            $("#password").attr('placeholder','密码不能为空');
            $("#password").focus();
            return false;
        }
        $("#myform").submit();
    }
</script>
</body>
</html>