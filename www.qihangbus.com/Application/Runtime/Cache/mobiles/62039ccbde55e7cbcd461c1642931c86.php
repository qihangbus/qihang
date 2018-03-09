<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link href="/Public/css/mobiles/canvas.css" rel="stylesheet" type="text/css"> 
</head>
<body>
	<canvas id="myCanvas">
	Your browser does not support the HTML5 canvas tag.
	</canvas>
<script src="/Public/js/mobiles/lib/jquery.min.js"></script>

<script>
  var canvas=document.getElementById("myCanvas");
  var ctx = canvas.getContext("2d");
  ctx.canvas.width  = window.innerWidth;
  ctx.canvas.height = window.innerHeight;
</script>

<script>
    //准备img对象 
    var img = new Image(); 
    var img1 = new Image();
    // img1.src = './Public/images/1511405335.jpeg';
    // img1.src = './Public/images/542704807756015015.jpg';
    img1.src = "../../<?php echo ($info['image']); ?>";
    img.src = './Public/images/145791377379906605.jpg';
    // 加载完成开始绘制
    img.onload=function(){
       //准备canvas环境 
       var canvas=document.getElementById("myCanvas");
       var ctx=canvas.getContext("2d");
       // 绘制图片
       // ctx.drawImage(img,0,0,981,1641);
       ctx.drawImage(img,0,0,$(window).get(0).innerWidth,$(window).get(0).innerHeight);
       // 图片坐标x,y,w,h
       var w1 = Math.ceil((299/981)*$(window).get(0).innerWidth);
       var h1 = (313/1641)*$(document).height();
       // ctx.drawImage(img1,116,543,350,460);
       ctx.drawImage(img1,w1,h1,393,526);
       // 绘制水印
       ctx.font="43px microsoft yahei";
       // ctx.fillStyle = "rgba(255,255,255,0.5)";
       var w = (496/981)*$(window).get(0).innerWidth;
       var h = (993/1641)*$(window).get(0).innerHeight;
       ctx.fillText("<?php echo ($student_name); ?>",w,h);
       var w2 = (471/981)*$(window).get(0).innerWidth;
       var h2 = (1163/1641)*$(window).get(0).innerHeight;
       ctx.fillText("<?php echo ($info['content']); ?>",w2,h2);
    }

</script>
<script>
  $(window).resize(resizeCanvas);
	function resizeCanvas() {  
        $("#myCanvas").attr("width", $(window).get(0).innerWidth);  
        $("#myCanvas").attr("height", $(window).get(0).innerHeight);
        // context.fillRect(0, 0, $("#myCanvas").width(), $("#myCanvas").height());  
 };  
 resizeCanvas(); 
</script>
</body>
</html>