<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta name="renderer" content="webkit">
    <title>绘本数量计算器</title>
    <link rel="stylesheet" href="/Public/css/mobiles/base.css">
    <link rel="stylesheet" href="/Public/css/mobiles/app.css">
    <link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body>

<div class="wrap2">

        <ul class="ul_form ul_form_jz">

            <li>
                <span class="s_label">套餐类型</span>
                <span class="s_radiobox ">
                     <input type="radio" name="meal" value="2" checked/> 一周四本
                     <input type="radio" name="meal" value="1" style="margin-left:10%"/> 一周两本
                </span>
            </li>
            <li>
                <span class="s_label">最大班级人数</span>
                <span class="s_input">
                    <input type="text" name="class_max_num" style="text-align: right;"/>
                </span>
            </li>
            <li>
                <span class="s_label">大班班级数</span>
                <span class="s_input">
                    <input type="text" name="db_num" style="text-align: right;"/>
                </span>
            </li>
            <li>
                <span class="s_label">中班班级数</span>
                <span class="s_input">
                    <input type="text" name="zb_num" style="text-align: right;"/>
                </span>
            </li>
            <li>
                <span class="s_label">小班班级数</span>
                <span class="s_input">
                    <input type="text" name="xb_num" style="text-align: right;"/>
                </span>
            </li>
        </ul>
        <div style="height: 60px;background-color: #ffffff;margin-top: 5px;">
            <span style="line-height:60px;color: #999;margin-left: 10px;font-size: 16px;">所需绘本数量</span>
            <span style="float:right;margin-right: 5%">
                <b id="result" style="line-height: 60px;font-size: 23px;color:#ee1e2d"></b>
            </span>
        </div>
        <div class="btn_box btn_boxadd" style="margin-top: 20px;">
            <a href="javascript:void(0);" id="counter" class="btn btn2 btn_addjz">计 算</a>
        </div>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/layer.js"></script>
<script type="text/javascript">
    $(function(){
        $('#counter').click(function(){
            var class_max_num = $('input[name="class_max_num"]').val();
            if(!class_max_num){
                layer.msg('请填写最大班级人数',{time:800});
                return false;
            }
            var db_num = $('input[name="db_num"]').val();
            if(!db_num){
                layer.msg('请填写大班班级数量',{time:800});
                return false;
            }
            var zb_num = $('input[name="zb_num"]').val();
            if(!zb_num){
                layer.msg('请填写中班班级数量',{time:800});
                return false;
            }
            var xb_num = $('input[name="xb_num"]').val();
            if(!xb_num){
                layer.msg('请填写小班班级数量',{time:800});
                return false;
            }
            var meal = $('input[name="meal"]:checked').val()
            var grade_min_num = meal*80;
            var class_num = class_max_num*meal + 5*meal;
            var db = db_num*class_num;
            if(db < grade_min_num) db = grade_min_num;
            var zb = zb_num*class_num;
            if(zb < grade_min_num) zb = grade_min_num;
            var xb = xb_num*class_num;
            if(xb < grade_min_num) xb = grade_min_num;
            var result = db+zb+xb;
            $('#result').html(result);
        });
    });

</script>
</body>
</html>