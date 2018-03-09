<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta content="telephone=no" name="format-detection"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta name="renderer" content="webkit">
    <title>家长注册</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/>
    <link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
</head>
<body class="mbg" style="overflow:auto;">
<style type="text/css">
    ul select {
        width: 24%;
        height: 10%;
        border:none;
    }

    .getcode {
        background-color: orange;
        border-radius: 20px 20px;
        color: #fff;
        padding: 8px 10px;
        border: none;
        float: right;
        margin: 3px 0 0 0;
    }
</style>
<div class="wrap2 ">
    <div class="page_login">
        <div class="login_t" style="padding: 32px 0 16px;"><img src="/Public/images/mobiles/login_logo.png" alt=""/>
        </div>
        <form name="myform" id="myform" method="post">
            <ul class="ul_form">
                <li>
                     <span class="s_input " style="margin-left: 9px;">地区：
                        <select name="province" id="province">
                            <option value="">-省-</option>
                            <?php if(is_array($province_list)): $i = 0; $__LIST__ = $province_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pl): $mod = ($i % 2 );++$i;?><option value="<?php echo ($pl["region_id"]); ?>" <?php if(($p_name) == $pl["region_name"]): ?>selected<?php endif; ?> ><?php echo ($pl["region_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                       <select name="city" id="city">
                           <option value="">-市-</option>
                           <?php if(is_array($city_list)): $i = 0; $__LIST__ = $city_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cl): $mod = ($i % 2 );++$i;?><option value="<?php echo ($cl["region_id"]); ?>" <?php if(($c_name) == $cl["region_name"]): ?>selected<?php endif; ?> ><?php echo ($cl["region_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                       </select>
                       <select name="district" id="district">
                           <option value="">-区-</option>
                           <?php if(is_array($zone_list)): $i = 0; $__LIST__ = $zone_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["region_id"]); ?>" <?php if(($z_name) == $v["region_name"]): ?>selected<?php endif; ?> ><?php echo ($v["region_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                       </select>
                     </span>
                </li>
            </ul>
            <ul class="ul_form">
                <li>
    <span class="s_input " style="margin-left: 9px;">学校：
        <select name="school" id="school">
            <option value="">-学校-</option>
            <?php if(is_array($school_list)): $i = 0; $__LIST__ = $school_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pl): $mod = ($i % 2 );++$i;?><option value="<?php echo ($pl["school_id"]); ?>"><?php echo ($pl["school_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
        </select>
           <select name="grade" id="grade">
               <option value="">-年级-</option>
               <?php if(is_array($grade_list)): $i = 0; $__LIST__ = $grade_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cl): $mod = ($i % 2 );++$i;?><option value="<?php echo ($cl["grade_id"]); ?>"><?php echo ($cl["grade_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
           </select>
         <select name="class" id="class">
             <option value="">-班级-</option>
             <?php if(is_array($class_list)): $i = 0; $__LIST__ = $class_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dl): $mod = ($i % 2 );++$i;?><option value="<?php echo ($dl["class_id"]); ?>"><?php echo ($dl["class_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
         </select>
     </span>
                </li>
            </ul>
            <ul class="ul_form ul_form_jz">
                <li class="li_tel" style="padding-left:0px;margin-bottom:5px;">
<span class="s_input ">
<input type="text" id="student_name" name="student_name" placeholder="学生姓名" maxlength="11" class="l_input"/>
</span>
                </li>
                <li class="li_tel" style="padding-left:0px;margin-bottom:5px;">
<span class="s_input ">
<input type="tel" id="mobile" name="mobile" placeholder="手机号码" maxlength="11" class="l_input"/>
                </li>
                <li class="li_tel" style="padding-left:0px;margin-bottom:5px;">
<span class="s_input">
<input type="text" id="code" name="code" placeholder="验证码" class="l_input" style="width:60%;"/>
<button type="button" id="sendcode" class="getcode">获取验证码</button>
														   </span>

                </li>
                <div style="color:#828080;margin-top: 10px;text-align: right">默认密码为123456，注意及时更改。</div>
            </ul>
            <ul class="ul_loginbtn" style="margin:15px 0 5px 0">
                <li>
                    <a href="javascript:void(0)" id="register" class="btn btn_login btn_a">注册</a>
                    <span class="btn btn_login btn_dis btn_span" style="display:none;">注册</span>
                </li>
            </ul>
        </form>
    </div>
</div>
<!--page2-->
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script type="text/javascript">
    //------------------查询数据获取地区  学校  班级  学生信息
    $(document).ready(function () {
        $("#province").change(function () {
            var region_id = $(this).val();
            $.post("<?php echo U('mobile.php/Parentregister/address');?>", {
                region_id: region_id,
                region_level: 2
            }, function (result) {
                $("#city")[0].options.length = 0;
                $("#city")[0].options.add(new Option("-市-", ""));
                for (var i = 0; i < result.length; i++) {
                    var date = result[i];
                    $("#city")[0].options.add(new Option(date.region_name, date.region_id));
                }
                $("#district")[0].options.length = 0;
                $("#district")[0].options.add(new Option("-区-", ""));
                $("#school")[0].options.length = 0;
                $("#school")[0].options.add(new Option("-学校-", ""));
                $("#grade")[0].options.length = 0;
                $("#grade")[0].options.add(new Option("-年级-", ""));
                $("#class")[0].options.length = 0;
                $("#class")[0].options.add(new Option("-班级-", ""));
            }, 'json');
        });

        $("#city").change(function () {
            var region_id = $(this).val();
            $.post("<?php echo U('mobile.php/Parentregister/address');?>", {
                region_id: region_id,
                region_level: 3
            }, function (result) {
                $("#district")[0].options.length = 0;
                $("#district")[0].options.add(new Option("-区-", ""));
                for (var i = 0; i < result.length; i++) {
                    var date = result[i];
                    $("#district")[0].options.add(new Option(date.region_name, date.region_id));
                }
                $("#school")[0].options.length = 0;
                $("#school")[0].options.add(new Option("-学校-", ""));
                $("#grade")[0].options.length = 0;
                $("#grade")[0].options.add(new Option("-年级-", ""));
                $("#class")[0].options.length = 0;
                $("#class")[0].options.add(new Option("-班级-", ""));
            }, 'json');
        });
        $("#district").change(function () {
            var region_id = $(this).val();
            $.post("<?php echo U('mobile.php/Parentregister/schools');?>", {region_id: region_id}, function (result) {
                $("#school")[0].options.length = 0;
                $("#school")[0].options.add(new Option("-学校-", ""));
                for (var i = 0; i < result.length; i++) {
                    var date = result[i];
                    $("#school")[0].options.add(new Option(date.school_name, date.school_id));
                }
                $("#grade")[0].options.length = 0;
                $("#grade")[0].options.add(new Option("-年级-", ""));
                $("#class")[0].options.length = 0;
                $("#class")[0].options.add(new Option("-班级-", ""));
            }, 'json');
        });
        $("#school").change(function () {
            var school_id = $(this).val();
            $.post("<?php echo U('mobile.php/Parentregister/grade');?>", {school_id: school_id}, function (result) {
                $("#grade")[0].options.length = 0;
                $("#grade")[0].options.add(new Option("-年级-", ""));
                for (var i = 0; i < result.length; i++) {
                    var date = result[i];
                    $("#grade")[0].options.add(new Option(date.grade_name, date.grade_id));
                }
                $("#class")[0].options.length = 0;
                $("#class")[0].options.add(new Option("-班级-", ""));
            }, 'json');
        });
        $("#grade").change(function () {
            var grade_id = $(this).val();
            $.post("<?php echo U('mobile.php/Parentregister/cla');?>", {grade_id: grade_id}, function (result) {
                $("#class")[0].options.length = 0;
                $("#class")[0].options.add(new Option("-班级-", ""));
                for (var i = 0; i < result.length; i++) {
                    var date = result[i];
                    $("#class")[0].options.add(new Option(date.class_name, date.class_id));
                }
            }, 'json');
        });


//验证码
        var countdown = 60;

        function settime() {
            var obj = $("#sendcode");
            if (countdown == 0) {
                obj.removeAttr("disabled");
                obj.html("获取验证码");
                countdown = 60;
                return;
            } else {
                obj.attr("disabled", 'true');
                obj.html("重新发送(" + countdown + ")");
                countdown--;
            }
            setTimeout(function () {
                settime();
            }, 1000);
        }

        $('#sendcode').click(function () {

            var mobile = $("#mobile").val();
            if (!mobile.match(/^((1[3-8]{1})+\d{9})$/)) {
                layer.open({content: '手机号不正确', skin: 'msg', time: 2});
                return false;
            } else {
                $.post("<?php echo U('mobile.php/Parentregister/sendSMS');?>", {mobile: mobile}, function (data) {
                    if (data.status == 1) {
                        layer.open({content: data.msg, skin: 'msg', time: 2});
                        settime();
                    } else {
                        layer.open({content: data.msg, skin: 'msg', time: 2});
                    }
                }, "json");
            }
        });

//提交判断
        $("#register").click(function () {

            var province = $("#province").val();
            var city = $("#city").val();
            var district = $("#district").val();
            var school = $("#school").val();
            var grade = $("#grade").val();
            var cla = $("#class").val();
            var student = $("#student_name").val();
            var parent = $("#parent_name").val();
            var code = $("#code").val();
            var mobile = $("#mobile").val();
            if (province == '') {
                layer.open({content: '请选择省份', skin: 'msg', time: 2});
                return false;
            }
            if (city == '') {
                layer.open({content: '请选择城市', skin: 'msg', time: 2});
                return false;
            }
            if (district == '') {
                layer.open({content: '请选择区县', skin: 'msg', time: 2});
                return false;
            }
            if (school == '') {
                layer.open({content: '请选择学校', skin: 'msg', time: 2});
                return false;
            }
            if (grade == '') {
                layer.open({content: '请选择年级', skin: 'msg', time: 2});
                return false;
            }
            if (cla == '') {
                layer.open({content: '请选择班级', skin: 'msg', time: 2});
                return false;
            }
            if (student == '') {
                layer.open({content: '请输入学生姓名', skin: 'msg', time: 2});
                return false;
            }
            if (code == '') {
                layer.open({content: '请输入验证码', skin: 'msg', time: 2});
                return false;
            }
            $.ajax({
                url: "mobile.php/Parentregister/register",
                type: "POST",
                async: false,
                data: {
                    school: school,
                    grade: grade,
                    cla: cla,
                    student: student,
                    mobile: mobile,
                    code: code,
                    parent: parent,
                },
                success: function (data) {
                    if (data == 4) {
                        layer.open({
                            content: '验证码不正确',
                            skin: 'msg',
                            time: 2
                        });
                    } else if (data == 3) {
                        layer.open({
                            content: '未查询到该学生信息,请确认',
                            skin: 'msg',
                            time: 2
                        });
                    } else if (data == 5) {
                        layer.open({
                            content: '手机号已注册',
                            skin: 'msg',
                            time: 2
                        });
                    } else {
                        layer.open({
                            content: '注册成功!'
                            , btn: ['登录']
                            , yes: function (index) {
                                window.location.href = "mobile.php/Oauth";
                                layer.close(index);
                            }
                        });
                    }
                }
            });
        });
    });
</script>
</body>
</html>