<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <meta content="telephone=no" name="format-detection"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta name="renderer" content="webkit">
    <title>请稍等，定位中...</title>
    <script type="text/javascript" src="/Public/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="/Public/wechatJs/jweixin-1.0.0.js"></script>
</head>
<body>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/layer.js"></script>
<script type="text/javascript">
    setTimeout("loading()",500);

    wx.config({
                //debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                appId: '<?php echo ($signPackage["appId"]); ?>', // 必填，公众号的唯一标识
                timestamp: <?php echo ($signPackage["timestamp"]); ?> , // 必填，生成签名的时间戳
            nonceStr: '<?php echo ($signPackage["nonceStr"]); ?>', // 必填，生成签名的随机串
            signature: '<?php echo ($signPackage["signature"]); ?>',// 必填，签名，见附录1
            jsApiList: ['getLocation'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });

    wx.ready(function(){
        wx.getLocation({
            type: 'gcj02', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
            success: function (res) {
                //gcj02转bd09
                var x_PI = 3.14159265358979324 * 3000.0 / 180.0;
                var z = Math.sqrt(res.longitude * res.longitude + res.latitude * res.latitude) + 0.00002 * Math.sin(res.latitude * x_PI);
                var theta = Math.atan2(res.latitude, res.longitude) + 0.000003 * Math.cos(res.longitude * x_PI);
                var longitude = z * Math.cos(theta) + 0.0065;
                var latitude = z * Math.sin(theta) + 0.006;
                $.ajax({
                    type :"post",
                    url :"<?php echo U('setLocation');?>",
                    data: {latitude: latitude,longitude: longitude},
                    dataType : "json",
//                    complete: function(){
//                        location.href = "<?php echo U('test');?>";
//                    },
                });
                location.href = "<?php echo U('index');?>";
            },
            cancel:function(res){
                location.href = "<?php echo U('index');?>";
            },
            fail: function(res) {
                location.href = "<?php echo U('index');?>";
            }
        });
    });
    function transformlat(lng, lat) {
        var ret = -100.0 + 2.0 * lng + 3.0 * lat + 0.2 * lat * lat + 0.1 * lng * lat + 0.2 * Math.sqrt(Math.abs(lng));
        ret += (20.0 * Math.sin(6.0 * lng * PI) + 20.0 * Math.sin(2.0 * lng * PI)) * 2.0 / 3.0;
        ret += (20.0 * Math.sin(lat * PI) + 40.0 * Math.sin(lat / 3.0 * PI)) * 2.0 / 3.0;
        ret += (160.0 * Math.sin(lat / 12.0 * PI) + 320 * Math.sin(lat * PI / 30.0)) * 2.0 / 3.0;
        return ret
    }

    function transformlng(lng, lat) {
        var ret = 300.0 + lng + 2.0 * lat + 0.1 * lng * lng + 0.1 * lng * lat + 0.1 * Math.sqrt(Math.abs(lng));
        ret += (20.0 * Math.sin(6.0 * lng * PI) + 20.0 * Math.sin(2.0 * lng * PI)) * 2.0 / 3.0;
        ret += (20.0 * Math.sin(lng * PI) + 40.0 * Math.sin(lng / 3.0 * PI)) * 2.0 / 3.0;
        ret += (150.0 * Math.sin(lng / 12.0 * PI) + 300.0 * Math.sin(lng / 30.0 * PI)) * 2.0 / 3.0;
        return ret
    }

    function loading(){
        layer.msg('请稍等，定位中...', {icon: 16,shade:0.4,time:50000});
    }
</script>
</body>
</html>